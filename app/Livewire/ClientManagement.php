<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use App\Models\Appointment;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layout.app')]
#[Title('Clientes')]
class ClientManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = '';
    public $sort = 'created_at';
    public $dir = 'desc';

    public $clientId;
    public $name;
    public $email;
    public $phone;
    public $notes;
    public $showModal = false;
    public $isEditing = false;
    public $clientToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => ''],
        'sort' => ['except' => 'created_at'],
        'dir' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['clientId', 'name', 'email', 'phone', 'notes', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $client = Client::find($id);
        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->notes = $client->notes;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $client = $this->isEditing ? Client::find($this->clientId) : new Client();
        $client->name = $this->name;
        $client->email = $this->email;
        $client->phone = $this->phone;
        $client->notes = $this->notes;
        $client->save();

        $this->showModal = false;
        $this->dispatch('toast', message: $this->isEditing ? 'Cliente actualizado' : 'Cliente registrado');
    }

    public function confirmDelete($id)
    {
        $this->clientToDelete = $id;
    }

    public function delete()
    {
        if ($this->clientToDelete) {
            Client::find($this->clientToDelete)->delete();
            $this->clientToDelete = null;
            $this->dispatch('toast', message: 'Cliente eliminado');
        }
    }

    public function render()
    {
        $now = Carbon::now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        $totalClients = Client::count();
        $newThisMonth = Client::where('created_at', '>=', $thisMonth)->count();
        $newLastMonth = Client::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $clientsGrowth = $newLastMonth > 0 ? round((($newThisMonth - $newLastMonth) / $newLastMonth) * 100, 1) : ($newThisMonth > 0 ? 100 : 0);

        $cutsThisMonth = Appointment::where('date', '>=', $thisMonth)->count();
        $cutsLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])->count();
        $cutsGrowth = $cutsLastMonth > 0 ? round((($cutsThisMonth - $cutsLastMonth) / $cutsLastMonth) * 100, 1) : ($cutsThisMonth > 0 ? 100 : 0);

        $avgCutsPerClient = $totalClients > 0 ? round(Appointment::count() / $totalClients, 1) : 0;
        $activeThisMonth = Appointment::where('date', '>=', $thisMonth)->distinct('client_id')->count('client_id');
        $activeLastMonth = Appointment::whereBetween('date', [$lastMonth, $lastMonthEnd])->distinct('client_id')->count('client_id');
        $activeGrowth = $activeLastMonth > 0 ? round((($activeThisMonth - $activeLastMonth) / $activeLastMonth) * 100, 1) : ($activeThisMonth > 0 ? 100 : 0);

        $query = Client::withCount('appointments')->with(['appointments' => fn($q) => $q->orderBy('date', 'desc')->limit(1)]);

        if ($this->search) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%")->orWhere('phone', 'like', "%{$this->search}%"));
        }

        if ($this->filter === 'active') {
            $query->whereHas('appointments', fn($q) => $q->where('date', '>=', $thisMonth));
        } elseif ($this->filter === 'inactive') {
            $query->whereDoesntHave('appointments', fn($q) => $q->where('date', '>=', $thisMonth));
        } elseif ($this->filter === 'new') {
            $query->where('created_at', '>=', $thisMonth);
        }

        $query->orderBy($this->sort, $this->dir);

        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i)->locale('es');
            $chartData[] = [
                'label' => ucfirst($month->isoFormat('MMM')),
                'cuts' => Appointment::whereYear('date', $month->year)->whereMonth('date', $month->month)->count(),
                'clients' => Client::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
            ];
        }

        return view('livewire.client-management', [
            'clients' => $query->paginate(12),
            'totalClients' => $totalClients,
            'newThisMonth' => $newThisMonth,
            'newLastMonth' => $newLastMonth,
            'clientsGrowth' => $clientsGrowth,
            'cutsThisMonth' => $cutsThisMonth,
            'cutsLastMonth' => $cutsLastMonth,
            'cutsGrowth' => $cutsGrowth,
            'avgCutsPerClient' => $avgCutsPerClient,
            'activeThisMonth' => $activeThisMonth,
            'activeLastMonth' => $activeLastMonth,
            'activeGrowth' => $activeGrowth,
            'chartData' => $chartData,
        ]);
    }
}
