@extends('layouts.employer')

@section('title', 'Create Job Posting') a

@section('content')

<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Job Posting</h1>
    </div>

    {{-- FORM --}}
    <form action="{{ route('jobs.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- TITLE --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">Job Title</label>
            <input name="title" type="text" required
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="e.g., Sales Associate">
        </div>

        {{-- LOCATION --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">Location</label>
            <input name="location" type="text" required
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="e.g., Davao City">
        </div>

        {{-- SALARY RANGE --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">Salary Range (per month)</label>

            <div class="grid grid-cols-2 gap-4">
                <input name="salary_min" type="number" step="0.01"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Min (e.g. 1000)">

                <input name="salary_max" type="number" step="0.01"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Max (e.g. 5000)">
            </div>

            <p class="text-sm text-gray-500 mt-1">Example: 1000 - 5000 per month</p>
        </div>

        {{-- JOB TYPE --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">Job Type</label>

            <div class="flex gap-4">
                <label>
                    <input type="checkbox" name="type[]" value="full-time">
                    Full Time
                </label>

                <label>
                    <input type="checkbox" name="type[]" value="part-time">
                    Part Time
                </label>
            </div>
        </div>

        {{-- STATUS --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">Status</label>
            <select name="status"
                class="w-full border rounded-lg px-4 py-2">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        {{-- DESCRIPTION --}}
        <div>
            <label class="block mb-2 font-medium text-gray-700">Job Description</label>
            <textarea name="description" rows="5" required
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Enter job description"></textarea>
        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-between items-center pt-4">

            {{-- CANCEL --}}
            <a href="{{ route('jobs.index') }}"
                class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                Cancel
            </a>

            {{-- SUBMIT --}}
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Save Job
            </button>

        </div>

    </form>
</div>

@endsection