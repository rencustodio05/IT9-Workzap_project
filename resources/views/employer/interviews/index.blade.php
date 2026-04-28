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
                            <a href="{{ route('employer.applications.show', $interview->application->id) }}" title="View" aria-label="View" class="inline-flex items-center justify-center px-3 py-1.5 rounded bg-blue-50 text-blue-700 text-xs font-medium hover:bg-blue-100 transition">
                                View
                            </a>
                            @endif
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
                            <a href="{{ route('employer.applications.show', $interview->application->id) }}" title="View" aria-label="View" class="inline-flex items-center justify-center px-3 py-1.5 rounded bg-blue-50 text-blue-700 text-xs font-medium hover:bg-blue-100 transition">
                                View
                            </a>
                            @endif
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