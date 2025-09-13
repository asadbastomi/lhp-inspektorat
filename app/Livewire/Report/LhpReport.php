<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\Temuan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LhpReport extends Component
{
    public $startDate;
    public $endDate;
    public $month;
    public $year;
    public $jenisPengawasan;
    public $statusPenyelesaian;
    public $selectedIrban = '';
    public $irbans = [];

    public function mount()
    {
        if (Auth::user()->role === 'admin') {
            $this->irbans = User::where('role', 'irban')->orderBy('name')->get();
        }
    }

    public function generateReport()
    {
        $filters = [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'month' => $this->month,
            'year' => $this->year,
            'jenisPengawasan' => $this->jenisPengawasan,
            'status' => $this->statusPenyelesaian,
            'irban' => $this->selectedIrban,
        ];

        $queryParams = http_build_query(array_filter($filters));

        // Redirect to the export controller with the correct route
        return redirect()->to(route('reports.lhp.export') . '?' . $queryParams);
    }

    public function render()
    {
        return view('livewire.report.lhp-report')
            ->layout('components.layouts.app', ['title' => 'Generate Laporan LHP']);
    }
}
