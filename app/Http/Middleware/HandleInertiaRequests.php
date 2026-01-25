<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            // 🔐 Authentification
            'auth' => [
                'user' => $request->user(),
            ],

            // 🔔 Notifications In-App
            'notifications' => fn () => $request->user()
                ? $request->user()->unreadNotifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'data' => $notification->data,
                        'created_at' => $notification->created_at->diffForHumans(),
                    ];
                })
                : [],
        ];
    }
}
