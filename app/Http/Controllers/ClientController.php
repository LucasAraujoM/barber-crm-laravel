<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ClientController
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // ── Métricas generales ──────────────────────────────────────────────
        $totalClients = Client::count();
        $newThisMonth = Client::where('created_at', '>=', $thisMonth)->count();
        $newLastMonth = Client::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $clientsGrowth = $newLastMonth > 0
            ? round((($newThisMonth - $newLastMonth) / $newLastMonth) * 100, 1)
            : ($newThisMonth > 0 ? 100 : 0);

        // ── Cortes (appointments) ───────────────────────────────────────────
        $cutsThisMonth = Appointment::where('date', '>=', $thisMonth)->count();
        $cutsLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])->count();
        $cutsGrowth = $cutsLastMonth > 0
            ? round((($cutsThisMonth - $cutsLastMonth) / $cutsLastMonth) * 100, 1)
            : ($cutsThisMonth > 0 ? 100 : 0);

        // ── Promedio de cortes por cliente ──────────────────────────────────
        $avgCutsPerClient = $totalClients > 0
            ? round(Appointment::count() / $totalClients, 1)
            : 0;

        $avgCutsThisMonth = $newThisMonth > 0
            ? round($cutsThisMonth / max($totalClients, 1), 1)
            : 0;

        // ── Clientes activos (con al menos 1 turno este mes) ────────────────
        $activeThisMonth = Appointment::where('date', '>=', $thisMonth)
            ->distinct('client_id')
            ->count('client_id');

        $activeLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])
            ->distinct('client_id')
            ->count('client_id');

        $activeGrowth = $activeLastMonth > 0
            ? round((($activeThisMonth - $activeLastMonth) / $activeLastMonth) * 100, 1)
            : ($activeThisMonth > 0 ? 100 : 0);

        // ── Tabla de clientes con filtros ───────────────────────────────────
        $query = Client::withCount('appointments')
            ->with([
                'appointments' => function ($q) {
                    $q->orderBy('date', 'desc')->limit(1);
                }
            ]);

        // Filtro de búsqueda
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtro de actividad
        if ($filter = $request->get('filter')) {
            if ($filter === 'active') {
                $query->whereHas('appointments', function ($q) use ($thisMonth) {
                    $q->where('date', '>=', $thisMonth);
                });
            } elseif ($filter === 'inactive') {
                $query->whereDoesntHave('appointments', function ($q) use ($thisMonth) {
                    $q->where('date', '>=', $thisMonth);
                });
            } elseif ($filter === 'new') {
                $query->where('created_at', '>=', $thisMonth);
            }
        }

        // Ordenamiento
        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc');
        $allowedSorts = ['name', 'created_at', 'appointments_count'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        $query->orderBy($sort, $dir === 'asc' ? 'asc' : 'desc');

        $paginatedClients = $query->paginate(12);
        $paginatedClients->withQueryString();

        // ── Datos para el gráfico de cortes por mes (últimos 6 meses) ───────
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $chartData[] = [
                'label' => ucfirst($month->isoFormat('MMM')),
                'cuts' => Appointment::whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->count(),
                'clients' => Client::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        return view('pages.clients', array_merge(compact(
            'totalClients',
            'newThisMonth',
            'newLastMonth',
            'clientsGrowth',
            'cutsThisMonth',
            'cutsLastMonth',
            'cutsGrowth',
            'avgCutsPerClient',
            'avgCutsThisMonth',
            'activeThisMonth',
            'activeLastMonth',
            'activeGrowth',
            'chartData',
        ), ['clients' => $paginatedClients]));
    }
}
