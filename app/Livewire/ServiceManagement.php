<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layout.app')]
#[Title('Servicios')]
class ServiceManagement extends Component
{
    use WithPagination;

    public $serviceId;
    public $name;
    public $price;
    public $duration = 30; // Default duration in minutes
    public $showModal = false;
    public $isEditing = false;
    public $serviceToDelete = null;

    public function openCreate()
    {
        $this->reset(['serviceId', 'name', 'price', 'duration', 'isEditing']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $service = Service::find($id);
        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->price = $service->price;
        $this->duration = $service->duration ?? 30;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
        ]);

        $service = $this->isEditing ? Service::find($this->serviceId) : new Service();
        $service->name = $this->name;
        $service->price = $this->price;
        $service->duration = $this->duration;
        $service->save();

        $this->showModal = false;
        $this->dispatch('toast', message: $this->isEditing ? 'Servicio actualizado' : 'Servicio creado');
    }

    public function confirmDelete($id)
    {
        $this->serviceToDelete = $id;
    }

    public function delete()
    {
        if ($this->serviceToDelete) {
            Service::find($this->serviceToDelete)->delete();
            $this->serviceToDelete = null;
            $this->dispatch('toast', message: 'Servicio eliminado');
        }
    }

    public function render()
    {
        return view('livewire.service-management', [
            'services' => Service::paginate(10),
            'totalServices' => Service::count(),
            'averagePrice' => Service::avg('price') ?? 0,
        ]);
    }
}
