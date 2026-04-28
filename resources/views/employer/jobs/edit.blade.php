@extends('layouts.employer')

@section('title', 'Edit Job')
@section('subtitle', 'Update job details and publishing status.')

@section('content')
<div class="max-w-2xl mx-auto">



    <form action="{{ route('employer.jobs.update', $job) }}"
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

        {{-- STATUS --}}
        @php
        $selectedStatus = old('status', (($job->status ?? 'active') === 'inactive' ? 'closed' : ($job->status ?? 'active')));
        @endphp
        <div class="mt-6">
            <label class="block mb-2 font-medium">Status</label>
            <div class="flex flex-wrap items-center gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="status" value="active" class="sr-only peer" {{ $selectedStatus === 'active' ? 'checked' : '' }} required>
                    <span class="inline-flex items-center px-4 py-2 rounded-full border text-sm font-semibold transition
                        peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500
                        bg-green-50 text-green-700 border-green-200 hover:bg-green-100
                        peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-green-500">
                        Active
                    </span>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="status" value="closed" class="sr-only peer" {{ $selectedStatus === 'closed' ? 'checked' : '' }} required>
                    <span class="inline-flex items-center px-4 py-2 rounded-full border text-sm font-semibold transition
                        peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500
                        bg-slate-100 text-slate-700 border-slate-300 hover:bg-slate-200
                        peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-red-500">
                        Closed
                    </span>
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
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('employer.jobs.index') }}"
                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2 admin-button-primary text-white rounded-lg hover:brightness-95 transition">
                Update Job
            </button>
        </div>

    </form>

</div>
@endsection