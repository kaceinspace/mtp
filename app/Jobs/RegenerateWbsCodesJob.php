<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RegenerateWbsCodesJob implements ShouldQueue
{
    use Queueable;

    protected $projectId;

    /**
     * Create a new job instance.
     */
    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $project = Project::find($this->projectId);

        if (!$project) {
            return;
        }

        $rootTasks = Task::where('project_id', $this->projectId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        foreach ($rootTasks as $task) {
            $task->updateWbsCode();
        }
    }
}
