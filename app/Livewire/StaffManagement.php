<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Staff;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layout.app')]
#[Title('Barberos')]
class StaffManagement extends Component
{
    use WithFileUploads;

    public $staffId;
    public $name;
    public $email;
    public $phone;
    public $role = 'Staff';
    public $notes;
    public $avatar;
    public $avatarPreview;

    public $showModal = false;
    public $isEditing = false;
    public $staffToDelete = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'role' => 'nullable|string',
        'notes' => 'nullable|string',
        'avatar' => 'nullable|image|max:2048',
    ];

    public function openCreate()
    {
        $this->reset(['staffId', 'name', 'email', 'phone', 'role', 'notes', 'avatar', 'avatarPreview', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $staff = Staff::find($id);
        $this->staffId = $staff->id;
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->phone = $staff->phone;
        $this->role = $staff->role ?? 'Staff';
        $this->notes = $staff->notes;
        $this->avatarPreview = $staff->avatar;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $staff = $this->isEditing ? Staff::find($this->staffId) : new Staff();
        $staff->name = $this->name;
        $staff->email = $this->email;
        $staff->phone = $this->phone;
        $staff->role = $this->role;
        $staff->notes = $this->notes;

        if ($this->avatar) {
            // Delete old avatar if editing
            if ($this->isEditing && $staff->avatar) {
                $oldPath = base_path('app-data/images/' . $staff->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $destination = base_path('app-data/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $filename = uniqid() . '.' . $this->avatar->extension();
            $this->avatar->storeAs(path: 'images', name: $filename, disk: 'app_data'); // Needs custom disk or move manually

            // Manual move as in original controller
            $this->avatar->toTemporaryFile()->move($destination, $filename);
            $staff->avatar = $filename;
        }

        $staff->save();

        $this->showModal = false;
        $this->dispatch('toast', message: $this->isEditing ? 'Barbero actualizado' : 'Barbero creado');
    }

    public function confirmDelete($id)
    {
        $this->staffToDelete = $id;
    }

    public function delete()
    {
        if ($this->staffToDelete) {
            $staff = Staff::find($this->staffToDelete);
            if ($staff->avatar) {
                $path = base_path('app-data/images/' . $staff->avatar);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $staff->delete();
            $this->staffToDelete = null;
            $this->dispatch('toast', message: 'Barbero eliminado');
        }
    }

    public function render()
    {
        $now = Carbon::now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        $totalStaff = Staff::count();
        $cutsThisMonth = Appointment::where('date', '>=', $thisMonth)->count();
        $cutsLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])->count();
        $cutsGrowth = $cutsLastMonth > 0
            ? round((($cutsThisMonth - $cutsLastMonth) / $cutsLastMonth) * 100, 1)
            : ($cutsThisMonth > 0 ? 100 : 0);

        $avgCutsPerStaff = $totalStaff > 0 ? round($cutsThisMonth / $totalStaff, 1) : 0;

        $topStaffId = Appointment::where('date', '>=', $thisMonth)
            ->selectRaw('staff_id, count(*) as total')
            ->groupBy('staff_id')
            ->orderByDesc('total')
            ->value('staff_id');
        $topStaff = $topStaffId ? Staff::find($topStaffId) : null;

        $activeThisMonth = Appointment::where('date', '>=', $thisMonth)->distinct('staff_id')->count('staff_id');
        $activeLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])->distinct('staff_id')->count('staff_id');
        $activeGrowth = $activeLastMonth > 0
            ? round((($activeThisMonth - $activeLastMonth) / $activeLastMonth) * 100, 1)
            : ($activeThisMonth > 0 ? 100 : 0);

        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i)->locale('es');
            $chartData[] = [
                'label' => ucfirst($month->isoFormat('MMM')),
                'cuts' => Appointment::whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->count(),
            ];
        }

        $staff = Staff::withCount([
            'appointments',
            'appointments as cuts_this_month' => fn($q) => $q->where('date', '>=', $thisMonth),
            'appointments as cuts_last_month' => fn($q) => $q->whereBetween('date', [$lastMonth, $lastMonthEnd]),
        ])->with(['appointments' => fn($q) => $q->orderBy('date', 'desc')->limit(1)])
            ->get();

        return view('livewire.staff-management', compact(
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
}
