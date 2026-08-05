<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Participant;
use App\Models\Donation;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Cache::remember('welcome.featured_projects', 300, function () {
            return Project::withCount('participants')
                ->latest()
                ->take(4)
                ->get();
        });

        $recentDonations = Cache::remember('welcome.recent_donations', 300, function () {
            return Donation::with('project')->latest('donated_at')->take(5)->get();
        });

        $stats = Cache::remember('welcome.public_stats', 300, function () {
            return [
                'projects'   => Project::count(),
                'active'     => Project::where('status', 'ongoing')->count(),
                'volunteers' => Participant::count(),
                'money'      => Donation::where('type', 'money')->sum('amount'),
            ];
        });

        return view('welcome', compact('featuredProjects', 'recentDonations', 'stats'));
    }
}
