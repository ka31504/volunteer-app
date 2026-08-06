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
        // KHÔNG cache 2 dòng dưới qua Cache::remember (driver 'database') nữa —
        // đây là nguyên nhân gây lỗi "Attempt to read property on string":
        // cache driver 'database' serialize/unserialize Eloquent Collection
        // không ổn định, dữ liệu đọc lại có thể bị hỏng thành string.
        // Trang chủ lưu lượng thấp nên query trực tiếp không ảnh hưởng hiệu năng.
        $featuredProjects = Project::withCount('participants')
            ->latest()
            ->take(4)
            ->get();

        $recentDonations = Donation::with('project')->latest('donated_at')->take(5)->get();

        // $stats chỉ là mảng số nguyên thuần (primitives) — an toàn để cache
        // qua driver database, giữ nguyên như cũ.
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
