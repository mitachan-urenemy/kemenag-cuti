<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and recent activity.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $currentYear = date('Y');
        $today = now();

        // 1. Stat Cards Data
        $total_pegawai = Pegawai::count();

        // Pegawai yang SEDANG cuti (cuti berlangsung hari ini)
        $pegawai_sedang_cuti = DB::table('pegawai_surat')
            ->join('surats', 'pegawai_surat.surat_id', '=', 'surats.id')
            ->where('surats.jenis_surat', 'cuti')
            ->whereDate('surats.tanggal_mulai_cuti', '<=', $today)
            ->whereDate('surats.tanggal_selesai_cuti', '>=', $today)
            ->distinct('pegawai_surat.pegawai_id')
            ->count('pegawai_surat.pegawai_id');

        // Pegawai yang pernah cuti tahun ini (unique)
        $pegawai_cuti_tahun_ini = DB::table('pegawai_surat')
            ->join('surats', 'pegawai_surat.surat_id', '=', 'surats.id')
            ->where('surats.jenis_surat', 'cuti')
            ->whereYear('surats.tanggal_surat', $currentYear)
            ->distinct('pegawai_surat.pegawai_id')
            ->count('pegawai_surat.pegawai_id');

        // Pegawai yang pernah tugas tahun ini (unique)
        $pegawai_tugas_tahun_ini = DB::table('pegawai_surat')
            ->join('surats', 'pegawai_surat.surat_id', '=', 'surats.id')
            ->where('surats.jenis_surat', 'tugas')
            ->whereYear('surats.tanggal_surat', $currentYear)
            ->distinct('pegawai_surat.pegawai_id')
            ->count('pegawai_surat.pegawai_id');

        // Total surat tahun ini
        $total_surat_tahun_ini = Surat::whereYear('tanggal_surat', $currentYear)->count();

        // 2. Chart Data - Surat per bulan
        $surat_counts = Surat::select(
                DB::raw('MONTH(tanggal_surat) as month'),
                DB::raw('jenis_surat as type'),
                DB::raw('count(*) as count')
            )
            ->whereYear('tanggal_surat', $currentYear)
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get();

        // Prepare labels (nama bulan dalam Bahasa Indonesia)
        $labels = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create()->month($m)->translatedFormat('F');
        }

        // Initialize data arrays
        $chart_data_cuti = array_fill(0, 12, 0);
        $chart_data_tugas = array_fill(0, 12, 0);

        // Fill data
        foreach ($surat_counts as $count) {
            $month_index = $count->month - 1;
            if ($count->type === 'cuti') {
                $chart_data_cuti[$month_index] = $count->count;
            } elseif ($count->type === 'tugas') {
                $chart_data_tugas[$month_index] = $count->count;
            }
        }

        $chart_data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Surat Cuti',
                    'data' => $chart_data_cuti,
                ],
                [
                    'label' => 'Surat Tugas',
                    'data' => $chart_data_tugas,
                ]
            ]
        ];

        // 3. Recent Activity Data
        $recent_surats = Surat::with('pegawais')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'total_pegawai',
            'pegawai_sedang_cuti',
            'pegawai_cuti_tahun_ini',
            'pegawai_tugas_tahun_ini',
            'total_surat_tahun_ini',
            'chart_data',
            'recent_surats'
        ));
    }
}
