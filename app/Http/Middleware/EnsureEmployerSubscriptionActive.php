<?php

namespace App\Http\Middleware;

use App\Services\EmployerSubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployerSubscriptionActive
{
    public function __construct(private readonly EmployerSubscriptionService $subscriptionService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'employer') {
            abort(403, 'Unauthorized');
        }

        $hasAccess = (bool) $user->subscription_active
            || $this->subscriptionService->enforceEmployerAccessState((int) $user->id);

        if (!$hasAccess) {
            return redirect()
                ->route('employer.subscription.index')
                ->with('error', 'Your subscription is inactive or expired. Please submit a subscription request and wait for admin approval.');
        }

        return $next($request);
    }
}
