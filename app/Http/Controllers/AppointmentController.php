<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        // ── Métricas rápidas ────────────────────────────────────────────────
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('date', $now->toDateString())->count();
        $monthAppointments = Appointment::where('date', '>=', $startOfMonth)->count();

        // Próximos 7 días
        $upcomingWeek = Appointment::whereBetween('date', [
            $now->toDateString(),
            $now->copy()->addDays(7)->toDateString()
        ])->count();

        // ── Query principal con filtros ─────────────────────────────────────
        $query = Appointment::with(['client', 'staff']);

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
        $staffMembers = Staff::all(); // Para el filtro

        return view('pages.appointments', compact(
            'appointments',
            'staffMembers',
            'totalAppointments',
            'todayAppointments',
            'monthAppointments',
            'upcomingWeek'
        ));
    }

    public function store(Request $request)
    {
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

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $data = $request->all();

        // Si solo se envía el status (actualización rápida desde la lista)
        if ($request->has('status') && count($data) <= 3) {
            $appointment->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Estado actualizado.');
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

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return redirect()->route('appointments.index')->with('success', 'Turno eliminado correctamente.');
    }
}
