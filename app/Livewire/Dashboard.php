<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Client;
use App\Models\Service;
use Carbon\Carbon;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layout.app')]
#[Title('Panel')]
class Dashboard extends Component
{
    public $turnosHoy;
    public $turnosHoyPrev;
    public $turnosSemana;
    public $totalClients;
    public $newClients;
    public $nextAppointment;
    public $calendarAppointments;
    public $appointments;
    public $recentClients;
    public $recentAppointments;
    public $topTodayStaff;
    public $weekChart;
    public $allClients;
    public $allStaff;
    public $allServices;

    // Quick Appointment Form
    public $client_id;
    public $guest_name;
    public $selected_staff_id;
    public $date;
    public $start_time;
    public $end_time;
    public $notes;
    public $selected_services = [];
    public $status = 'pendiente';
    public $editing_id = null;

    public $showQuickModal = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $thisMonth = $now->copy()->startOfMonth();

        // Métricas
        $this->turnosHoy = Appointment::whereDate('date', $today)->count();
        $this->turnosHoyPrev = Appointment::whereDate('date', $now->copy()->subDay()->toDateString())->count();
        $this->turnosSemana = Appointment::whereBetween('date', [
            $now->copy()->startOfWeek()->toDateString(),
            $now->copy()->endOfWeek()->toDateString(),
        ])->count();
        $this->totalClients = Client::count();
        $this->newClients = Client::where('created_at', '>=', $thisMonth)->count();

        // Próximo Turno
        $this->nextAppointment = Appointment::with(['client', 'staff'])
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

        // Calendario
        $this->calendarAppointments = Appointment::with(['client', 'staff'])
            ->get()
            ->map(function ($appt) {
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
                    'start' => Carbon::parse($appt->start_time)->toIso8601String(),
                    'end' => Carbon::parse($appt->end_time)->toIso8601String(),
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

        // Agenda de Hoy
        $this->appointments = Appointment::with(['client', 'staff', 'services'])
            ->whereDate('date', $today)
            ->orderBy('start_time')
            ->get();

        // Recientes
        $this->recentClients = Client::latest()->limit(5)->get();
        $this->recentAppointments = Appointment::with(['client', 'staff', 'services'])
            ->where('date', '<=', $today)
            ->whereIn('status', ['completado', 'cancelado'])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->limit(5)
            ->get();

        if ($this->recentAppointments->isEmpty()) {
            $this->recentAppointments = Appointment::with(['client', 'staff'])
                ->orderByDesc('date')
                ->orderByDesc('start_time')
                ->limit(5)
                ->get();
        }

        // Más activo
        $topTodayStaffId = Appointment::whereDate('date', $today)
            ->selectRaw('staff_id, count(*) as total')
            ->groupBy('staff_id')
            ->orderByDesc('total')
            ->value('staff_id');
        $this->topTodayStaff = $topTodayStaffId ? Staff::find($topTodayStaffId) : null;

        // Gráfico
        $this->weekChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->locale('es');
            $this->weekChart[] = [
                'label' => ucfirst($day->isoFormat('ddd')),
                'count' => Appointment::whereDate('date', $day->toDateString())->count(),
            ];
        }

        $this->allClients = Client::orderBy('name')->get();
        $this->allStaff = Staff::orderBy('name')->get();
        $this->allServices = Service::orderBy('name')->get();
    }

    public function completeAppointment($id)
    {
        $appt = Appointment::find($id);
        if ($appt) {
            $appt->update(['status' => 'completado']);
            $this->loadData();
            $this->dispatch('toast', message: 'Turno completado correctamente');
        }
    }

    public function editAppointment($id)
    {
        $appt = Appointment::with('services')->find($id);
        if ($appt) {
            $this->editing_id = $appt->id;
            $this->client_id = $appt->client_id;
            $this->guest_name = $appt->guest_name;
            $this->selected_staff_id = $appt->staff_id;
            $this->date = $appt->date;
            $this->start_time = Carbon::parse($appt->start_time)->format('H:i');
            $this->end_time = $appt->end_time ? Carbon::parse($appt->end_time)->format('H:i') : null;
            $this->notes = $appt->notes;
            $this->status = $appt->status;
            $this->selected_services = $appt->services->pluck('id')->toArray();

            // To properly initialize Alpine state for guest vs registered if needed
            $this->dispatch('init-edit-modal', isGuest: $appt->client_id === null);

            $this->showQuickModal = true;
        }
    }

    public function saveAppointment()
    {
        $this->validate([
            'selected_staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'client_id' => 'required_without:guest_name|nullable|exists:clients,id',
            'guest_name' => 'required_without:client_id|nullable|string|max:255',
        ]);

        $start = $this->date . ' ' . $this->start_time;
        $end = $this->end_time ? ($this->date . ' ' . $this->end_time) : Carbon::parse($start)->addHour()->toDateTimeString();

        $data = [
            'client_id' => $this->client_id,
            'guest_name' => $this->client_id ? null : $this->guest_name,
            'staff_id' => $this->selected_staff_id,
            'date' => $this->date,
            'start_time' => $start,
            'end_time' => $end,
            'notes' => $this->notes,
            'status' => $this->status,
        ];

        if ($this->editing_id) {
            $appointment = Appointment::find($this->editing_id);
            if ($appointment) {
                $appointment->update($data);
                $appointment->services()->sync($this->selected_services ?? []);
            }
            $msg = 'Turno actualizado con éxito';
        } else {
            $appointment = Appointment::create($data);
            if (!empty($this->selected_services)) {
                $appointment->services()->sync($this->selected_services);
            }
            $msg = 'Turno creado con éxito';
        }

        $this->showQuickModal = false;
        $this->dispatch('close-modal');
        $this->reset(['editing_id', 'client_id', 'guest_name', 'selected_staff_id', 'date', 'start_time', 'end_time', 'notes', 'selected_services', 'status']);
        $this->loadData();
        $this->dispatch('toast', message: $msg);
        $this->dispatch('calendar-updated');
    }

    public function openCreateFromCalendar($date, $time)
    {
        $this->resetModal();
        $this->date = $date;
        $this->start_time = $time;
    }

    public function deleteAppointment($id)
    {
        $appt = Appointment::find($id);
        if ($appt) {
            $appt->services()->detach();
            $appt->delete();
            $this->showQuickModal = false;
            $this->loadData();
            $this->dispatch('toast', message: 'Turno eliminado correctamente');
            $this->dispatch('calendar-updated');
        }
    }

    public function moveAppointment($id, $newStartISO, $newEndISO = null)
    {
        $appt = Appointment::find($id);
        if ($appt) {
            $start = Carbon::parse($newStartISO)->setTimezone('America/Argentina/Buenos_Aires');
            $appt->date = $start->toDateString();
            $appt->start_time = $start->toDateTimeString();

            if ($newEndISO) {
                $end = Carbon::parse($newEndISO)->setTimezone('America/Argentina/Buenos_Aires');
                $appt->end_time = $end->toDateTimeString();
            } else {
                $appt->end_time = $start->copy()->addHour()->toDateTimeString();
            }

            $appt->save();
            $this->loadData();
            $this->dispatch('toast', message: 'Turno reprogramado');
            $this->dispatch('calendar-updated');
        }
    }

    public function resetModal()
    {
        $this->reset(['editing_id', 'client_id', 'guest_name', 'selected_staff_id', 'date', 'start_time', 'end_time', 'notes', 'selected_services', 'status']);
        $this->status = 'pendiente';
        $this->showQuickModal = true;
        $this->dispatch('open-modal');
    }

    public function getEvents()
    {
        return Appointment::with(['client', 'staff'])
            ->get()
            ->map(function ($appt) {
                $clientName = $appt->client_id ? ($appt->client->name ?? 'Cliente') : ($appt->guest_name ?? 'Invitado');
                $color = '#6366f1';
                if ($appt->status === 'completado')
                    $color = '#10b981';
                if ($appt->status === 'cancelado')
                    $color = '#ef4444';
                if ($appt->status === 'en_progreso')
                    $color = '#f59e0b';
                return [
                    'id' => $appt->id,
                    'title' => $clientName . ' (' . ($appt->staff->name ?? '?') . ')',
                    'start' => Carbon::parse($appt->start_time)->toIso8601String(),
                    'end' => Carbon::parse($appt->end_time)->toIso8601String(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'description' => $appt->notes ?? '',
                        'status' => $appt->status ?? 'pendiente',
                        'staff' => $appt->staff->name ?? '',
                        'client' => $clientName,
                    ],
                ];
            });
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
