<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layout.app')]
#[Title('Configuración')]
class Settings extends Component
{
    public $companyName;
    public $requirePassword;
    public $appPassword;
    public $confirmPassword;
    public $openTime;
    public $closeTime;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->companyName = Setting::get('company_name', 'Barber CRM');
        $this->requirePassword = Setting::get('require_password', '0') === '1' || Setting::get('require_password', '0') === 'true';
        $this->appPassword = '';
        $this->confirmPassword = '';
        $this->openTime = Setting::get('open_time', '08:00');
        $this->closeTime = Setting::get('close_time', '20:00');
    }

    public function save()
    {
        Setting::set('company_name', $this->companyName);
        Setting::set('require_password', $this->requirePassword ? '1' : '0');

        if ($this->requirePassword && $this->appPassword) {
            if ($this->appPassword !== $this->confirmPassword) {
                $this->dispatch('toast', message: 'Las contraseñas no coinciden', type: 'error');
                return;
            }
            Setting::set('app_password', Hash::make($this->appPassword));
        } elseif (!$this->requirePassword) {
            Setting::set('app_password', '');
        }

        Setting::set('open_time', $this->openTime);
        Setting::set('close_time', $this->closeTime);

        $this->dispatch('toast', message: 'Configuración guardada correctamente', type: 'success');
        session()->forget('app_unlocked');
    }

    public function render()
    {
        return view('livewire.settings');
    }
}