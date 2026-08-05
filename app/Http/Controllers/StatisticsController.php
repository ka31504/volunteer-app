<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Donation;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        $data = $this->getStatisticsData($year);

        return view('statistics.index', [
            'year' => $year,
            'availableYears' => $this->getAvailableYears(),

            // Stat cards
            'totalProjects' => $data['totalProjects'],
            'activeProjects' => $data['activeProjects'],
            'completedProjects' => $data['completedProjects'],
            'totalDonationMoney' => $data['totalDonationMoney'],
            'totalParticipants' => $data['totalParticipants'],
            'totalHours' => $data['totalHours'],

            // Biểu đồ 1: đóng góp theo tháng — mảng 12 số, khớp thứ tự T1..T12 trong JS
            'monthlyDonations' => $data['monthlyDonations'],

            // Biểu đồ 2: tỷ lệ loại đóng góp
            'moneyCount' => $data['moneyCount'],
            'goodsCount' => $data['goodsCount'],

            // Biểu đồ 3: TNV theo dự án — collection với 'name', 'count'
            'participantsByProject' => collect($data['participantsByProject']),

            // Tiến độ gây quỹ — collection/array với 'name', 'progress'
            'projectProgress' => collect($data['projectProgress']),
        ]);
    }

    /**
     * Dùng chung cho view (index) và xuất PDF (exportPdf).
     * Chỉ cache PHP primitives (array of scalar), không cache
     * Eloquent Collection/Model.
     */
    private function getStatisticsData(int $year): array
    {
        return Cache::remember("statistics.cards.{$year}", 300, function () use ($year) {

            $totalProjects = Project::count();
            $activeProjects = Project::where('status', 'active')->count();
            $completedProjects = Project::where('status', 'completed')->count();

            $totalDonationMoney = (float) Donation::where('type', 'money')
                ->whereYear('donated_at', $year)
                ->sum('amount');

            $totalParticipants = Participant::count();
            $totalHours = (float) Participant::sum('hours_contributed');

            // Biểu đồ 1: mảng 12 số theo tháng (JS dùng thẳng làm data, label T1..T12 hardcode)
            $monthlyRaw = Donation::where('type', 'money')
                ->whereYear('donated_at', $year)
                ->select(
                    DB::raw('MONTH(donated_at) as month'),
                    DB::raw('SUM(amount) as total')
                )
                ->groupBy(DB::raw('MONTH(donated_at)'))
                ->orderBy('month')
                ->get();

            $monthlyDonations = [];
            for ($m = 1; $m <= 12; $m++) {
                $found = $monthlyRaw->firstWhere('month', $m);
                $monthlyDonations[] = $found ? (float) $found->total : 0;
            }

            // Biểu đồ 2: đếm số lượt theo loại đóng góp
            $typeRaw = Donation::whereYear('donated_at', $year)
                ->select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->get();

            $moneyCount = (int) (optional($typeRaw->firstWhere('type', 'money'))->count ?? 0);
            $goodsCount = (int) (optional($typeRaw->firstWhere('type', 'goods'))->count ?? 0);

            // Biểu đồ 3: top 8 dự án theo số tình nguyện viên
            $participantsByProjectRaw = Project::withCount('participants')
                ->orderByDesc('participants_count')
                ->take(8)
                ->get(['id', 'name']);

            $participantsByProject = $participantsByProjectRaw->map(function ($project) {
                return [
                    'name' => $project->name,
                    'count' => $project->participants_count,
                ];
            })->toArray();

            // Tiến độ gây quỹ theo dự án — tính percentage ở đây, tránh N+1 trong view
            $progressRaw = Project::select('id', 'name', 'target_amount', 'current_amount')
                ->orderByDesc('current_amount')
                ->get();

            $projectProgress = $progressRaw->map(function ($project) {
                $target = (float) $project->target_amount;
                $current = (float) $project->current_amount;
                $progress = $target > 0 ? round(($current / $target) * 100, 1) : 0;

                return [
                    'name' => $project->name,
                    'progress' => $progress,
                ];
            })->toArray();

            return [
                'totalProjects' => $totalProjects,
                'activeProjects' => $activeProjects,
                'completedProjects' => $completedProjects,
                'totalDonationMoney' => $totalDonationMoney,
                'totalParticipants' => $totalParticipants,
                'totalHours' => $totalHours,
                'monthlyDonations' => $monthlyDonations,
                'moneyCount' => $moneyCount,
                'goodsCount' => $goodsCount,
                'participantsByProject' => $participantsByProject,
                'projectProgress' => $projectProgress,
            ];
        });
    }

    /**
     * Danh sách các năm có dữ liệu, phục vụ dropdown filter năm.
     */
    private function getAvailableYears(): array
    {
        $years = Donation::selectRaw('DISTINCT YEAR(donated_at) as year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (empty($years)) {
            $years = [now()->year];
        }

        return $years;
    }

    /**
     * Xuất báo cáo thống kê ra PDF (barryvdh/laravel-dompdf).
     */
    public function exportPdf(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        $data = $this->getStatisticsData($year);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('statistics.pdf', [
            'data' => $data,
            'year' => $year,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download("bao-cao-thong-ke-{$year}.pdf");
    }
}