@extends('layouts.dashboard')

@section('title', __('messages.create_project'))
@section('page-title', __('messages.create_new_project'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <!-- Form Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.project_information') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.fill_details_create_project') }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('projects.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.project_title') }} <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('title') border-red-500 @enderror"
                       placeholder="{{ __('messages.enter_project_title') }}"
                       required>
                @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.description') }}
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('description') border-red-500 @enderror"
                          placeholder="{{ __('messages.describe_project_goals') }}">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Department & Team -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.department') }}
                    </label>
                    <input type="text"
                           name="department"
                           id="department"
                           value="{{ old('department') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('department') border-red-500 @enderror"
                           placeholder="{{ __('messages.department_placeholder') }}">
                    @error('department')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="team" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.team') }} @if(Gate::allows('admin'))<span class="text-red-500">*</span>@endif
                    </label>
                    <select name="team"
                            id="team"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('team') border-red-500 @enderror"
                            {{ Gate::allows('admin') ? 'required' : '' }}>
                        <option value="">-- {{ __('messages.select_team') }} --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @if(Gate::allows('team_lead'))
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('messages.team_auto_selected') }}</p>
                    @endif
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.start_date') }}
                    </label>
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           value="{{ old('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.end_date') }}
                    </label>
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           value="{{ old('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status & Priority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.status') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('status') border-red-500 @enderror"
                            required>
                        <option value="planning" {{ old('status') === 'planning' ? 'selected' : '' }}>{{ __('messages.planning') }}</option>
                        <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>{{ __('messages.ongoing') }}</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                        <option value="on-hold" {{ old('status') === 'on-hold' ? 'selected' : '' }}>{{ __('messages.on_hold') }}</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('messages.priority') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="priority"
                            id="priority"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('priority') border-red-500 @enderror"
                            required>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>{{ __('messages.low') }}</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>{{ __('messages.medium') }}</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>{{ __('messages.high') }}</option>
                        <option value="critical" {{ old('priority') === 'critical' ? 'selected' : '' }}>{{ __('messages.critical') }}</option>
                    </select>
                    @error('priority')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Team Members -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.team_members') }}
                </label>
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 max-h-64 overflow-y-auto bg-white dark:bg-gray-700">
                    @forelse($users as $user)
                    <label class="flex items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-600 rounded cursor-pointer">
                        <input type="checkbox"
                               name="members[]"
                               value="{{ $user->id }}"
                               {{ in_array($user->id, old('members', [])) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <div class="ml-3 flex items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3b82f6&color=fff"
                                 alt="{{ $user->name }}"
                                 class="w-6 h-6 rounded-full mr-2">
                            <div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ ucfirst($user->user_type) }}</span>
                            </div>
                        </div>
                    </label>
                    @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">{{ __('messages.no_users_available') }}</p>
                    @endforelse
                </div>
                @error('members')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('projects.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition">
                    {{ __('messages.create_project') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
