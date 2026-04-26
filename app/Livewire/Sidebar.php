<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $menuItems = [
        [
            'name' => 'Panel',
            'icon' => '<path d="M3 3h7v9H3z" /><path d="M14 3h7v5h-7z" /><path d="M14 12h7v9h-7z" /><path d="M3 16h7v5H3z" />',
            'route' => 'home'
        ],
        [
            'name' => 'Turnos',
            'icon' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />',
            'route' => 'appointments.index'
        ],
        [
            'name' => 'Clientes',
            'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
            'route' => 'clients'
        ],
        [
            'name' => 'Servicios',
            'icon' => '<circle cx="6" cy="6" r="3" /><circle cx="6" cy="18" r="3" /><line x1="20" y1="4" x2="8.12" y2="15.88" /><line x1="14.47" y1="14.48" x2="20" y2="20" /><line x1="8.12" y1="8.12" x2="12" y2="12" />',
            'route' => 'services.index'
        ],
        [
            'name' => 'Barberos',
            'icon' => '<circle cx="12" cy="8" r="5" /><path d="M20 21a8 8 0 1 0-16 0" />',
            'route' => 'staff'
        ],
    ];

    public function render()
    {
        return view('livewire.sidebar');
    }
}