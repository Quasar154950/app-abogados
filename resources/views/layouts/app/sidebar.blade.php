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

            {{-- Buscador sidebar --}}
            <div class="px-3 mt-4 mb-2">
                <button 
                    x-on:click="$dispatch('open-global-search')"
                    class="w-full flex items-center justify-between pl-3 pr-2 py-1.5 text-xs rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-400 hover:border-zinc-400 dark:hover:border-zinc-500 transition-all cursor-text group"
                >
                    <div class="flex items-center gap-2">
                        <flux:icon name="magnifying-glass" variant="micro" class="text-zinc-400 group-hover:text-green-500 transition-colors" />
                        <span>Buscar...</span>
                    </div>
                    
                    <div class="hidden sm:flex items-center gap-1 bg-zinc-100 dark:bg-zinc-700 px-1.5 py-0.5 rounded border border-zinc-200 dark:border-zinc-600 text-[9px] font-medium text-zinc-500">
                        <span>Ctrl</span>
                        <span>K</span>
                    </div>
                </button>
            </div>

            <flux:sidebar.nav>
                <flux:sidebar.group heading="Navegación" class="grid">
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
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        {{-- HEADER MOBILE --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            Configuración
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                        >
                            Cerrar sesión
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{-- CONTENIDO PRINCIPAL --}}
        <flux:main class="w-full max-w-none px-4">
            <div class="w-full max-w-none">
                {{ $slot }}
            </div>
        </flux:main>

        <livewire:actions.global-search />

        @fluxScripts

        {{-- 🔥 FIX DEFINITIVO MOBILE --}}
        <style>
            @media (max-width: 1023px) {
                [data-flux-main] {
                    margin-left: 0 !important;
                    padding-left: 0 !important;
                }
            }
        </style>

    </body>
</html>