<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Donation;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\Sponsor;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalProjects     = Project::count();
        $activeProjects    = Project::where('status', 'ongoing')->count();
        $doneProjects      = Project::where('status', 'completed')->count();
        $upcomingProjects  = Project::where('status', 'planning')->count();

        $totalParticipants = Participant::count();
        $totalSponsors = Sponsor::count();

        $totalDonations    = Donation::count();
        $totalMoney        = Donation::where('type', 'money')->sum('amount') ?? 0;

        // Dự án gần đây
        $recentProjects = Project::withCount('participants')
            ->latest()
            ->take(5)
            ->get();

        // Đóng góp gần đây
        $recentDonations = Donation::with('project')
            ->latest('donated_at')
            ->take(6)
            ->get();

        // Tình nguyện viên mới
        $recentParticipants = Participant::with('project')
            ->latest('joined_at')
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalProjects',
            'activeProjects',
            'doneProjects',
            'upcomingProjects',
            'totalParticipants',
            'totalSponsors',
            'totalDonations',
            'totalMoney',
            'recentProjects',
            'recentDonations',
            'recentParticipants'
        ));
    }
}