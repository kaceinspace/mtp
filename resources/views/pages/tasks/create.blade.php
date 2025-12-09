@extends('layouts.dashboard')

@section('title', __('messages.create_task'))
@section('page-title', __('messages.create_new_task'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('messages.create_new_task') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.fill_details_create_task') }}</p>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Project Selection -->
            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.project') }} <span class="text-red-500">*</span>
                </label>
                <select id="project_id" name="project_id" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('project_id') border-red-500 @enderror">
                    <option value="">{{ __('messages.select_project') }}</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ (old('project_id') ?? $selectedProjectId ?? '') == $project->id ? 'selected' : '' }}>
                            {{ $project->title }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Task Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.task_title') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('title') border-red-500 @enderror"
                    placeholder="{{ __('messages.enter_task_title') }}">
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.description') }}
                </label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('description') border-red-500 @enderror"
                    placeholder="{{ __('messages.describe_task_details') }}">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assign To -->
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.assign_to') }}
                </label>
                <select id="assigned_to" name="assigned_to"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('assigned_to') border-red-500 @enderror">
                    <option value="">{{ __('messages.unassigned') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->user_type)) }})
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.status') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>{{ __('messages.todo') }}</option>
                        <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>{{ __('messages.in_progress') }}</option>
                        <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>{{ __('messages.review') }}</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.priority') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" name="priority" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('priority') border-red-500 @enderror">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('messages.low') }}</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>{{ __('messages.medium') }}</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('messages.high') }}</option>
                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>{{ __('messages.critical') }}</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.due_date') }}
                    </label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('tasks.index') }}"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition">
                    {{ __('messages.create_task') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
