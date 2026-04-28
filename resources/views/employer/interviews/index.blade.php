@extends('layouts.employer')

@section('title', 'Interview Scheduling')
@section('subtitle', 'Schedule and manage candidate interviews.')

@section('content')
<div class="max-w-7xl mx-auto">
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

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <section class="admin-surface rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Today</h2>
                <span class="text-xs font-medium px-2 py-1 rounded bg-blue-50 text-blue-700">{{ now()->timezone('Asia/Manila')->format('F d') }}</span>
            </div>

            <div class="space-y-3">
                @forelse($todayInterviews as $interview)
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate">{{ optional(optional($interview->application)->job)->title ?? 'N/A' }}</div>
                            <div class="text-gray-700 text-sm mt-1">{{ trim((optional(optional($interview->application)->jobseeker)->first_name ?? '') . ' ' . (optional(optional($interview->application)->jobseeker)->last_name ?? '')) ?: 'N/A' }}</div>
                            <div class="text-gray-500 text-xs mt-1">{{ optional(optional($interview->application)->jobseeker)->email ?? 'N/A' }}</div>
                            <div class="text-gray-400 text-xs mt-1">{{ $interview->interview_date ? \Illuminate\Support\Carbon::parse($interview->interview_date)->format('M d') : '' }} {{ $interview->interview_time ? \Illuminate\Support\Carbon::parse($interview->interview_time)->format('g:i A') : '' }}</div>
                            <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold {{ $interview->status === 'completed' ? 'bg-green-100 text-green-800' : ($interview->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($interview->status ?? 'scheduled') }}</span>
                        </div>

                        <div class="flex justify-end gap-2 flex-wrap">
                            @if(optional($interview->application)->id)
                            <a href="{{ route('employer.applications.show', $interview->application->id) }}" title="View" aria-label="View" class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                                    <circle cx="12" cy="12" r="2.75" />
                                </svg>
                            </a>
                            @endif

                            <a href="{{ route('employer.interviews.show', $interview->id) }}" class="px-3 py-1.5 rounded bg-amber-500 text-white text-xs font-medium hover:bg-amber-600">
                                Reschedule
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-gray-50 p-4 rounded-lg border text-gray-500 text-sm">
                    No interviews scheduled for today.
                </div>
                @endforelse
            </div>
        </section>

        <section class="admin-surface rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Upcoming</h2>
                <span class="text-xs font-medium px-2 py-1 rounded bg-gray-100 text-gray-700">Next dates</span>
            </div>

            <div class="space-y-3">
                @forelse($upcomingInterviews as $interview)
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate">{{ optional(optional($interview->application)->job)->title ?? 'N/A' }}</div>
                            <div class="text-gray-700 text-sm mt-1">{{ trim((optional(optional($interview->application)->jobseeker)->first_name ?? '') . ' ' . (optional(optional($interview->application)->jobseeker)->last_name ?? '')) ?: 'N/A' }}</div>
                            <div class="text-gray-500 text-xs mt-1">{{ optional(optional($interview->application)->jobseeker)->email ?? 'N/A' }}</div>
                            <div class="text-gray-400 text-xs mt-1">{{ $interview->interview_date ? \Illuminate\Support\Carbon::parse($interview->interview_date)->format('M d') : '' }} {{ $interview->interview_time ? \Illuminate\Support\Carbon::parse($interview->interview_time)->format('g:i A') : '' }}</div>
                            <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold {{ $interview->status === 'completed' ? 'bg-green-100 text-green-800' : ($interview->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($interview->status ?? 'scheduled') }}</span>
                        </div>

                        <div class="flex justify-end gap-2 flex-wrap">
                            @if(optional($interview->application)->id)
                            <a href="{{ route('employer.applications.show', $interview->application->id) }}" title="View" aria-label="View" class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 hover:bg-blue-50 transition">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
                                    <circle cx="12" cy="12" r="2.75" />
                                </svg>
                            </a>
                            @endif

                            <a href="{{ route('employer.interviews.show', $interview->id) }}" class="px-3 py-1.5 rounded bg-amber-500 text-white text-xs font-medium hover:bg-amber-600">
                                Reschedule
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-gray-50 p-4 rounded-lg border text-gray-500 text-sm">
                    No upcoming interviews.
                </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection