<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_employers' => Schema::hasTable('employers')
                ? DB::table('employers')->count()
                : User::where('role', 'employer')->count(),
            'total_applicants' => User::where('role', 'applicant')->count(),
            'total_job_posts' => Schema::hasTable('jobs') ? Job::count() : 0,
            'total_applications' => Schema::hasTable('applications') ? Application::count() : 0,
        ];

        $userRegistrationSeries = $this->monthlyCountSeries(User::query());
        $jobPostSeries = Schema::hasTable('jobs')
            ? $this->monthlyCountSeries(Job::query())
            : $this->emptyMonthlySeries();

        $applicationStatusDistribution = Schema::hasTable('applications')
            ? Application::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray()
            : [];

        $recentUsers = User::query()
            ->latest()
            ->take(5)
            ->get(['id', 'first_name', 'last_name', 'email', 'role', 'created_at']);

        $recentJobs = Schema::hasTable('jobs')
            ? Job::query()
            ->with('employer:id,first_name,last_name')
            ->latest()
            ->take(5)
            ->get(['id', 'user_id', 'title', 'status', 'created_at'])
            : collect();

        $recentApplications = Schema::hasTable('applications')
            ? Application::query()
            ->with(['job:id,title', 'user:id,first_name,last_name'])
            ->latest()
            ->take(5)
            ->get(['id', 'job_id', 'user_id', 'status', 'created_at'])
            : collect();

        $statusLabelMap = [
            'pending' => 'Pending',
            'interview' => 'Interview',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'fired' => 'Fired',
        ];

        $statusLabels = [];
        foreach (array_keys($applicationStatusDistribution) as $status) {
            $statusLabels[] = $statusLabelMap[$status] ?? ucfirst($status);
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentJobs' => $recentJobs,
            'recentApplications' => $recentApplications,
            'chart' => [
                'labels' => $userRegistrationSeries['labels'],
                'user_registrations' => $userRegistrationSeries['values'],
                'job_posts' => $jobPostSeries['values'],
                'application_status_labels' => $statusLabels,
                'application_status_values' => array_values($applicationStatusDistribution),
            ],
        ]);
    }

    private function monthlyCountSeries($query, int $months = 12): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);
        $rows = $query
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m');
            });

        $labels = [];
        $values = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $values[] = isset($rows[$key]) ? $rows[$key]->count() : 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function emptyMonthlySeries(int $months = 12): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);
        $labels = [];
        $values = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $labels[] = $month->format('M Y');
            $values[] = 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
