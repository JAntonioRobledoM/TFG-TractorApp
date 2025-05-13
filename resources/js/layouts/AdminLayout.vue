<template>
    <div class="min-h-screen bg-gradient-to-b from-green-50 to-green-100 flex flex-col">
        <!-- Navigation with agricultural theme -->
        <nav class="bg-green-900 border-b-4 border-yellow-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo with tractor icon -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('home')" class="flex items-center space-x-2 text-xl font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-8 w-8 text-yellow-400" fill="currentColor">
                                    <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                                </svg>
                                <span class="text-yellow-400">TractorApp</span>
                            </Link>
                        </div>

                        <!-- Navigation Links with field-inspired hover states -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')" 
                                class="text-green-100 hover:text-yellow-400 hover:bg-green-800 px-3 py-2 rounded-md transition duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    <span>Dashboard</span>
                                </div>
                            </NavLink>
                            <NavLink :href="route('users.index')" :active="route().current('users.*')"
                                class="text-green-100 hover:text-yellow-400 hover:bg-green-800 px-3 py-2 rounded-md transition duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    <span>Usuarios</span>
                                </div>
                            </NavLink>
                            <NavLink :href="route('tractors.index')" :active="route().current('tractors.*')"
                                class="text-green-100 hover:text-yellow-400 hover:bg-green-800 px-3 py-2 rounded-md transition duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                                    </svg>
                                    <span>Tractores</span>
                                </div>
                            </NavLink>
                            <!-- Enlace para Aperos -->
                            <NavLink :href="route('aperos.index')" :active="route().current('aperos.*')"
                                class="text-green-100 hover:text-yellow-400 hover:bg-green-800 px-3 py-2 rounded-md transition duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 9H10a1 1 0 110-2h3.586l-2.293-2.293A1 1 0 0112 2z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Aperos</span>
                                </div>
                            </NavLink>
                            <NavLink :href="route('listings.index')" :active="route().current('listings.*')"
                                class="text-green-100 hover:text-yellow-400 hover:bg-green-800 px-3 py-2 rounded-md transition duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                                    </svg>
                                    <span>Anuncios</span>
                                </div>
                            </NavLink>
                            <NavLink :href="route('requests.index')" :active="route().current('requests.*')"
                                class="text-green-100 hover:text-yellow-400 hover:bg-green-800 px-3 py-2 rounded-md transition duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Solicitudes</span>
                                </div>
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Notifications Dropdown with harvest theme -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <div class="relative">
                                        <button
                                            class="flex items-center text-green-100 hover:text-yellow-400 focus:outline-none focus:text-yellow-400 transition duration-150 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                            <span v-if="unreadNotificationsCount > 0"
                                                class="absolute -top-1 -right-1 bg-red-600 text-xs text-white font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                                {{ unreadNotificationsCount }}
                                            </span>
                                        </button>
                                    </div>
                                </template>

                                <template #content>
                                    <div class="px-4 py-2 border-b border-green-200 bg-green-50 rounded-t-lg">
                                        <div class="font-medium text-base text-green-800">Notificaciones</div>
                                    </div>
                                    <div v-if="notifications.length > 0" class="max-h-60 overflow-y-auto bg-white">
                                        <div v-for="notification in notifications" :key="notification.id"
                                            class="px-4 py-2 text-sm text-gray-700 hover:bg-green-50 border-b border-green-100 last:border-b-0">
                                            <Link :href="route('notifications.show', notification.id)" method="get"
                                                as="button" class="w-full text-left">
                                            <div class="font-semibold"
                                                :class="notification.is_read ? 'text-gray-600' : 'text-green-800'">
                                                {{ notification.type }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">{{ notification.message }}</div>
                                            </Link>
                                        </div>
                                    </div>
                                    <div v-else class="px-4 py-2 text-sm text-gray-700 bg-white">
                                        No hay notificaciones nuevas
                                    </div>
                                    <div class="px-4 py-2 border-t border-green-200 bg-gray-50 rounded-b-lg">
                                        <Link :href="route('notifications.index')"
                                            class="block text-sm text-green-600 hover:text-green-800">
                                        Ver todas las notificaciones
                                        </Link>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Messages Dropdown with field borders -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <div class="relative">
                                        <button
                                            class="flex items-center text-green-100 hover:text-yellow-400 focus:outline-none focus:text-yellow-400 transition duration-150 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                            <span v-if="unreadMessagesCount > 0"
                                                class="absolute -top-1 -right-1 bg-red-600 text-xs text-white font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                                {{ unreadMessagesCount }}
                                            </span>
                                        </button>
                                    </div>
                                </template>

                                <template #content>
                                    <div class="px-4 py-2 border-b border-green-200 bg-green-50 rounded-t-lg">
                                        <div class="font-medium text-base text-green-800">Mensajes</div>
                                    </div>
                                    <div v-if="messages.length > 0" class="max-h-60 overflow-y-auto bg-white">
                                        <div v-for="message in messages" :key="message.id"
                                            class="px-4 py-2 text-sm text-gray-700 hover:bg-green-50 border-b border-green-100 last:border-b-0">
                                            <Link
                                                :href="route('messages.show', message.sender.id !== $page.props.auth.user.id ? message.sender.id : message.receiver.id)"
                                                method="get" as="button" class="w-full text-left">
                                            <div class="font-semibold"
                                                :class="message.is_read ? 'text-gray-600' : 'text-green-800'">
                                                {{ message.sender.id === $page.props.auth.user.id ?
                                                    message.receiver.first_name + ' ' + message.receiver.last_name :
                                                message.sender.first_name + ' ' + message.sender.last_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">{{ message.content }}</div>
                                            </Link>
                                        </div>
                                    </div>
                                    <div v-else class="px-4 py-2 text-sm text-gray-700 bg-white">
                                        No hay mensajes nuevos
                                    </div>
                                    <div class="px-4 py-2 border-t border-green-200 bg-gray-50 rounded-b-lg">
                                        <Link :href="route('messages.index')"
                                            class="block text-sm text-green-600 hover:text-green-800">
                                        Ver todos los mensajes
                                        </Link>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Settings Dropdown with farmer touch -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-100 bg-green-800 hover:text-yellow-400 hover:bg-green-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.first_name }}

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <div class="px-4 py-2 border-b border-green-200 bg-green-50 rounded-t-lg">
                                        <div class="font-medium text-base text-green-800">{{
                                            $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}
                                        </div>
                                        <div class="font-medium text-sm text-green-600">{{ $page.props.auth.user.email }}
                                        </div>
                                    </div>

                                    <Link :href="route('users.edit', $page.props.auth.user.id)"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-green-50 focus:outline-none focus:bg-green-50 transition duration-150 ease-in-out">
                                    Perfil
                                    </Link>

                                    <!-- Links para Mis Tractores y Mis Aperos -->
                                    <Link :href="route('users.tractors', $page.props.auth.user.id)"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-green-50 focus:outline-none focus:bg-green-50 transition duration-150 ease-in-out">
                                    Mis Tractores
                                    </Link>

                                    <form @submit.prevent="logout">
                                        <button type="submit"
                                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-green-50 focus:outline-none focus:bg-green-50 transition duration-150 ease-in-out">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-green-200 hover:text-yellow-400 hover:bg-green-800 focus:outline-none focus:bg-green-800 focus:text-yellow-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path
                                    :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu with tractor patterns -->
            <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden bg-green-800 border-t border-green-700">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"
                        class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('users.index')" :active="route().current('users.*')"
                        class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                        Usuarios
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('tractors.index')" :active="route().current('tractors.*')"
                        class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                        Tractores
                    </ResponsiveNavLink>
                    <!-- Enlace responsive para Aperos -->
                    <ResponsiveNavLink :href="route('aperos.index')" :active="route().current('aperos.*')"
                        class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                        Aperos
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('listings.index')" :active="route().current('listings.*')"
                        class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                        Anuncios
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('requests.index')" :active="route().current('requests.*')"
                        class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                        Solicitudes
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-green-700">
                    <div class="px-4">
                        <div class="font-medium text-base text-green-100">{{ $page.props.auth.user.first_name }} {{
                            $page.props.auth.user.last_name }}</div>
                        <div class="font-medium text-sm text-green-300">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('users.edit', $page.props.auth.user.id)"
                            class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                            Perfil
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('users.tractors', $page.props.auth.user.id)"
                            class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                            Mis Tractores
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('notifications.index')"
                            class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                            Notificaciones
                            <span v-if="unreadNotificationsCount > 0"
                                class="ml-2 bg-red-600 text-xs text-white font-bold rounded-full h-5 w-5 inline-flex items-center justify-center">
                                {{ unreadNotificationsCount }}
                            </span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('messages.index')"
                            class="text-green-100 hover:text-yellow-400 hover:bg-green-700">
                            Mensajes
                            <span v-if="unreadMessagesCount > 0"
                                class="ml-2 bg-red-600 text-xs text-white font-bold rounded-full h-5 w-5 inline-flex items-center justify-center">
                                {{ unreadMessagesCount }}
                            </span>
                        </ResponsiveNavLink>

                        <!-- Authentication -->
                        <form method="POST" @submit.prevent="logout">
                            <ResponsiveNavLink as="button"
                                class="text-green-100 hover:text-yellow-400 hover:bg-green-700 w-full text-left">
                                Cerrar sesión
                            </ResponsiveNavLink>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading with field background -->
        <header class="bg-gradient-to-r from-green-700 to-green-600 shadow-lg" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header"></slot>
            </div>
        </header>

        <!-- Page Content with subtle field pattern -->
        <main class="flex-grow">
            <slot></slot>
        </main>

        <!-- Footer with countryside feel -->
        <footer class="bg-green-900 border-t-4 border-yellow-600 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-green-200 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 8h-2c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1h-2c-1.7 0-3 1.3-3 3v3c0 1.7 1.3 3 3 3h8c1.7 0 3-1.3 3-3v-3c0-1.7-1.3-3-3-3zm-2 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                        </svg>
                        <span>&copy; {{ new Date().getFullYear() }} TractorApp. Todos los derechos reservados.</span>
                    </div>
                    <div class="text-sm text-green-300">
                        Cultivando conexiones
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script lang="ts">
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import NavLink from '@/components/NavLink.vue';
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue';
import Dropdown from '@/components/Dropdown.vue';

const component = {
  components: {
    Link,
    NavLink,
    ResponsiveNavLink,
    Dropdown,
  },
  data() {
    return {
      showingNavigationDropdown: false,
      unreadNotificationsCount: 0,
      unreadMessagesCount: 0,
      notifications: [],
      messages: []
    };
  },
  methods: {
    logout() {
      this.$inertia.post(route('logout'));
    },
    fetchNotifications() {
      axios.get(route('notifications.unread-count'))
        .then(response => {
          this.unreadNotificationsCount = response.data.count;
        })
        .catch(error => {
          console.error('Error al obtener notificaciones no leídas:', error);
        });
      
      axios.get(route('notifications.recent'))
        .then(response => {
          this.notifications = response.data.notifications;
        })
        .catch(error => {
          console.error('Error al obtener notificaciones recientes:', error);
        });
    },
    fetchMessages() {
      axios.get(route('messages.unread-count'))
        .then(response => {
          this.unreadMessagesCount = response.data.count;
        })
        .catch(error => {
          console.error('Error al obtener mensajes no leídos:', error);
        });
    }
  },
  mounted() {
    this.fetchNotifications();
    this.fetchMessages();

    setInterval(() => {
      this.fetchNotifications();
      this.fetchMessages();
    }, 30000);
  }
};

export default component;
</script>

<style scoped>
/* Add subtle field texture to the background */
.bg-gradient-to-b {
    background-image: 
        linear-gradient(to bottom, rgba(240, 253, 244, 0.95), rgba(220, 252, 231, 0.95)),
        repeating-linear-gradient(
            45deg,
            rgba(34, 197, 94, 0.03) 0px,
            rgba(34, 197, 94, 0.03) 10px,
            transparent 10px,
            transparent 20px
        );
}

/* Add tractor wheel pattern to the nav */
.bg-green-900 {
    background-image: 
        linear-gradient(to bottom, rgba(20, 83, 45, 0.98), rgba(20, 83, 45, 0.98)),
        radial-gradient(circle at 10px 10px, rgba(234, 179, 8, 0.05) 2px, transparent 2px);
    background-size: 100%, 20px 20px;
}

/* Footer field rows pattern */
footer {
    background-image: 
        linear-gradient(to bottom, rgba(20, 83, 45, 0.98), rgba(20, 83, 45, 0.98)),
        repeating-linear-gradient(
            0deg,
            rgba(234, 179, 8, 0.05) 0px,
            rgba(234, 179, 8, 0.05) 1px,
            transparent 1px,
            transparent 15px
        );
}

/* Add subtle animation to notifications */
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>