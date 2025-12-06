<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProjectFileController extends Controller
{
    /**
     * Display all files for a project.
     */
    public function index(Project $project)
    {
        $user = auth()->user();

        // Check if user can access this project
        $this->authorizeProjectAccess($user, $project);

        $files = ProjectFile::where('project_id', $project->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        // Group files by type
        $filesByType = ProjectFile::where('project_id', $project->id)
            ->selectRaw('file_type, COUNT(*) as count, SUM(file_size) as total_size')
            ->groupBy('file_type')
            ->get();

        return view('pages.projects.files.index', compact('project', 'files', 'filesByType'));
    }

    /**
     * Store new file(s).
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $project);

        $request->validate([
            'files.*' => 'required|file|max:51200', // 50MB max
            'description' => 'nullable|string|max:500',
        ]);

        if (!$request->hasFile('files')) {
            return response()->json(['success' => false, 'message' => 'No files provided'], 400);
        }

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $path = $file->store('project-files/' . $project->id, 'public');

                $projectFile = ProjectFile::create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'file_name' => $originalName,
                    'file_path' => $path,
                    'file_type' => $extension,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'description' => $request->description,
                ]);

                $uploadedFiles[] = $projectFile;
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) . ' file(s) uploaded successfully!',
                'files' => $uploadedFiles,
            ]);
        }

        return redirect()->back()
            ->with('success', count($uploadedFiles) . ' file(s) uploaded successfully!');
    }

    /**
     * Download a file.
     */
    public function download(ProjectFile $file)
    {
        $user = auth()->user();
        $this->authorizeProjectAccess($user, $file->project);

        // Increment download count
        $file->increment('download_count');

        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }

    /**
     * Delete a file.
     */
    public function destroy(ProjectFile $file)
    {
        $user = auth()->user();

        // Only file owner, project team lead, or admin can delete
        if (!Gate::allows('admin') && $file->user_id !== $user->id) {
            // Check if user is team lead of this project
            if (!Gate::allows('team_lead')) {
                abort(403, 'Unauthorized to delete this file.');
            }

            $teamLeadTeams = $user->leadingTeams->pluck('id');
            if (!$teamLeadTeams->contains($file->project->team)) {
                abort(403, 'Unauthorized to delete this file.');
            }
        }

        // Delete physical file
        Storage::disk('public')->delete($file->file_path);

        // Delete record
        $file->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully!',
            ]);
        }

        return redirect()->back()
            ->with('success', 'File deleted successfully!');
    }

    /**
     * Check if user can access project.
     */
    private function authorizeProjectAccess($user, $project)
    {
        if (Gate::allows('admin')) {
            return true;
        }

        if (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');
            if (!$teamLeadTeams->contains($project->team)) {
                abort(403, 'Unauthorized access to this project.');
            }
            return true;
        }

        // Team member must be assigned to project
        if (!$user->projects->contains($project->id)) {
            abort(403, 'Unauthorized access to this project.');
        }

        return true;
    }
}
