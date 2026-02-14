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
        $suratCutiCount = Surat::where('jenis_surat', 'cuti')->count();
        $suratTugasCount = Surat::where('jenis_surat', 'tugas')->count();
        $totalSuratCount = Surat::count();

        $recentActivities = Surat::with('pegawai')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'suratCutiCount',
            'suratTugasCount',
            'totalSuratCount',
            'recentActivities'
        ));
    }
}
