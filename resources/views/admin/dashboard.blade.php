@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of platform health, growth, and activity from live database data.')

@section('content')
<div class="space-y-6">
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <article class="admin-surface admin-stat-card rounded-xl p-5 admin-fade-up" id="employers">
            <div class="text-sm" style="color: var(--admin-muted);">Total Users</div>
            <div class="mt-2 text-3xl font-black tracking-tight">{{ number_format($stats['total_users']) }}</div>
            <div class="mt-2 text-xs" style="color: var(--admin-muted);">All roles combined</div>
        </article>

        <article class="admin-surface admin-stat-card rounded-xl p-5 admin-fade-up">
            <div class="text-sm" style="color: var(--admin-muted);">Total Employers</div>
            <div class="mt-2 text-3xl font-black tracking-tight">{{ number_format($stats['total_employers']) }}</div>
            <div class="mt-2 text-xs" style="color: var(--admin-muted);">Users with employer role</div>
        </article>

        <article class="admin-surface admin-stat-card rounded-xl p-5 admin-fade-up">
            <div class="text-sm" style="color: var(--admin-muted);">Total Applicants</div>
            <div class="mt-2 text-3xl font-black tracking-tight">{{ number_format($stats['total_jobseekers']) }}</div>
            <div class="mt-2 text-xs" style="color: var(--admin-muted);">Users with applicant role</div>
        </article>

        <article class="admin-surface admin-stat-card rounded-xl p-5 admin-fade-up" id="jobs">
            <div class="text-sm" style="color: var(--admin-muted);">Total Job Posts</div>
            <div class="mt-2 text-3xl font-black tracking-tight">{{ number_format($stats['total_job_posts']) }}</div>
            <div class="mt-2 text-xs" style="color: var(--admin-muted);">Created by employers</div>
        </article>

        <article class="admin-surface admin-stat-card rounded-xl p-5 admin-fade-up">
            <div class="text-sm" style="color: var(--admin-muted);">Total Applications</div>
            <div class="mt-2 text-3xl font-black tracking-tight">{{ number_format($stats['total_applications']) }}</div>
            <div class="mt-2 text-xs" style="color: var(--admin-muted);">Submitted by applicants</div>
        </article>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <article class="admin-surface rounded-xl p-5 xl:col-span-2 admin-fade-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold">User Registrations Per Month</h2>
                <span class="admin-chip rounded-full px-3 py-1 text-xs font-medium">Last 12 months</span>
            </div>
            <canvas id="adminUserGrowthChart" height="120"></canvas>
        </article>

        <article class="admin-surface rounded-xl p-5 admin-fade-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold">Application Status</h2>
                <span class="admin-chip rounded-full px-3 py-1 text-xs font-medium">Distribution</span>
            </div>
            <canvas id="adminApplicationStatusChart" height="220"></canvas>
        </article>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <article class="admin-surface rounded-xl p-5 xl:col-span-2 admin-fade-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline">Manage users</a>
            </div>

            @if($recentUsers->count())
            <div class="overflow-x-auto">
                <table class="admin-table min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="py-3 pr-4">Name</th>
                            <th class="py-3 pr-4">Email</th>
                            <th class="py-3 pr-4">Role</th>
                            <th class="py-3">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td class="py-3 pr-4 font-semibold">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A' }}</td>
                            <td class="py-3 pr-4" style="color: var(--admin-muted);">{{ $user->email }}</td>
                            <td class="py-3 pr-4"><span class="admin-chip rounded-full px-2.5 py-1 text-xs">{{ ucfirst($user->role) }}</span></td>
                            <td class="py-3" style="color: var(--admin-muted);">{{ optional($user->created_at)->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-base font-semibold">No users found</div>
                <div class="mt-1 text-sm" style="color: var(--admin-muted);">Users from your database will appear here.</div>
            </div>
            @endif
        </article>

        <article class="admin-surface rounded-xl p-5 space-y-4 admin-fade-up">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold">Recent Applications</h2>
                <span class="text-xs" style="color: var(--admin-muted);">Live DB</span>
            </div>

            @forelse($recentApplications as $application)
            <div class="rounded-lg border p-3 border-blue-200 bg-blue-50">
                <div class="text-sm font-semibold">{{ optional($application->job)->title ?? 'Unknown Job' }}</div>
                <div class="mt-1 text-xs" style="color: var(--admin-muted);">
                    Applicant: {{ trim((optional($application->user)->first_name ?? '') . ' ' . (optional($application->user)->last_name ?? '')) ?: 'N/A' }}
                </div>
                <div class="mt-1 text-xs" style="color: var(--admin-muted);">
                    Status: {{ ucfirst($application->status) }}
                </div>
            </div>
            @empty
            <div class="text-sm" style="color: var(--admin-muted);">No applications yet.</div>
            @endforelse

            @if(!$recentApplications->count())
            <div class="space-y-2 pt-2">
                <div class="admin-skeleton rounded-md h-3"></div>
                <div class="admin-skeleton rounded-md h-3 w-10/12"></div>
                <div class="admin-skeleton rounded-md h-3 w-8/12"></div>
            </div>
            @endif
        </article>
    </section>

    <section class="admin-surface rounded-xl p-5 admin-fade-up" id="settings">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold">Job Posts Per Month</h2>
            <span class="admin-chip rounded-full px-3 py-1 text-xs font-medium">Last 12 months</span>
        </div>
        <canvas id="adminJobPostsChart" height="110"></canvas>
    </section>

    <section class="admin-surface rounded-xl p-5 admin-fade-up">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold">Recent Job Posts</h2>
            <span class="admin-chip rounded-full px-3 py-1 text-xs font-medium">Latest 5</span>
        </div>

        @if($recentJobs->count())
        <div class="overflow-x-auto">
            <table class="admin-table min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="py-3 pr-4">Title</th>
                        <th class="py-3 pr-4">Employer</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentJobs as $job)
                    <tr>
                        <td class="py-3 pr-4 font-semibold">{{ $job->title }}</td>
                        <td class="py-3 pr-4" style="color: var(--admin-muted);">{{ trim((optional($job->employer)->first_name ?? '') . ' ' . (optional($job->employer)->last_name ?? '')) ?: 'N/A' }}</td>
                        <td class="py-3 pr-4"><span class="admin-chip rounded-full px-2.5 py-1 text-xs">{{ ucfirst($job->status) }}</span></td>
                        <td class="py-3" style="color: var(--admin-muted);">{{ optional($job->created_at)->format('M d, Y h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm" style="color: var(--admin-muted);">No job posts found.</p>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="admin-chart-data" type="application/json">
    @json($chart)
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const createChart = (id, config) => {
            const el = document.getElementById(id);
            if (!el) return;
            new Chart(el, config);
        };

        let chartData = {};
        const chartDataEl = document.getElementById('admin-chart-data');
        if (chartDataEl) {
            try {
                chartData = JSON.parse(chartDataEl.textContent || '{}');
            } catch (e) {
                chartData = {};
            }
        }
        const chartLabels = chartData.labels || [];
        const userRegistrations = chartData.user_registrations || [];
        const jobPosts = chartData.job_posts || [];
        const applicationStatusLabels = chartData.application_status_labels || [];
        const applicationStatusValues = chartData.application_status_values || [];

        createChart('adminUserGrowthChart', {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'User Registrations',
                    data: userRegistrations,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.18)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        createChart('adminApplicationStatusChart', {
            type: 'doughnut',
            data: {
                labels: applicationStatusLabels,
                datasets: [{
                    data: applicationStatusValues,
                    backgroundColor: ['#f59e0b', '#3b82f6', '#22c55e', '#ef4444', '#64748b', '#a855f7']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        createChart('adminJobPostsChart', {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Job Posts Per Month',
                    data: jobPosts,
                    borderRadius: 8,
                    backgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush