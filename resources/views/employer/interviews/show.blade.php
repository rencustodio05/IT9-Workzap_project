@extends('layouts.employer')

@section('title', 'Reschedule Interview')
@section('subtitle', 'Update interview schedule and status.')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-5">
            <h1 class="text-2xl font-bold text-gray-900">Reschedule Interview</h1>
            <p class="text-sm text-gray-500 mt-1">Update interview date, time, status, and notes.</p>
        </div>

        @if($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <div class="mb-5 p-4 rounded border bg-gray-50">
            <div class="text-sm font-semibold text-gray-900">{{ optional(optional($interview->application)->job)->title ?? 'N/A' }}</div>
            <div class="text-sm text-gray-700 mt-1">
                {{ trim((optional(optional($interview->application)->jobseeker)->first_name ?? '') . ' ' . (optional(optional($interview->application)->jobseeker)->last_name ?? '')) ?: 'N/A' }}
            </div>
            <div class="text-xs text-gray-500">{{ optional(optional($interview->application)->jobseeker)->email ?? 'N/A' }}</div>
        </div>

        <form method="POST" action="{{ route('interviews.update', $interview->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Interview Date</label>
                <input
                    type="date"
                    name="interview_date"
                    value="{{ old('interview_date', $interview->interview_date ? \Illuminate\Support\Carbon::parse($interview->interview_date)->format('Y-m-d') : '') }}"
                    class="w-full border rounded px-3 py-2"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Interview Time</label>
                <input
                    type="time"
                    name="interview_time"
                    value="{{ old('interview_time', $interview->interview_time ? \Illuminate\Support\Carbon::parse($interview->interview_time)->format('H:i') : '') }}"
                    class="w-full border rounded px-3 py-2"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="scheduled" {{ old('status', $interview->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ old('status', $interview->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $interview->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes', $interview->notes) }}</textarea>
            </div>

            <div class="pt-3 border-t flex items-center justify-between gap-3">
                <a href="{{ route('interviews.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Back</a>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600">Save Reschedule</button>
            </div>
        </form>
    </div>
</div>
@endsection