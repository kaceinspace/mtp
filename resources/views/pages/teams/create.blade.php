@extends('layouts.dashboard')

@section('title', 'Create Team')
@section('page-title', 'Create New Team')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Team</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fill in the details to create a new team</p>
        </div>

        <form action="{{ route('teams.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Team Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Team Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Enter team name">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('description') border-red-500 @enderror"
                    placeholder="Describe the team's purpose and responsibilities">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Team Lead -->
                <div>
                    <label for="team_lead_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Team Lead
                    </label>
                    <select id="team_lead_id" name="team_lead_id"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('team_lead_id') border-red-500 @enderror">
                        <option value="">Select team lead</option>
                        @foreach($teamLeads as $lead)
                            <option value="{{ $lead->id }}" {{ old('team_lead_id') == $lead->id ? 'selected' : '' }}>
                                {{ $lead->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team_lead_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Department
                    </label>
                    <input type="text" id="department" name="department" value="{{ old('department') }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('department') border-red-500 @enderror"
                        placeholder="e.g., IT Department">
                    @error('department')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Team Members -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Team Members
                </label>
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 p-4 max-h-64 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($users as $user)
                        <label class="flex items-center p-3 rounded-lg hover:bg-white dark:hover:bg-gray-800 cursor-pointer transition">
                            <input type="checkbox" name="members[]" value="{{ $user->id }}"
                                {{ in_array($user->id, old('members', [])) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-400 dark:bg-gray-700 dark:border-gray-600">
                            <div class="ml-3 flex-1">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $user->user_type)) }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @error('members')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('teams.index') }}"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-medium rounded-lg transition">
                    Create Team
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
