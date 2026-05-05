<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_applicants' => User::where('role', 'applicant')->count(),
            'total_jobs' => Job::count(),
            'total_job_posts' => Job::count(),
            'total_applications' => class_exists(Application::class) ? Application::count() : 0,
        ];

        $userSeries = $this->monthlyCountSeries(User::query());
        $jobSeries = $this->monthlyCountSeries(Job::query());

        $statusRows = class_exists(Application::class)
            ? Application::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status')->toArray()
            : [];

        return view('admin.dashboard', [
            'stats' => $stats,
            'chart' => [
                'labels' => $userSeries['labels'],
                'user_registrations' => $userSeries['values'],
                'job_posts' => $jobSeries['values'],
                'application_status_labels' => array_map(fn($status) => ucfirst((string) $status), array_keys($statusRows)),
                'application_status_values' => array_values($statusRows),
            ],
            'recentUsers' => User::latest()->take(5)->get(['id', 'first_name', 'last_name', 'email', 'role', 'created_at']),
            'recentJobs' => Job::with('employer:id,first_name,last_name')->latest()->take(5)->get(),
            'recentApplications' => class_exists(Application::class)
                ? Application::with(['job:id,title', 'user:id,first_name,last_name'])->latest()->take(5)->get()
                : collect(),
        ]);
    }

    private function monthlyCountSeries($query, int $months = 12): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $rows = $query
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format('Y-m'));

        $labels = [];
        $values = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $values[] = isset($rows[$key]) ? $rows[$key]->count() : 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
