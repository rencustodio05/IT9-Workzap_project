@extends('layouts.employer')

@section('title', 'Interview Scheduling')
@section('subtitle', 'Schedule and manage candidate interviews.')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header + Schedule Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Interview scheduling</h1>
            <p class="text-gray-500 mt-1">Manage all scheduled interviews</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Schedule Form Column -->
        <div>
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <button class="mr-2 px-4 py-1 bg-blue-700 text-white rounded font-medium" disabled>Schedule interview</button>
                </div>

                @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('interviews.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Applicant / Job</label>
                        <select name="application_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select application</option>
                            @foreach(($applications ?? collect()) as $application)
                            <option value="{{ $application->id }}" {{ old('application_id') == $application->id ? 'selected' : '' }}>
                                {{ $application->jobseeker->name ?? 'Applicant' }} - {{ $application->job->title ?? 'Job' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Interview Date</label>
                        <input type="date" name="interview_date" value="{{ old('interview_date') }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Interview Time</label>
                        <input type="time" name="interview_time" value="{{ old('interview_time') }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                        <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2" placeholder="Add interview notes...">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">
                        + Schedule interview
                    </button>
                </form>
            </div>
        </div>
        <!-- Schedule Cards Column -->
        <div>
            <!-- Today's Interviews -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="text-gray-800 font-bold mb-2">Today - {{ now()->format('F d') }}</div>
                <div class="space-y-3">
                    @forelse($todayInterviews as $interview)
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-gray-900 truncate">{{ optional(optional($interview->application)->job)->title ?? 'N/A' }}</div>
                                <div class="text-gray-700 text-sm mt-1">{{ trim((optional(optional($interview->application)->jobseeker)->first_name ?? '') . ' ' . (optional(optional($interview->application)->jobseeker)->last_name ?? '')) ?: 'N/A' }}</div>
                                <div class="text-gray-500 text-xs mt-1">{{ optional(optional($interview->application)->jobseeker)->email ?? 'N/A' }}</div>
                                <div class="text-gray-400 text-xs mt-1">{{ $interview->interview_date ? \Illuminate\Support\Carbon::parse($interview->interview_date)->format('M d') : '' }} · {{ $interview->interview_time ? \Illuminate\Support\Carbon::parse($interview->interview_time)->format('g:i A') : '' }}</div>
                                <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold {{ $interview->status === 'completed' ? 'bg-green-100 text-green-800' : ($interview->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($interview->status ?? 'scheduled') }}</span>
                            </div>

                            <div class="flex justify-end gap-2 flex-wrap">
                                @if(optional($interview->application)->id)
                                <a href="{{ route('applications.show', $interview->application->id) }}" class="px-3 py-1.5 rounded bg-blue-600 text-white text-xs font-medium hover:bg-blue-700">
                                    View
                                </a>
                                @endif

                                <a href="{{ route('interviews.show', $interview->id) }}" class="px-3 py-1.5 rounded bg-amber-500 text-white text-xs font-medium hover:bg-amber-600">
                                    Reschedule
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
                        <div class="text-gray-500 text-sm">No interviews scheduled for today.</div>
                    </div>
                    @endforelse
                </div>
            </div>
            <!-- Upcoming Interviews -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-800 font-bold mb-2">Upcoming</div>
                <div class="space-y-3">
                    @forelse($upcomingInterviews as $interview)
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-gray-900 truncate">{{ optional(optional($interview->application)->job)->title ?? 'N/A' }}</div>
                                <div class="text-gray-700 text-sm mt-1">{{ trim((optional(optional($interview->application)->jobseeker)->first_name ?? '') . ' ' . (optional(optional($interview->application)->jobseeker)->last_name ?? '')) ?: 'N/A' }}</div>
                                <div class="text-gray-500 text-xs mt-1">{{ optional(optional($interview->application)->jobseeker)->email ?? 'N/A' }}</div>
                                <div class="text-gray-400 text-xs mt-1">{{ $interview->interview_date ? \Illuminate\Support\Carbon::parse($interview->interview_date)->format('M d') : '' }} · {{ $interview->interview_time ? \Illuminate\Support\Carbon::parse($interview->interview_time)->format('g:i A') : '' }}</div>
                                <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold {{ $interview->status === 'completed' ? 'bg-green-100 text-green-800' : ($interview->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($interview->status ?? 'scheduled') }}</span>
                            </div>

                            <div class="flex justify-end gap-2 flex-wrap">
                                @if(optional($interview->application)->id)
                                <a href="{{ route('applications.show', $interview->application->id) }}" class="px-3 py-1.5 rounded bg-blue-600 text-white text-xs font-medium hover:bg-blue-700">
                                    View
                                </a>
                                @endif

                                <a href="{{ route('interviews.show', $interview->id) }}" class="px-3 py-1.5 rounded bg-amber-500 text-white text-xs font-medium hover:bg-amber-600">
                                    Reschedule
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
                        <div class="text-gray-500 text-sm">No upcoming interviews.</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection