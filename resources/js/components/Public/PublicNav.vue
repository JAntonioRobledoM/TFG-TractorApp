<template>
    <nav class="border-b-4 border-yellow-600 bg-green-900 shadow-lg">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <!-- Logo with tractor icon -->
                    <div class="flex shrink-0 items-center">
                        <Link :href="route('home')" class="flex items-center space-x-2 text-xl font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-8 w-8 text-yellow-400" fill="currentColor">
                                <path
                                    d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"
                                />
                            </svg>
                            <span class="text-yellow-400">TractorApp</span>
                        </Link>
                    </div>

                    <!-- Navigation Links with field-inspired hover states -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <Link
                            :href="route('home')"
                            class="flex items-center space-x-2 rounded-md px-3 py-2 text-green-100 transition duration-200 hover:bg-green-800 hover:text-yellow-400"
                            :class="{ 'bg-green-800 text-yellow-400': route().current('home') }"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                                />
                            </svg>
                            <span>Inicio</span>
                        </Link>

                        <!-- Mi Maquinaria - Solo visible para usuarios con sesión iniciada -->
                        <div v-if="$page.props.auth.user" class="relative flex items-center" ref="maquinariaDropdownRef">
                            <button
                                @click="toggleMaquinariaDropdown"
                                class="flex items-center space-x-2 rounded-md px-3 py-2 text-green-100 transition duration-200 hover:bg-green-800 hover:text-yellow-400"
                                :class="{ 'bg-green-800 text-yellow-400': route().current('user.tractors.*') || route().current('user.aperos.*') }"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                                    />
                                </svg>
                                <span>Mi Maquinaria</span>
                                <svg
                                    class="-mr-0.5 ml-1 h-4 w-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': showMaquinariaDropdown }"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <div
                                    v-show="showMaquinariaDropdown"
                                    class="ring-opacity-5 absolute left-0 z-50 mt-1 w-64 rounded-md bg-white shadow-lg ring-1 ring-black focus:outline-none"
                                    style="top: 100%"
                                >
                                    <div class="rounded-t-lg border-b border-green-200 bg-green-50 px-4 py-2">
                                        <div class="text-base font-medium text-green-800">Gestiona tu Maquinaria</div>
                                        <div class="text-sm text-green-600">Tractores y Aperos</div>
                                    </div>

                                    <Link
                                        :href="route('user.tractors.index')"
                                        @click="showMaquinariaDropdown = false"
                                        class="flex w-full items-center px-4 py-3 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-green-50 focus:bg-green-50 focus:outline-none"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="mr-3 h-5 w-5 text-green-600"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                        >
                                            <path
                                                d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"
                                            />
                                        </svg>
                                        <div>
                                            <div class="font-medium">Mis Tractores</div>
                                            <div class="text-xs text-gray-500">Ver y gestionar tractores</div>
                                        </div>
                                    </Link>

                                    <Link
                                        :href="route('user.aperos.index')"
                                        @click="showMaquinariaDropdown = false"
                                        class="flex w-full items-center border-t border-gray-100 px-4 py-3 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-green-50 focus:bg-green-50 focus:outline-none"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="mr-3 h-5 w-5 text-green-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                                            />
                                        </svg>
                                        <div>
                                            <div class="font-medium">Mis Aperos</div>
                                            <div class="text-xs text-gray-500">Ver y gestionar aperos</div>
                                        </div>
                                    </Link>
                                </div>
                            </Transition>
                        </div>

                        <!-- Enlace a Anuncios - Solo visible para usuarios con sesión iniciada -->
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('user.listings.index')"
                            class="flex items-center space-x-2 rounded-md px-3 py-2 text-green-100 transition duration-200 hover:bg-green-800 hover:text-yellow-400"
                            :class="{ 'bg-green-800 text-yellow-400': route().current('user.listings.*') }"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            <span>Anuncios</span>
                        </Link>

                        <Link
                            :href="route('contact')"
                            class="flex items-center space-x-2 rounded-md px-3 py-2 text-green-100 transition duration-200 hover:bg-green-800 hover:text-yellow-400"
                            :class="{ 'bg-green-800 text-yellow-400': route().current('contact') }"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            <span>Contacto</span>
                        </Link>
                    </div>
                </div>

                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <template v-if="$page.props.auth.user">
                        <!-- Settings Dropdown with farmer touch -->
                        <div class="relative ml-3" ref="userDropdownRef">
                            <button
                                @click="toggleUserDropdown"
                                type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-green-800 px-3 py-2 text-sm leading-4 font-medium text-green-100 transition duration-150 ease-in-out hover:bg-green-700 hover:text-yellow-400 focus:outline-none"
                            >
                                {{ $page.props.auth.user.first_name }}
                                <svg
                                    class="-mr-0.5 ml-2 h-4 w-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': showUserDropdown }"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <div
                                    v-show="showUserDropdown"
                                    class="ring-opacity-5 absolute right-0 z-50 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black focus:outline-none"
                                >
                                    <div class="rounded-t-lg border-b border-green-200 bg-green-50 px-4 py-2">
                                        <div class="text-base font-medium text-green-800">
                                            {{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}
                                        </div>
                                        <div class="text-sm font-medium text-green-600">{{ $page.props.auth.user.email }}</div>
                                    </div>

                                    <!-- Panel de Admin - Solo visible para administradores -->
                                    <Link
                                        v-if="$page.props.auth.user.is_admin === 1"
                                        :href="route('admin.dashboard')"
                                        @click="showUserDropdown = false"
                                        class="mt-2 block w-full border-t border-gray-200 bg-green-50 px-4 py-2 pt-2 text-left text-sm leading-5 font-medium text-green-700 hover:bg-green-100 focus:bg-green-100 focus:outline-none"
                                    >
                                        <div class="flex items-center">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="mr-2 h-4 w-4 text-green-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                            </svg>
                                            Panel de Admin
                                        </div>
                                    </Link>

                                    <!-- Enlace al panel de control para usuarios normales -->
                                    <Link
                                        :href="route('user.dashboard')"
                                        @click="showUserDropdown = false"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-green-50 focus:bg-green-50 focus:outline-none"
                                    >
                                        Panel de Control
                                    </Link>

                                    <Link
                                        :href="route('user.profile.show')"
                                        @click="showUserDropdown = false"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-green-50 focus:bg-green-50 focus:outline-none"
                                    >
                                        Mi Perfil
                                    </Link>

                                    <!-- Enlace a Mis Solicitudes -->
                                    <Link
                                        :href="route('user.requests.index')"
                                        @click="showUserDropdown = false"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-green-50 focus:bg-green-50 focus:outline-none"
                                    >
                                        Mis Solicitudes
                                    </Link>

                                    <Link
                                        :href="route('logout')"
                                        @click="showUserDropdown = false"
                                        method="post"
                                        as="button"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-green-50 focus:bg-green-50 focus:outline-none"
                                    >
                                        Cerrar sesión
                                    </Link>
                                </div>
                            </Transition>
                        </div>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md px-3 py-2 text-sm font-medium text-green-100 transition duration-200 hover:bg-green-800 hover:text-yellow-400"
                        >
                            Iniciar Sesión
                        </Link>
                        <Link
                            :href="route('register')"
                            class="ml-3 rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white transition duration-200 hover:bg-green-700"
                        >
                            Registrarse
                        </Link>
                    </template>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        @click="toggleMobileMenu"
                        class="inline-flex items-center justify-center rounded-md p-2 text-green-200 transition duration-150 ease-in-out hover:bg-green-800 hover:text-yellow-400 focus:bg-green-800 focus:text-yellow-400 focus:outline-none"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{ hidden: mobileMenuOpen, 'inline-flex': !mobileMenuOpen }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{ hidden: !mobileMenuOpen, 'inline-flex': mobileMenuOpen }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ block: mobileMenuOpen, hidden: !mobileMenuOpen }" class="border-t border-green-700 bg-green-800 sm:hidden">
            <div class="space-y-1 pt-2 pb-3">
                <Link
                    :href="route('home')"
                    :class="{ 'bg-green-700 text-yellow-400': route().current('home') }"
                    class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                >
                    Inicio
                </Link>

                <!-- Mi Maquinaria para móvil - Solo visible para usuarios con sesión iniciada -->
                <div v-if="$page.props.auth.user" class="space-y-1">
                    <div class="mb-1 border-b border-green-700 px-3 py-2 pb-1 text-base font-medium text-green-100">Mi Maquinaria</div>
                    <Link
                        :href="route('user.tractors.index')"
                        :class="{ 'bg-green-700 text-yellow-400': route().current('user.tractors.*') }"
                        class="block rounded-md py-2 pr-3 pl-6 text-sm font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                    >
                        Mis Tractores
                    </Link>
                    <Link
                        :href="route('user.aperos.index')"
                        :class="{ 'bg-green-700 text-yellow-400': route().current('user.aperos.*') }"
                        class="block rounded-md py-2 pr-3 pl-6 text-sm font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                    >
                        Mis Aperos
                    </Link>
                </div>

                <!-- Enlace a Anuncios para móvil - Solo visible para usuarios con sesión iniciada -->
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('user.listings.index')"
                    :class="{ 'bg-green-700 text-yellow-400': route().current('user.listings.*') }"
                    class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                >
                    Anuncios
                </Link>
                <Link
                    :href="route('contact')"
                    :class="{ 'bg-green-700 text-yellow-400': route().current('contact') }"
                    class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                >
                    Contacto
                </Link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="border-t border-green-700 pt-4 pb-1">
                <template v-if="$page.props.auth.user">
                    <div class="px-4">
                        <div class="text-base font-medium text-green-100">
                            {{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}
                        </div>
                        <div class="text-sm font-medium text-green-300">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <!-- Panel de usuario para móvil -->
                        <Link
                            :href="route('user.dashboard')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                        >
                            Panel de Control
                        </Link>

                        <Link
                            :href="route('user.profile.show')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                        >
                            Mi Perfil
                        </Link>

                        <!-- Mis solicitudes para móvil -->
                        <Link
                            :href="route('user.requests.index')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                        >
                            Mis Solicitudes
                        </Link>

                        <!-- Panel de Admin en versión móvil - Solo visible para administradores -->
                        <Link
                            v-if="$page.props.auth.user.is_admin === 1"
                            :href="route('admin.dashboard')"
                            class="mt-2 flex items-center rounded-md border-t border-green-700 bg-green-700 px-3 py-2 pt-2 text-base font-medium text-yellow-400 hover:bg-green-600"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-2 h-5 w-5 text-yellow-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Panel de Admin
                        </Link>
                        <Link
                            :href="route('user.profile.show')"
                            @click="showUserDropdown = false"
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-green-50 focus:bg-green-50 focus:outline-none"
                        >
                            Mi Perfil
                        </Link>

                        <!-- Agregar el enlace al perfil en el menú móvil -->
                        <Link
                            :href="route('user.profile.show')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                        >
                            Mi Perfil
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                        >
                            Cerrar sesión
                        </Link>
                    </div>
                </template>
                <template v-else>
                    <div class="mt-3 space-y-1">
                        <Link
                            :href="route('login')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                        >
                            Iniciar Sesión
                        </Link>
                        <Link
                            :href="route('register')"
                            class="block rounded-md px-3 py-2 text-base font-medium text-green-100 hover:bg-green-700 hover:text-yellow-400"
                        >
                            Registrarse
                        </Link>
                    </div>
                </template>
            </div>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

const mobileMenuOpen = ref(false);
const showMaquinariaDropdown = ref(false);
const showUserDropdown = ref(false);
const maquinariaDropdownRef = ref(null);
const userDropdownRef = ref(null);

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const toggleMaquinariaDropdown = () => {
    showMaquinariaDropdown.value = !showMaquinariaDropdown.value;
    showUserDropdown.value = false; // Cerrar el otro dropdown
};

const toggleUserDropdown = () => {
    showUserDropdown.value = !showUserDropdown.value;
    showMaquinariaDropdown.value = false; // Cerrar el otro dropdown
};

// Función para cerrar dropdowns al hacer clic fuera
const handleClickOutside = (event) => {
    // Cerrar dropdown de maquinaria si se hace clic fuera de él
    if (maquinariaDropdownRef.value && !maquinariaDropdownRef.value.contains(event.target)) {
        showMaquinariaDropdown.value = false;
    }

    // Cerrar dropdown de usuario si se hace clic fuera de él
    if (userDropdownRef.value && !userDropdownRef.value.contains(event.target)) {
        showUserDropdown.value = false;
    }
};

// Cerrar dropdowns al presionar Escape
const handleKeyDown = (event) => {
    if (event.key === 'Escape') {
        showMaquinariaDropdown.value = false;
        showUserDropdown.value = false;
        mobileMenuOpen.value = false;
    }
};

// Configurar event listeners al montar el componente
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleKeyDown);
});

// Limpiar event listeners al desmontar el componente
onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleKeyDown);
});

const logout = () => {
    router.post(
        route('logout'),
        {},
        {
            onSuccess: () => {
                window.location.href = route('home');
            },
            onError: (errors) => {
                console.error('Error al cerrar sesión:', errors);
            },
        },
    );
};
</script>

<style scoped>
/* Add tractor wheel pattern to the nav */
.bg-green-900 {
    background-image:
        linear-gradient(to bottom, rgba(20, 83, 45, 0.98), rgba(20, 83, 45, 0.98)),
        radial-gradient(circle at 10px 10px, rgba(234, 179, 8, 0.05) 2px, transparent 2px);
    background-size:
        100%,
        20px 20px;
}

/* Asegurar que los dropdowns estén por encima de otros elementos */
.z-50 {
    z-index: 50;
}

/* Animaciones para los dropdowns */
.rotate-180 {
    transform: rotate(180deg);
}

/* Estilos adicionales para mejorar la accesibilidad */
button:focus {
    outline: 2px solid #fbbf24;
    outline-offset: 2px;
}

/* Hover states mejorados */
.hover\:bg-green-50:hover {
    background-color: rgb(240 253 244);
}

.hover\:bg-green-800:hover {
    background-color: rgb(22 101 52);
}

.hover\:text-yellow-400:hover {
    color: rgb(251 191 36);
}

/* Estados activos */
.bg-green-800 {
    background-color: rgb(22 101 52);
}

.text-yellow-400 {
    color: rgb(251 191 36);
}

/* Mejoras para el menú móvil */
@media (max-width: 640px) {
    .sm\:hidden {
        display: block;
    }

    .hidden.sm\:flex {
        display: none;
    }
}
</style>