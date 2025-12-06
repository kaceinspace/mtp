@extends('layouts.dashboard')

@section('title', 'Project Files - ' . $project->title)

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">📁 Project Files</h2>
            <p class="text-gray-600 mt-1">{{ $project->title }}</p>
        </div>
        <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            ← Back to Project
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6" x-data="fileManager()">
    <!-- File Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Files</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $files->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        @foreach($filesByType as $type)
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">{{ $type->file_type }}</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $type->count }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($type->total_size / 1048576, 2) }} MB</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Upload Files</h3>
        </div>

        <form @submit.prevent="uploadFiles" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Files</label>
                <input type="file"
                       name="files[]"
                       multiple
                       @change="handleFileSelect($event)"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Maximum file size: 50MB per file</p>
            </div>

            <div class="mb-4" x-show="selectedFiles.length > 0">
                <label class="block text-sm font-medium text-gray-700 mb-2">Selected Files</label>
                <div class="bg-gray-50 rounded-lg p-3">
                    <template x-for="(file, index) in selectedFiles" :key="index">
                        <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                            <span class="text-sm text-gray-700" x-text="file.name"></span>
                            <span class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                <textarea x-model="description" name="description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <button type="submit"
                    :disabled="uploading || selectedFiles.length === 0"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!uploading">📤 Upload Files</span>
                <span x-show="uploading">Uploading...</span>
            </button>
        </form>
    </div>

    <!-- Files List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">All Files</h3>
                <div class="flex space-x-2">
                    <select x-model="filterType" @change="filterFiles" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        @foreach($filesByType as $type)
                            <option value="{{ $type->file_type }}">{{ strtoupper($type->file_type) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($files as $file)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="text-4xl">{{ $file->file_icon }}</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $file->file_name }}</h4>
                                @if($file->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $file->description }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>📤 {{ $file->user->name }}</span>
                                    <span>📏 {{ $file->formatted_size }}</span>
                                    <span>📅 {{ $file->created_at->format('M d, Y H:i') }}</span>
                                    <span>⬇️ {{ $file->download_count }} downloads</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            @if($file->canPreview())
                                <button @click="previewFile({{ $file->id }}, '{{ $file->file_name }}', '{{ asset('storage/' . $file->file_path) }}', '{{ $file->file_type }}')"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Preview">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            @endif
                            <a href="{{ route('files.download', $file) }}"
                               class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Download">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                            @if(auth()->id() === $file->user_id || Gate::allows('admin') || (Gate::allows('team_lead') && auth()->user()->leadingTeams->pluck('id')->contains($project->team)))
                                <button @click="deleteFile({{ $file->id }}, '{{ $file->file_name }}')"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">No files uploaded yet</p>
                    <p class="text-sm text-gray-400 mt-1">Upload your first file to get started</p>
                </div>
            @endforelse
        </div>

        @if($files->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $files->links() }}
            </div>
        @endif
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreview"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="showPreview = false">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-75" @click="showPreview = false"></div>

            <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900" x-text="previewFileName"></h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    <template x-if="previewFileType === 'pdf'">
                        <iframe :src="previewFileUrl" class="w-full h-[600px] border-0"></iframe>
                    </template>
                    <template x-if="['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(previewFileType)">
                        <img :src="previewFileUrl" :alt="previewFileName" class="max-w-full h-auto mx-auto">
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function fileManager() {
    return {
        selectedFiles: [],
        description: '',
        uploading: false,
        filterType: '',
        showPreview: false,
        previewFileName: '',
        previewFileUrl: '',
        previewFileType: '',

        handleFileSelect(event) {
            this.selectedFiles = Array.from(event.target.files);
        },

        formatFileSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let i = 0;
            while (bytes >= 1024 && i < units.length - 1) {
                bytes /= 1024;
                i++;
            }
            return bytes.toFixed(2) + ' ' + units[i];
        },

        async uploadFiles(event) {
            if (this.selectedFiles.length === 0) return;

            this.uploading = true;
            const formData = new FormData(event.target);

            try {
                const response = await fetch('{{ route("projects.files.store", $project) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Upload failed: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                alert('Upload failed: ' + error.message);
            } finally {
                this.uploading = false;
            }
        },

        filterFiles() {
            // Reload with filter
            window.location.href = '{{ route("projects.files.index", $project) }}?type=' + this.filterType;
        },

        previewFile(id, name, url, type) {
            this.previewFileName = name;
            this.previewFileUrl = url;
            this.previewFileType = type;
            this.showPreview = true;
        },

        async deleteFile(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}"?`)) return;

            try {
                const response = await fetch(`/files/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Delete failed: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                alert('Delete failed: ' + error.message);
            }
        }
    }
}
</script>
@endpush
@endsection
