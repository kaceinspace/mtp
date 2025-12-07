@foreach($tasks as $task)
    @include('pages.wbs.task-item', ['task' => $task, 'level' => $level])
@endforeach
