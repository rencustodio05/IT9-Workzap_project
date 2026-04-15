@extends('layouts.employer')

@section('title', 'Edit Job')
@section('subtitle', 'Update job details and publishing status.')

@section('content')
<div class="max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Edit Job Posting</h1>

    <form action="{{ route('jobs.update', $job) }}"
        method="POST"
        class="bg-white p-6 rounded-lg shadow space-y-5">

        @csrf
        @method('PUT')

        {{-- JOB TITLE --}}
        <div>
            <label class="block mb-2 font-medium">Job Title</label>
            <input type="text"
                name="title"
                value="{{ old('title', $job->title) }}"
                class="w-full border px-4 py-2 rounded"
                required>
        </div>

        {{-- LOCATION --}}
        <div>
            <label class="block mb-2 font-medium">Location</label>
            <input type="text"
                name="location"
                value="{{ old('location', $job->location) }}"
                class="w-full border px-4 py-2 rounded"
                required>
        </div>

        {{-- SALARY RANGE --}}
        <div>
            <label class="block mb-2 font-medium">Salary Range</label>
            <div class="grid grid-cols-2 gap-3">
                <input type="number"
                    step="0.01"
                    name="salary_min"
                    value="{{ old('salary_min', $job->salary_min) }}"
                    class="w-full border px-4 py-2 rounded"
                    placeholder="Minimum salary">

                <input type="number"
                    step="0.01"
                    name="salary_max"
                    value="{{ old('salary_max', $job->salary_max) }}"
                    class="w-full border px-4 py-2 rounded"
                    placeholder="Maximum salary">
            </div>
        </div>

        @php
        $selectedTypes = explode(',', old('type', $job->type ?? ''));
        @endphp

        <div>
            <label class="block mb-2 font-medium">Job Type</label>
            <div class="flex gap-4">
                <label>
                    <input type="checkbox" name="type[]" value="full-time" {{ in_array('full-time', $selectedTypes) ? 'checked' : '' }}>
                    Full Time
                </label>
                <label>
                    <input type="checkbox" name="type[]" value="part-time" {{ in_array('part-time', $selectedTypes) ? 'checked' : '' }}>
                    Part Time
                </label>
            </div>
        </div>

        {{-- DESCRIPTION --}}
        <div>
            <label class="block mb-2 font-medium">Description</label>
            <textarea name="description"
                rows="5"
                class="w-full border px-4 py-2 rounded"
                required>{{ old('description', $job->description) }}</textarea>
        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3">

            <a href="{{ route('jobs.index') }}"
                class="px-4 py-2 bg-gray-500 text-white rounded">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded">
                Update Job
            </button>

        </div>

        <div>
            <label>Status</label>

            <select name="status" required>
                <option value="active" {{ isset($job) && $job->status == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="closed" {{ isset($job) && ($job->status == 'inactive' || $job->status == 'closed') ? 'selected' : '' }}>
                    Closed
                </option>
            </select>
        </div>

    </form>

</div>
@endsection