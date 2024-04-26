<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use App\Models\Category;

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
    public function version(Request $request): string|null
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
            'auth' => [
                'user_id' => $request->user()->id ?? '',
                'userName' => $request->user()->name ?? 'guest',
            ],
            'categories' => Category::all()->map(fn ($category) => ['id' => $category->id, 'name' => $category->name]),
            'filters' => $request->only(['search']),
            'flash' => [
                'stripeSuccess' => fn () => $request->session()->get('stripeSuccess'),
                'stripeError' => fn () => $request->session()->get('stripeError'),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
