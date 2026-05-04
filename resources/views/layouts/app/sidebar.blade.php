<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body 
        x-data 
        x-on:keydown.window.ctrl.k.prevent="$dispatch('open-global-search')"
        x-on:keydown.window.meta.k.prevent="$dispatch('open-global-search')"
        class="min-h-screen bg-zinc-100 dark:bg-zinc-800"
    >
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            {{-- Buscador --}}
            <div class="px-3 mt-4 mb-2">
                <button 
                    x-on:click="$dispatch('open-global-search')"
                    class="w-full flex items-center justify-between pl-3 pr-2 py-1.5 text-xs rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-400 hover:border-zinc-400 dark:hover:border-zinc-500 transition-all cursor-text group"
                >
                    <div class="flex items-center gap-2">
                        <flux:icon name="magnifying-glass" variant="micro" />
                        <span>Buscar...</span>
                    </div>
                    <div class="hidden sm:flex items-center gap-1 bg-zinc-100 dark:bg-zinc-700 px-1.5 py-0.5 rounded text-[9px]">
                        <span>Ctrl</span>
                        <span>K</span>
                    </div>
                </button>
            </div>

            {{-- 🔥 SIDEBAR SEGÚN ROL --}}
            <flux:sidebar.nav>
                <flux:sidebar.group heading="Navegación" class="grid">

                    @if(auth()->user()->email === 'soporte@tuempresa.com')

                        {{-- SOLO SOPORTE --}}
                        <flux:sidebar.item icon="home" href="/soporte">
                            Panel de soporte
                        </flux:sidebar.item>

                    @else

                        {{-- ABOGADO --}}
                        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                            Panel
                        </flux:sidebar.item>

                        <flux:sidebar.item icon="users" :href="route('clientes.index')" :current="request()->routeIs('clientes.*')" wire:navigate>
                            Clientes
                        </flux:sidebar.item>

                        <flux:sidebar.item icon="clipboard-document-list" :href="route('seguimientos.index')" :current="request()->routeIs('seguimientos.*')" wire:navigate>
                            Tareas
                        </flux:sidebar.item>

                        <flux:sidebar.item icon="clock" :href="route('actividades.index')" :current="request()->routeIs('actividades.*')" wire:navigate>
                            Historial
                        </flux:sidebar.item>

                    @endif

                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        {{-- HEADER MOBILE --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle icon="bars-2" inset="left" />
            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.item :href="route('profile.edit')">
                        Configuración
                    </flux:menu.item>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit">
                            Cerrar sesión
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{-- CONTENIDO --}}
        <flux:main class="w-full">
            <div class="w-full max-w-[1100px] mx-auto px-0 md:px-4">
                {{ $slot }}
            </div>
        </flux:main>

        <livewire:actions.global-search />

        @fluxScripts
    </body>
</html>