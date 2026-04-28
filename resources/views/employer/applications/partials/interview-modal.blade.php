<div x-show="openInterviewModal"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="scheduleInterviewTitle"
    @click.self="openInterviewModal = false">

    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="openInterviewModal = false"></div>

    <div x-show="openInterviewModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="relative z-10 w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl"
        @click.stop>

        @php
        $isReschedule = $application->interview !== null;
        $interviewDate = optional($application->interview)->interview_date;
        $interviewTime = optional($application->interview)->interview_time;
        $interviewNotes = optional($application->interview)->notes;
        @endphp

        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-5 py-4 text-white">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 id="scheduleInterviewTitle" class="text-lg sm:text-xl font-semibold">
                        {{ $isReschedule ? 'Reschedule Interview' : 'Schedule Interview' }}
                    </h3>
                    <p class="text-blue-100 text-sm mt-1 truncate">{{ $fullName }}</p>
                </div>

                <button type="button"
                    @click="openInterviewModal = false"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/70"
                    aria-label="Close interview modal">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6l-12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-5 sm:p-6 bg-white">
            @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-800">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->has('application_id') || $errors->has('interview_date') || $errors->has('interview_time'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ $isReschedule ? route('employer.interviews.update', $application->interview->id) : route('employer.interviews.store') }}" class="space-y-4">
                @csrf
                @if($isReschedule)
                @method('PUT')
                @else
                <input type="hidden" name="application_id" value="{{ $application->id }}">
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="interview_date" class="block text-sm font-medium text-gray-700 mb-1">Interview Date</label>
                        <input id="interview_date"
                            type="date"
                            name="interview_date"
                            min="{{ now()->format('Y-m-d') }}"
                            value="{{ old('interview_date', $interviewDate ? \Illuminate\Support\Carbon::parse($interviewDate)->format('Y-m-d') : '') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                            required>
                    </div>

                    <div>
                        <label for="interview_time" class="block text-sm font-medium text-gray-700 mb-1">Interview Time</label>
                        <input id="interview_time"
                            type="time"
                            name="interview_time"
                            value="{{ old('interview_time', $interviewTime ? \Illuminate\Support\Carbon::parse($interviewTime)->format('H:i') : '') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                            required>
                    </div>
                </div>

                <div>
                    <label for="interview_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="interview_notes"
                        name="notes"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                        placeholder="Add interview notes (optional)...">{{ old('notes', $interviewNotes) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button"
                        @click="openInterviewModal = false"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2.5 font-semibold text-white hover:from-blue-700 hover:to-blue-800 transition">
                        {{ $isReschedule ? 'Save Changes' : 'Schedule Interview' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>