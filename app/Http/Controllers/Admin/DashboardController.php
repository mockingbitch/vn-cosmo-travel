<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPosts = Post::query()->count();
        $totalBanners = HeroBanner::query()->count();

        $recentPosts = Post::query()
            ->latest('id')
            ->take(6)
            ->get(['id', 'title', 'slug', 'created_at']);

        return view('admin.dashboard', [
            'title' => __('Dashboard'),
            'stats' => [
                'posts' => $totalPosts,
                'banners' => $totalBanners,
                'visitors' => 12450,
                'revenue' => 86400000,
            ],
            'recentPosts' => $recentPosts,
        ]);
    }
}

