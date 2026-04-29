<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Post;
use App\Models\Tour;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $recentPosts = Post::query()
            ->latest('id')
            ->take(6)
            ->get(['id', 'title', 'slug', 'created_at']);

        return view('admin.dashboard', [
            'title' => __('Dashboard'),
            'stats' => [
                'posts' => Post::query()->count(),
                'tours' => Tour::query()->count(),
                'bookings' => Booking::query()->count(),
            ],
            'recentPosts' => $recentPosts,
        ]);
    }
}
