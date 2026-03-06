<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;

class Controller
{
    /**
     * Dashboard principal: recopila métricas, calendario y actividad reciente.
     */
    public function index()
    {
        $now = \Carbon\Carbon::now();
        $today = $now->toDateString();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // ── Métricas del dashboard ──────────────────────────────────────────
        $turnosHoy = Appointment::whereDate('date', $today)->count();
        $turnosHoyPrev = Appointment::whereDate('date', $now->copy()->subDay()->toDateString())->count();

        $turnosSemana = Appointment::whereBetween('date', [
            $now->copy()->startOfWeek()->toDateString(),
            $now->copy()->endOfWeek()->toDateString(),
        ])->count();

        $totalClients = \App\Models\Client::count();
        $newClients = \App\Models\Client::where('created_at', '>=', $thisMonth)->count();

        // ── Próximo Turno ──────────────────────────────────────────────────
        $nextAppointment = Appointment::with(['client', 'staff'])
            ->where(function ($q) use ($now) {
                $q->where('date', '>', $now->toDateString())
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where('date', '=', $now->toDateString())
                            ->where('start_time', '>', $now->format('H:i:s'));
                    });
            })
            ->whereNotIn('status', ['completado', 'cancelado'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();

        // ── Datos para el Calendario ───────────────────────────────────────
        $calendarAppointments = Appointment::with(['client', 'staff'])
            ->get()
            ->map(function ($appt) {
                $start = $appt->start_time;
                $end = $appt->end_time;

                $clientName = $appt->client_id ? ($appt->client->name ?? 'Cliente') : ($appt->guest_name ?? 'Invitado');
                $title = $clientName . ' (' . ($appt->staff->name ?? '?') . ')';

                $color = '#6366f1';
                if ($appt->status === 'completado')
                    $color = '#10b981';
                if ($appt->status === 'cancelado')
                    $color = '#ef4444';
                if ($appt->status === 'en_progreso')
                    $color = '#f59e0b';

                return [
                    'id' => $appt->id,
                    'title' => $title,
                    'start' => \Carbon\Carbon::parse($start)->toIso8601String(),
                    'end' => \Carbon\Carbon::parse($end)->toIso8601String(),
                    'status' => $appt->status ?? 'pendiente',
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'description' => $appt->notes ?? '',
                        'staff' => $appt->staff->name ?? '',
                        'client' => $clientName,
                    ]
                ];
            });

        // ── Próximos turnos del día ─────────────────────────────────────────
        $appointments = Appointment::with(['client', 'staff', 'services'])
            ->whereDate('date', $today)
            ->orderBy('start_time')
            ->get();

        // ── Últimos clientes registrados ────────────────────────────────────
        $recentClients = \App\Models\Client::latest()->limit(5)->get();

        // ── Actividad reciente (últimos 5 turnos procesados) ───────────────
        $recentAppointments = Appointment::with(['client', 'staff', 'services'])
            ->where('date', '<=', $today)
            ->whereIn('status', ['completado', 'cancelado'])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->limit(5)
            ->get();

        // Si no hay completados, tomamos los últimos 5 por fecha
        if ($recentAppointments->isEmpty()) {
            $recentAppointments = Appointment::with(['client', 'staff'])
                ->orderByDesc('date')
                ->orderByDesc('start_time')
                ->limit(5)
                ->get();
        }

        // ── Barbero más activo hoy ──────────────────────────────────────────
        $topTodayStaffId = Appointment::whereDate('date', $today)
            ->selectRaw('staff_id, count(*) as total')
            ->groupBy('staff_id')
            ->orderByDesc('total')
            ->value('staff_id');
        $topTodayStaff = $topTodayStaffId ? Staff::find($topTodayStaffId) : null;

        // ── Gráfico semanal (últimos 7 días) ────────────────────────────────
        $weekChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->locale('es');
            $weekChart[] = [
                'label' => ucfirst($day->isoFormat('ddd')),
                'count' => Appointment::whereDate('date', $day->toDateString())->count(),
            ];
        }

        $allClients = \App\Models\Client::orderBy('name')->get();
        $allStaff = Staff::orderBy('name')->get();
        $allServices = \App\Models\Service::orderBy('name')->get();

        return view('home', compact(
            'appointments',
            'turnosHoy',
            'turnosHoyPrev',
            'turnosSemana',
            'totalClients',
            'newClients',
            'nextAppointment',
            'calendarAppointments',
            'recentClients',
            'recentAppointments',
            'topTodayStaff',
            'weekChart',
            'allClients',
            'allStaff',
            'allServices',
        ));
    }

    // Methods moved to StaffController
}
