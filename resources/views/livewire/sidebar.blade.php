<flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.header>
        <flux:sidebar.brand href="{{ route('home') }}" wire:navigate name="💈 Barber CRM" />
        <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.item
            icon="squares-2x2"
            href="{{ route('home') }}"
            wire:navigate
            :current="request()->routeIs('home')"
        >
            Panel
        </flux:sidebar.item>

        <flux:sidebar.item
            icon="calendar"
            href="{{ route('appointments.index') }}"
            wire:navigate
            :current="request()->routeIs('appointments.index')"
        >
            Turnos
        </flux:sidebar.item>

        <flux:sidebar.item
            icon="users"
            href="{{ route('clients') }}"
            wire:navigate
            :current="request()->routeIs('clients')"
        >
            Clientes
        </flux:sidebar.item>

        <flux:sidebar.item
            icon="scissors"
            href="{{ route('services.index') }}"
            wire:navigate
            :current="request()->routeIs('services.index')"
        >
            Servicios
        </flux:sidebar.item>

        <flux:sidebar.item
            icon="user"
            href="{{ route('staff') }}"
            wire:navigate
            :current="request()->routeIs('staff')"
        >
            Barberos
        </flux:sidebar.item>
    </flux:sidebar.nav>

    <flux:sidebar.spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="cog-6-tooth" href="#">
            Configuración
        </flux:sidebar.item>
    </flux:sidebar.nav>
</flux:sidebar>