<?php

namespace App\Http\Controllers;

use App\Models\Surat;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and recent activity.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $query = Surat::query();

        if ($user->role === 'pegawai') {
            $query->where('pegawai_id', $user->pegawai?->id);
        }

        $suratCutiCount = (clone $query)->where('jenis_surat', 'cuti')->count();
        $suratTugasCount = (clone $query)->where('jenis_surat', 'tugas')->count();
        $totalSuratCount = $query->count();

        $recentActivities = $query->latest()
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
