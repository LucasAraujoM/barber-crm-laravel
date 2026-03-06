<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Staff;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layout.app')]
#[Title('Turnos')]
class AppointmentManagement extends Component
{
    use WithPagination;

    // Filters
    public $date_from;
    public $date_to;
    public $staff_id;
    public $status_filter;

    // Form fields
    public $appointmentId;
    public $client_id;
    public $guest_name;
    public $selected_staff_id;
    public $date;
    public $start_time;
    public $end_time;
    public $notes;
    public $selected_services = [];
    public $status = 'pendiente';

    public $showModal = false;
    public $isEditing = false;
    public $appointmentToDelete = null;

    protected $queryString = [
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'staff_id' => ['except' => ''],
        'status_filter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function openCreate($date = null, $time = null)
    {
        $this->reset(['appointmentId', 'client_id', 'guest_name', 'selected_staff_id', 'notes', 'selected_services', 'status', 'isEditing']);
        $this->date = $date ?? date('Y-m-d');
        $this->start_time = $time ?? date('H:i');
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $appointment = Appointment::with('services')->find($id);
        $this->appointmentId = $appointment->id;
        $this->client_id = $appointment->client_id;
        $this->guest_name = $appointment->guest_name;
        $this->selected_staff_id = $appointment->staff_id;
        $this->date = Carbon::parse($appointment->date)->toDateString();
        $this->start_time = Carbon::parse($appointment->start_time)->format('H:i');
        $this->end_time = Carbon::parse($appointment->end_time)->format('H:i');
        $this->notes = $appointment->notes;
        $this->status = $appointment->status;
        $this->selected_services = $appointment->services->pluck('id')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'client_id' => 'required_without:guest_name|nullable|exists:clients,id',
            'guest_name' => 'required_without:client_id|nullable|string|max:255',
            'selected_staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'start_time' => 'required',
        ]);

        $start = $this->date . ' ' . $this->start_time;
        $end = $this->end_time ? ($this->date . ' ' . $this->end_time) : Carbon::parse($start)->addHour()->toDateTimeString();

        $appointment = $this->isEditing ? Appointment::find($this->appointmentId) : new Appointment();
        $appointment->client_id = $this->client_id;
        $appointment->guest_name = $this->client_id ? null : $this->guest_name;
        $appointment->staff_id = $this->selected_staff_id;
        $appointment->date = $this->date;
        $appointment->start_time = $start;
        $appointment->end_time = $end;
        $appointment->notes = $this->notes;
        $appointment->status = $this->status;
        $appointment->save();

        if (!empty($this->selected_services)) {
            $appointment->services()->sync($this->selected_services);
        } else {
            $appointment->services()->detach();
        }

        $this->showModal = false;
        $this->dispatch('toast', message: $this->isEditing ? 'Turno actualizado' : 'Turno creado');
        $this->dispatch('calendar-updated');
    }

    public function updateStatus($id, $newStatus)
    {
        Appointment::find($id)->update(['status' => $newStatus]);
        $this->dispatch('toast', message: 'Estado actualizado');
        $this->dispatch('calendar-updated');
    }

    public function confirmDelete($id)
    {
        $this->appointmentToDelete = $id;
    }

    public function delete()
    {
        if ($this->appointmentToDelete) {
            Appointment::find($this->appointmentToDelete)->delete();
            $this->appointmentToDelete = null;
            $this->dispatch('toast', message: 'Turno eliminado');
            $this->dispatch('calendar-updated');
        }
    }

    public function getEvents()
    {
        $appointments = Appointment::with(['client', 'staff'])->get();
        return $appointments->map(fn($a) => [
            'id' => $a->id,
            'title' => ($a->client->name ?? $a->guest_name) . " (" . ($a->staff->name ?? '?') . ")",
            'start' => $a->start_time,
            'end' => $a->end_time,
            'color' => $this->getStatusColor($a->status),
            'extendedProps' => [
                'status' => $a->status,
                'staff' => $a->staff->name ?? '—',
                'client' => $a->client->name ?? $a->guest_name,
            ]
        ]);
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'completado' => '#10b981',
            'en_progreso' => '#6366f1',
            'cancelado' => '#ef4444',
            default => '#f59e0b',
        };
    }

    public function render()
    {
        $now = Carbon::now();
        $query = Appointment::with(['client', 'staff', 'services']);

        if ($this->date_from)
            $query->whereDate('date', '>=', $this->date_from);
        if ($this->date_to)
            $query->whereDate('date', '<=', $this->date_to);
        if ($this->staff_id)
            $query->where('staff_id', $this->staff_id);

        if ($this->status_filter) {
            if ($this->status_filter === 'upcoming')
                $query->where('date', '>=', $now->toDateString());
            elseif ($this->status_filter === 'past')
                $query->where('date', '<', $now->toDateString());
        }

        $query->orderBy('date', 'desc')->orderBy('start_time', 'asc');

        return view('livewire.appointment-management', [
            'appointments' => $query->paginate(10),
            'staffMembers' => Staff::all(),
            'allClients' => Client::orderBy('name')->get(),
            'allServices' => Service::orderBy('name')->get(),
            'totalAppointments' => Appointment::count(),
            'todayAppointments' => Appointment::whereDate('date', $now->toDateString())->count(),
            'monthAppointments' => Appointment::where('date', '>=', $now->copy()->startOfMonth())->count(),
            'upcomingWeek' => Appointment::whereBetween('date', [$now->toDateString(), $now->copy()->addDays(7)->toDateString()])->count(),
            'events' => $this->getEvents(),
        ]);
    }
}
