<?php

namespace App\Livewire;

use App\Models\Lhp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    // Filter Properties
    public $selectedIrban = 'all';
    public $selectedYear;
    public $selectedMonth = 'all';
    public $temuanFilter = 'all';

    // Data Properties
    public $years = [];
    public $irbans = [];

    // Lifecycle Hook
    public function mount()
    {
        $this->years = Lhp::select(DB::raw('YEAR(tanggal_lhp) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $this->selectedYear = $this->years->first() ?? now()->year;

        $this->irbans = User::where('role', 'irban')->orderBy('name')->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['selectedIrban', 'selectedYear', 'selectedMonth', 'temuanFilter'])) {
            $this->dispatch('updateCharts', $this->getChartData());
        }
    }

    public function render()
    {
        $baseQuery = $this->getFilteredLhps();

        $stats = [
            'totalLaporan' => (clone $baseQuery)->count(),
            'jumlahTemuan' => (clone $baseQuery)->whereNotNull('temuan')->count(),
            'totalKerugian' => (clone $baseQuery)->sum('besaran_temuan'),
            'penyelesaianSelesai' => (clone $baseQuery)->where('status_penyelesaian', 'selesai')->count(),
        ];
        $stats['persentasePenyelesaian'] = $stats['totalLaporan'] > 0 
            ? round(($stats['penyelesaianSelesai'] / $stats['totalLaporan']) * 100, 1) 
            : 0;
        
        $recentLhps = (clone $baseQuery)->with('user')->latest('tanggal_lhp')->take(5)->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'recentLhps' => $recentLhps,
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }

    private function getFilteredLhps()
    {
        return Lhp::query()
            // FIX: Automatically scope data for the 'irban' role
            ->when(Auth::user()->role === 'irban', function ($query) {
                $query->where('user_id', Auth::id());
            })
            // Admin-specific filter
            ->when(Auth::user()->role === 'admin' && $this->selectedIrban !== 'all', function ($query) {
                $query->where('user_id', $this->selectedIrban);
            })
            ->when($this->selectedYear, function ($query) {
                $query->whereYear('tanggal_lhp', $this->selectedYear);
            })
            ->when($this->selectedMonth !== 'all', function ($query) {
                $query->whereMonth('tanggal_lhp', $this->selectedMonth);
            })
            ->when($this->temuanFilter !== 'all', function ($query) {
                if ($this->temuanFilter === 'administratif') {
                    $query->where(function ($q) {
                        $q->whereNull('besaran_temuan')->orWhere('besaran_temuan', '=', 0);
                    });
                } elseif ($this->temuanFilter === 'material') {
                    $query->where('besaran_temuan', '>', 0);
                }
            });
    }

    public function getChartData()
    {
        $baseQuery = $this->getFilteredLhps();

        // 1. Temuan by Irban Chart
        $irbanQuery = User::where('role', 'irban');
        if (Auth::user()->role === 'admin' && $this->selectedIrban !== 'all') {
            $irbanQuery->where('id', $this->selectedIrban);
        } elseif (Auth::user()->role === 'irban') {
            $irbanQuery->where('id', Auth::id());
        }
        
        $temuanIrbanData = $irbanQuery->withCount(['lhps as temuan_count' => function ($query) {
            $query->whereNotNull('temuan')
                ->when($this->selectedYear, fn($q) => $q->whereYear('tanggal_lhp', $this->selectedYear))
                ->when($this->selectedMonth !== 'all', fn($q) => $q->whereMonth('tanggal_lhp', $this->selectedMonth))
                ->when($this->temuanFilter !== 'all', function ($q) {
                    if ($this->temuanFilter === 'administratif') {
                        $q->where(fn($sq) => $sq->whereNull('besaran_temuan')->orWhere('besaran_temuan', 0));
                    } elseif ($this->temuanFilter === 'material') {
                        $q->where('besaran_temuan', '>', 0);
                    }
                });
        }])->get();

        // 2. Kerugian by Month Chart
        $kerugianBulananData = (clone $baseQuery)
            ->select(DB::raw('MONTH(tanggal_lhp) as month'), DB::raw('SUM(besaran_temuan) as total_kerugian'))
            ->groupBy('month')->orderBy('month')->pluck('total_kerugian', 'month')->all();
        
        // 3. Status Penyelesaian Chart
        $statusPenyelesaianData = (clone $baseQuery)
            ->select('status_penyelesaian', DB::raw('COUNT(*) as count'))
            ->groupBy('status_penyelesaian')->pluck('count', 'status_penyelesaian')->all();

        return [
            'temuanIrban' => [
                'labels' => $temuanIrbanData->pluck('name')->toArray(),
                'data' => $temuanIrbanData->pluck('temuan_count')->toArray(),
            ],
            'kerugianBulanan' => $this->formatMonthlyData($kerugianBulananData),
            'statusPenyelesaian' => [
                'selesai' => $statusPenyelesaianData['selesai'] ?? 0,
                'dalam_proses' => $statusPenyelesaianData['dalam_proses'] ?? 0,
                'belum_diproses' => $statusPenyelesaianData['belum_diproses'] ?? 0,
            ],
        ];
    }

    private function formatMonthlyData($data)
    {
        $formatted = array_fill(1, 12, 0);
        foreach ($data as $month => $value) {
            $formatted[$month] = (float) $value;
        }
        return array_values($formatted);
    }
}
