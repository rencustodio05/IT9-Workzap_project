@extends('layouts.employer')

@section('title', 'Interview Scheduling')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header + Schedule Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Interview scheduling</h1>
            <p class="text-gray-500 mt-1">Manage all scheduled interviews</p>
        </div>
        <a href="#" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">
            + Schedule interview
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Calendar Column (Dummy Calendar) -->
        <div>
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <button class="mr-2 px-4 py-1 bg-blue-700 text-white rounded font-medium">Calendar view</button>
                    <button class="px-4 py-1 bg-gray-100 text-gray-800 rounded font-medium">List view</button>
                </div>
                <!-- Dummy Calendar layout -->
                <div class="grid grid-cols-7 gap-2 text-center text-sm text-gray-500 mb-4">
                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                    <div class="col-span-1"></div>
                    <div>1</div>
                    <div>2</div>
                    <div>3</div>
                    <div>4</div>
                    <div>5</div>
                    <div>6</div>
                    <div>7</div>
                    <div class="bg-blue-100 text-blue-700 font-bold rounded-full">9</div>
                    <div class="bg-gray-200 rounded-full">10</div>
                    <div class="bg-gray-200 rounded-full">11</div>
                    <div class="bg-gray-200 rounded-full">12</div>
                    <div class="bg-gray-200 rounded-full">13</div>
                </div>
            </div>
        </div>
        <!-- Schedule Cards Column -->
        <div>
            <!-- Today's Interviews -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="text-gray-800 font-bold mb-2">Today — March 23</div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
                        <div>
                            <div class="font-semibold">Ben Torres</div>
                            <div class="text-gray-500 text-xs">Warehouse Staff</div>
                            <div class="text-gray-400 text-xs">9:00 AM — 9:30 AM</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">Confirmed</span>
                    </div>
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
                        <div>
                            <div class="font-semibold">Maria Reyes</div>
                            <div class="text-gray-500 text-xs">Sales Associate</div>
                            <div class="text-gray-400 text-xs">2:00 PM — 2:30 PM</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">Pending</span>
                    </div>
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
                        <div>
                            <div class="font-semibold">Rico Cruz</div>
                            <div class="text-gray-500 text-xs">Driver</div>
                            <div class="text-gray-400 text-xs">4:00 PM — 4:30 PM</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">Confirmed</span>
                    </div>
                </div>
            </div>
            <!-- Upcoming Interviews -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-800 font-bold mb-2">Upcoming</div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
                        <div>
                            <div class="font-semibold">Jose Santos</div>
                            <div class="text-gray-500 text-xs">Mar 24 · 10:00 AM</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">Confirmed</span>
                    </div>
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
                        <div>
                            <div class="font-semibold">Ana Lim</div>
                            <div class="text-gray-500 text-xs">Mar 26 · 1:00 PM</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection