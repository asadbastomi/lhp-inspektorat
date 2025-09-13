<?php

namespace App\Http\Controllers;

use App\Models\Lhp;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LhpExportController extends Controller
{
    public function exportPdf(Request $request, Lhp $lhp = null)
    {
        $subtitle = ''; // Initialize subtitle

        if ($lhp) {
            $lhp->load('temuans.rekomendasis.tindakLanjuts');
            $lhps = collect([$lhp]);
            $view = 'pdf.lhp-single-export';
            $title = 'Laporan Hasil Pemeriksaan (LHP)';
            $subtitle = 'LHP: ' . $lhp->nomor_lhp;
        } else {
            // Handle bulk/filtered LHP export
            $query = Lhp::query()->with('temuans.rekomendasis.tindakLanjuts');

            $title = 'Laporan Hasil Pemeriksaan (LHP)';
            $subtitleParts = [];

            // Build period string
            $periodString = '';
            if ($request->filled('startDate') && $request->filled('endDate')) {
                $periodString = 'Periode ' . \Carbon\Carbon::parse($request->startDate)->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($request->endDate)->translatedFormat('d M Y');
            } else {
                $dateParts = [];
                if ($request->filled('month')) {
                    $dateParts[] = 'Bulan ' . \Carbon\Carbon::create()->month($request->month)->translatedFormat('F');
                }
                if ($request->filled('year')) {
                    $dateParts[] = 'Tahun ' . $request->year;
                }
                $periodString = implode(' ', $dateParts);
            }
            if ($periodString) {
                $subtitleParts[] = $periodString;
            }

            // Build other filter strings
            if ($request->filled('jenisPengawasan')) {
                $subtitleParts[] = 'Jenis: ' . $request->jenisPengawasan;
            }
            if ($request->filled('status')) {
                $statusMap = [
                    'belum_ditindaklanjuti' => 'Belum Ditindaklanjuti',
                    'dalam_proses' => 'Dalam Proses',
                    'sesuai' => 'Sesuai',
                ];
                $subtitleParts[] = 'Status: ' . ($statusMap[$request->status] ?? $request->status);
            }
            if ($request->filled('irban')) {
                $irbanName = User::find($request->irban)->name ?? 'N/A';
                $subtitleParts[] = 'Irban: ' . $irbanName;
            }

            $subtitle = implode(' | ', $subtitleParts);


            // Apply filters for bulk export
            if ($request->filled('startDate') && $request->filled('endDate')) {
                $query->whereDate('tanggal_lhp', '>=', $request->startDate);
                $query->whereDate('tanggal_lhp', '<=', $request->endDate);
            }
            if ($request->filled('month')) {
                $query->whereMonth('tanggal_lhp', $request->input('month'));
            }
            if ($request->filled('year')) {
                $query->whereYear('tanggal_lhp', $request->input('year'));
            }
            if ($request->filled('jenisPengawasan')) {
                $query->whereHas('temuans', function ($q) use ($request) {
                    $q->where('jenis_pengawasan', $request->input('jenisPengawasan'));
                });
            }

            if ($request->filled('status')) {
                $query->where('status_penyelesaian', $request->input('status'));
            }

            if ($request->filled('irban')) {
                $query->where('user_id', $request->input('irban'));
            }

            $lhps = $query->get();
            $view = 'pdf.lhp-bulk-export';
        }

        if ($lhps->isEmpty()) {
            return back()->with('error', 'Tidak ada data LHP yang ditemukan untuk kriteria yang dipilih.');
        }

        $pdf = Pdf::loadView($view, ['lhps' => $lhps, 'title' => $title, 'subtitle' => $subtitle]);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('lhp-report.pdf');
    }
}
