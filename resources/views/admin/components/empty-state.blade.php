@props([
'title' => 'No data found',
'message' => 'There are no records to display right now.',
])

<div class="admin-surface rounded-xl p-10 text-center">
    <h3 class="text-base font-semibold">{{ $title }}</h3>
    <p class="mt-2 text-sm" style="color: var(--admin-muted);">{{ $message }}</p>
</div>