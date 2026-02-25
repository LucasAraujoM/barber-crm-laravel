<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index()
    {
        $now = \Carbon\Carbon::now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // ── Métricas generales ──────────────────────────────────────────────
        $totalStaff = Staff::count();

        // Cortes este mes vs mes anterior
        $cutsThisMonth = Appointment::where('date', '>=', $thisMonth)->count();
        $cutsLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])->count();
        $cutsGrowth = $cutsLastMonth > 0
            ? round((($cutsThisMonth - $cutsLastMonth) / $cutsLastMonth) * 100, 1)
            : ($cutsThisMonth > 0 ? 100 : 0);

        // Promedio de cortes por barbero este mes
        $avgCutsPerStaff = $totalStaff > 0
            ? round($cutsThisMonth / $totalStaff, 1)
            : 0;

        // Barbero más activo este mes
        $topStaffId = Appointment::where('date', '>=', $thisMonth)
            ->selectRaw('staff_id, count(*) as total')
            ->groupBy('staff_id')
            ->orderByDesc('total')
            ->value('staff_id');

        $topStaff = $topStaffId ? Staff::find($topStaffId) : null;

        // Barberos activos este mes (con al menos 1 turno)
        $activeThisMonth = Appointment::where('date', '>=', $thisMonth)
            ->distinct('staff_id')->count('staff_id');
        $activeLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])
            ->distinct('staff_id')->count('staff_id');
        $activeGrowth = $activeLastMonth > 0
            ? round((($activeThisMonth - $activeLastMonth) / $activeLastMonth) * 100, 1)
            : ($activeThisMonth > 0 ? 100 : 0);

        // ── Datos para el gráfico (últimos 6 meses) ─────────────────────────
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $chartData[] = [
                'label' => ucfirst($month->isoFormat('MMM')),
                'cuts' => Appointment::whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->count(),
            ];
        }

        // ── Tabla de barberos con conteos ────────────────────────────────────
        $staff = Staff::withCount([
            'appointments',
            'appointments as cuts_this_month' => fn($q) => $q->where('date', '>=', $thisMonth),
            'appointments as cuts_last_month' => fn($q) => $q->whereBetween('date', [$lastMonth, $lastMonthEnd]),
        ])->with(['appointments' => fn($q) => $q->orderBy('date', 'desc')->limit(1)])
            ->get();

        return view('pages.staff', compact(
            'staff',
            'totalStaff',
            'cutsThisMonth',
            'cutsLastMonth',
            'cutsGrowth',
            'avgCutsPerStaff',
            'activeThisMonth',
            'activeLastMonth',
            'activeGrowth',
            'topStaff',
            'chartData',
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'role' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $staff = new Staff();
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;

        if ($request->has('role')) {
            $staff->role = $request->role;
        }

        if ($request->hasFile('avatar')) {
            $destination = base_path('app-data/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $filename = uniqid() . '.' . $request->file('avatar')->extension();

            $request->file('avatar')->move($destination, $filename);
            $staff->avatar = $filename;
        }

        $staff->notes = $request->notes;
        $staff->save();
        return redirect()->route('staff')->with('success', 'Miembro agregado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'role' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $staff = Staff::find($id);
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;

        if ($request->has('role')) {
            $staff->role = $request->role;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($staff->avatar) {
                $oldPath = base_path('app-data/images/' . $staff->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $destination = base_path('app-data/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $filename = uniqid() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->move($destination, $filename);
            $staff->avatar = $filename;
        }

        $staff->notes = $request->notes;
        $staff->save();
        return redirect()->route('staff')->with('success', 'Miembro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $staff = Staff::find($id);
        if ($staff->avatar) {
            Storage::disk('public')->delete($staff->avatar);
        }
        $staff->delete();
        return redirect()->route('staff');
    }
}
