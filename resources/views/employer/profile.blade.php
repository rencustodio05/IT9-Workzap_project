@extends('layouts.employer')

@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My profile</h1>
    </div>
    <div class="bg-white rounded-lg shadow p-8 flex flex-col items-center">
        <div class="mb-6">
            <div class="rounded-full bg-gray-200 w-24 h-24 flex items-center justify-center text-4xl text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 0112 15a4 4 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <div class="text-center space-y-1 mb-4">
            <div class="text-xl font-bold text-gray-900">Jane Employer</div>
            <div class="text-gray-500">Acme Corp</div>
            <div class="text-gray-400 text-sm">jane@acme.com</div>
        </div>
        <a href="#" class="mt-4 inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">
            Edit profile
        </a>
    </div>
</div>
@endsection