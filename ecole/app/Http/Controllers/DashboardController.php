<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $studentCount = Student::count();
        $classCount = SchoolClass::count();
        $staffCount = Staff::count();
        $pendingPayments = Payment::whereIn('status', ['pending', 'partial'])->count();
        $totalRevenue = Payment::sum('amount_paid');
        $totalOutstanding = Payment::sum('balance_due');

        $studentsThisMonth = Student::whereBetween('created_at', [$startOfMonth, $now])->count();
        $studentsLastMonth = Student::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $studentTrend = $this->calculateTrend($studentsThisMonth, $studentsLastMonth);

        $revenueThisMonth = Payment::whereBetween('created_at', [$startOfMonth, $now])->sum('amount_paid');
        $revenueLastMonth = Payment::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('amount_paid');
        $revenueTrend = $this->calculateTrend($revenueThisMonth, $revenueLastMonth);

        $kpis = [
            [
                'title' => 'Total des élèves',
                'value' => number_format($studentCount, 0, ',', ' '),
                'subtitle' => $this->formatTrendLabel($studentTrend, 'ce mois'),
                'tone' => 'blue',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a3 3 0 100-6 3 3 0 000 6zM6.75 20.25a5.25 5.25 0 0110.5 0v.75H6.75v-.75z" />',
            ],
            [
                'title' => 'Classes actives',
                'value' => number_format($classCount, 0, ',', ' '),
                'subtitle' => 'Organisation pédagogique',
                'tone' => 'purple',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75h18M3 12h18M3 17.25h18" />',
            ],
            [
                'title' => 'Paiements en attente',
                'value' => number_format($pendingPayments, 0, ',', ' '),
                'subtitle' => 'Suivi des encaissements',
                'tone' => 'red',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3h.008M4.5 19.5h15a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0019.5 7.5h-15A1.5 1.5 0 003 9v9a1.5 1.5 0 001.5 1.5z" />',
            ],
            [
                'title' => 'Encaissements',
                'value' => $this->formatCurrency($totalRevenue),
                'subtitle' => $this->formatTrendLabel($revenueTrend, 'ce mois'),
                'tone' => 'emerald',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0 0l3-3m-3 3l-3-3m8.25-6.75a6.75 6.75 0 10-13.5 0c0 2.01.89 3.81 2.31 5.04l.69.66a1.5 1.5 0 01.44 1.06V18a1.5 1.5 0 001.5 1.5h5.12a1.5 1.5 0 001.5-1.5v-1.99c0-.4.16-.78.44-1.06l.69-.66a6.72 6.72 0 002.31-5.04z" />',
            ],
        ];

        $activities = ActivityLog::latest()->take(6)->get();

        $quickActions = [
            [
                'label' => 'Nouvelle inscription',
                'route' => route('students.create'),
                'tone' => 'emerald',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />',
            ],
            [
                'label' => 'Ajouter un paiement',
                'route' => route('accounting.income.create'),
                'tone' => 'red',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15" />',
            ],
            [
                'label' => 'Gérer les classes',
                'route' => route('classes.index'),
                'tone' => 'slate',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15M4.5 12h15M4.5 17.25h15" />',
            ],
            [
                'label' => 'Transferts internes',
                'route' => route('accounting.dashboard'),
                'tone' => 'purple',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15l-3-3m0 0l3-3m-3 3h12m-3 6l3-3m0 0l-3-3m3 3H6" />',
            ],
        ];

        $trendRange = collect(range(5, 0))
            ->map(fn (int $offset) => $now->copy()->subMonths($offset)->startOfMonth());

        $trendData = $trendRange->map(function (Carbon $month) {
            $monthStart = $month->copy();
            $monthEnd = $month->copy()->endOfMonth();

            return [
                'label' => $month->translatedFormat('M'),
                'entries' => Student::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'payments' => Payment::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ];
        });

        $maxTrend = $trendData->flatMap(fn (array $data) => [$data['entries'], $data['payments']])->max() ?: 1;

        $classDistribution = DB::table('classes')
            ->leftJoin('student_classes', 'classes.id', '=', 'student_classes.class_id')
            ->select('classes.name', DB::raw('count(student_classes.student_id) as total'))
            ->groupBy('classes.id', 'classes.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $statusDistribution = Student::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $totalStudents = max($studentCount, 1);
        $classBreakdown = $classDistribution->map(function ($class) use ($totalStudents) {
            return [
                'name' => $class->name,
                'total' => (int) $class->total,
                'percentage' => round(((int) $class->total / $totalStudents) * 100, 1),
            ];
        });

        $statusBreakdown = $statusDistribution->map(function ($status) use ($totalStudents) {
            return [
                'label' => ucfirst($status->status ?? 'Autres'),
                'total' => (int) $status->total,
                'percentage' => round(((int) $status->total / $totalStudents) * 100, 1),
            ];
        });

        return view('dashboard', [
            'kpis' => $kpis,
            'activities' => $activities,
            'quickActions' => $quickActions,
            'trendData' => $trendData,
            'maxTrend' => $maxTrend,
            'totalOutstanding' => $this->formatCurrency($totalOutstanding),
            'staffCount' => number_format($staffCount, 0, ',', ' '),
            'classBreakdown' => $classBreakdown,
            'statusBreakdown' => $statusBreakdown,
        ]);
    }

    private function calculateTrend(float|int $current, float|int $previous): ?float
    {
        if ($previous <= 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function formatCurrency(float|int $amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }

    private function formatTrendLabel(?float $trend, string $suffix): string
    {
        if ($trend === null) {
            return 'Tendance en cours';
        }

        $prefix = $trend >= 0 ? '+' : '';

        return $prefix . $trend . '% ' . $suffix;
    }
}
