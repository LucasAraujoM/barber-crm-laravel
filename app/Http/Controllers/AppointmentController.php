<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Staff;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController
{
    /**
     * Lista los turnos con filtros y estadísticas.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        // ── Métricas rápidas ────────────────────────────────────────────────
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('date', $now->toDateString())->count();
        $todayAppointmentsPending = Appointment::whereDate('date', $now->toDateString())->where('status', 'en_progreso')->count();
        $monthAppointments = Appointment::where('date', '>=', $startOfMonth)->count();

        // Próximos 7 días
        $upcomingWeek = Appointment::whereBetween('date', [
            $now->toDateString(),
            $now->copy()->addDays(7)->toDateString()
        ])->count();

        // ── Query principal con filtros ─────────────────────────────────────
        $query = Appointment::with(['client', 'staff', 'services']);

        // Filtro por fecha (Rango personalizado o mes actual por defecto)
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('date', '>=', $dateFrom);
        } else {
            // Por defecto mostramos desde el inicio del mes actual si no hay filtro
            // $query->whereDate('date', '>=', $startOfMonth); 
            // Mejor mostramos todo ordenado por fecha descendiente por defecto
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('date', '<=', $dateTo);
        }

        // Filtro por Barbero
        if ($staffId = $request->get('staff_id')) {
            $query->where('staff_id', $staffId);
        }

        // Filtro por estado (Pasado / Futuro)
        if ($status = $request->get('status')) {
            if ($status === 'upcoming') {
                $query->where('date', '>=', $now->toDateString());
            } elseif ($status === 'past') {
                $query->where('date', '<', $now->toDateString());
            }
        }

        // Ordenamiento por defecto: más reciente primero (incluyendo futuros cercanos)
        $query->orderBy('date', 'desc')->orderBy('start_time', 'asc');

        $appointments = $query->paginate(15)->withQueryString();
        $staffMembers = Staff::all();
        $allClients = Client::orderBy('name')->get();
        $allServices = Service::orderBy('name')->get();

        // ── Datos para el gráfico (últimos 7 días) ──────────────────────────
        $weekChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->locale('es');
            $chartQuery = Appointment::whereDate('date', $day->toDateString());

            if ($staffId) {
                $chartQuery->where('staff_id', $staffId);
            }

            $weekChartData[] = [
                'label' => ucfirst($day->isoFormat('ddd D/MM')),
                'count' => $chartQuery->count(),
            ];
        }

        return view('pages.appointments', compact(
            'appointments',
            'staffMembers',
            'allClients',
            'allServices',
            'totalAppointments',
            'todayAppointments',
            'todayAppointmentsPending',
            'monthAppointments',
            'upcomingWeek',
            'weekChartData'
        ));
    }

    /**
     * Crea un nuevo turno en la base de datos.
     */
    public function store(Request $request)
    {
        if (!$request->filled('client_id') && !$request->filled('guest_name')) {
            $request->merge(['guest_name' => 'anonimo']);
        }

        $validated = $request->validate([
            'client_id' => 'required_without:guest_name|nullable|exists:clients,id',
            'guest_name' => 'required_without:client_id|nullable|string|max:255',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'notes' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $data = $request->all();
        $date = $data['date'];

        if (isset($data['start_time']) && strlen($data['start_time']) <= 8) {
            $data['start_time'] = $date . ' ' . $data['start_time'];
        }

        if (empty($data['end_time'])) {
            $data['end_time'] = Carbon::parse($data['start_time'])->addHour()->format('Y-m-d H:i:s');
        } elseif (strlen($data['end_time']) <= 8) {
            $data['end_time'] = $date . ' ' . $data['end_time'];
        }

        $appointment = Appointment::create($data);

        if ($request->has('services')) {
            $appointment->services()->sync($request->services);
        }

        return redirect()->back()->with('success', 'Turno creado correctamente.');
    }

    /**
     * Actualiza un turno existente o su estado.
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $data = $request->all();

        // Si solo se envía el status (actualización rápida desde la lista)
        if ($request->has('status') && count($data) <= 3) {
            $appointment->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Estado actualizado.');
        }
        if (!$request->filled('client_id') && !$request->filled('guest_name')) {
            $request->merge(['guest_name' => 'anonimo']);
        }

        $validated = $request->validate([
            'client_id' => 'required_without:guest_name|nullable|exists:clients,id',
            'guest_name' => 'required_without:client_id|nullable|string|max:255',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'status' => 'nullable|string|in:pendiente,en_progreso,completado,cancelado',
            'notes' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $date = $data['date'] ?? Carbon::parse($appointment->date)->toDateString();

        // Solo concatenamos la fecha si el input es solo una hora (HH:mm)
        if (isset($data['start_time']) && strlen($data['start_time']) <= 8) {
            $data['start_time'] = $date . ' ' . $data['start_time'];
        }

        if (empty($data['end_time']) && isset($data['start_time'])) {
            $data['end_time'] = Carbon::parse($data['start_time'])->addHour()->format('Y-m-d H:i:s');
        } elseif (!empty($data['end_time']) && strlen($data['end_time']) <= 8) {
            $data['end_time'] = $date . ' ' . $data['end_time'];
        }

        // Si se elige un cliente, limpiamos el nombre de invitado
        if (!empty($data['client_id'])) {
            $data['guest_name'] = null;
        }

        $appointment->update($data);

        if ($request->has('services')) {
            $appointment->services()->sync($request->services);
        } else {
            $appointment->services()->detach();
        }

        return redirect()->back()->with('success', 'Turno actualizado correctamente.');
    }

    /**
     * Verifica la disponibilidad de los barberos para un horario dado.
     * Retorna un JSON con el estado de cada barbero.
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->get('date');
        $start = $request->get('start_time');
        $end = $request->get('end_time');
        $excludeId = $request->get('exclude_id');

        if (!$date || !$start) {
            return response()->json([]);
        }

        try {
            $startTimeStr = $date . ' ' . $start;
            $endTimeStr = $end ? ($date . ' ' . $end) : Carbon::parse($startTimeStr)->addHour()->toDateTimeString();

            $staff = Staff::all()->map(function ($st) use ($startTimeStr, $endTimeStr, $excludeId, $date) {
                // Check for overlap
                $conflict = Appointment::where('staff_id', $st->id)
                    ->where('status', '!=', 'cancelado')
                    ->where(function ($q) use ($startTimeStr, $endTimeStr) {
                        $q->where(function ($q2) use ($startTimeStr, $endTimeStr) {
                            $q2->where('start_time', '>=', $startTimeStr)
                                ->where('start_time', '<', $endTimeStr);
                        })->orWhere(function ($q2) use ($startTimeStr, $endTimeStr) {
                            $q2->where('end_time', '>', $startTimeStr)
                                ->where('end_time', '<=', $endTimeStr);
                        })->orWhere(function ($q2) use ($startTimeStr, $endTimeStr) {
                            $q2->where('start_time', '<=', $startTimeStr)
                                ->where('end_time', '>=', $endTimeStr);
                        });
                    })
                    ->when($excludeId, function ($q) use ($excludeId) {
                        $q->where('id', '!=', $excludeId);
                    })
                    ->first();

                // Find the next appointment after the selected range to see when they'll be busy again
                // OR if they are currently busy, when will they be free.
                $infoNext = "";
                if ($conflict) {
                    $infoNext = "Disponible desde las " . Carbon::parse($conflict->end_time)->format('H:i');
                } else {
                    $nextAppt = Appointment::where('staff_id', $st->id)
                        ->where('start_time', '>=', $endTimeStr)
                        ->whereDate('date', $date)
                        ->where('status', '!=', 'cancelado')
                        ->orderBy('start_time', 'asc')
                        ->first();

                    if ($nextAppt) {
                        $infoNext = "Libre hasta las " . Carbon::parse($nextAppt->start_time)->format('H:i');
                    } else {
                        $infoNext = "Libre el resto del día";
                    }
                }

                return [
                    'id' => $st->id,
                    'name' => $st->name,
                    'available' => !$conflict,
                    'status_text' => $infoNext
                ];
            });

            return response()->json($staff);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Elimina un turno.
     */
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return redirect()->route('appointments.index')->with('success', 'Turno eliminado correctamente.');
    }
}
