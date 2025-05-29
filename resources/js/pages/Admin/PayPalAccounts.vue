<!-- resources/js/Pages/Admin/PayPalAccounts.vue -->
<template>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
        <AdminLayout>
            <template #header>
                <h2 class="text-2xl font-semibold text-white">Gestión de Cuentas PayPal</h2>
            </template>

            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-medium text-gray-900 mb-4">Configuración de Cuentas PayPal para Sandbox</h3>

                        <!-- Alerta de modo sandbox -->
                        <div v-if="isSandboxMode" class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Modo Sandbox Activo</strong>: Esta configuración solo se aplica al entorno de pruebas. En producción se utilizarán los emails reales de los usuarios.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pestañas para vendedores y compradores -->
                        <div class="mb-6">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex" aria-label="Tabs">
                                    <button 
                                        @click="activeTab = 'sellers'"
                                        :class="[
                                            'py-2 px-4 text-center border-b-2 font-medium text-sm',
                                            activeTab === 'sellers' 
                                                ? 'border-green-500 text-green-600' 
                                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                        ]"
                                    >
                                        Cuentas de Vendedores
                                    </button>
                                    <button 
                                        @click="activeTab = 'buyers'"
                                        :class="[
                                            'py-2 px-4 text-center border-b-2 font-medium text-sm',
                                            activeTab === 'buyers' 
                                                ? 'border-blue-500 text-blue-600' 
                                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                        ]"
                                    >
                                        Cuentas de Compradores
                                    </button>
                                </nav>
                            </div>
                        </div>

                        <!-- Contenido de la pestaña de vendedores -->
                        <div v-if="activeTab === 'sellers'">
                            <div class="bg-green-50 p-4 rounded mb-6">
                                <h4 class="font-medium text-green-800 mb-2">Configuración de Vendedores</h4>
                                <p class="text-sm text-green-700">
                                    Asigna cuentas de PayPal Sandbox a los vendedores para pruebas. Estas cuentas deben ser de tipo <strong>Business</strong>.
                                </p>
                            </div>

                            <!-- Tabla de cuentas de vendedores -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Real</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Sandbox</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="user in users" :key="user.id">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.first_name }} {{ user.last_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ getSellerSandboxEmail(user.id) || '(No asignado)' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Configuración por defecto -->
                            <div class="mt-6 p-4 bg-gray-50 rounded">
                                <h5 class="font-medium text-gray-700 mb-2">Cuenta de vendedor por defecto</h5>
                                <p class="text-sm text-gray-600">
                                    Esta cuenta se usará para vendedores sin cuenta específica asignada: 
                                    <span class="font-mono bg-white px-2 py-1 rounded">{{ defaultSellerAccount }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Contenido de la pestaña de compradores -->
                        <div v-if="activeTab === 'buyers'">
                            <div class="bg-blue-50 p-4 rounded mb-6">
                                <h4 class="font-medium text-blue-800 mb-2">Configuración de Compradores</h4>
                                <p class="text-sm text-blue-700">
                                    Asigna cuentas de PayPal Sandbox a los compradores para pruebas. Estas cuentas deben ser de tipo <strong>Personal</strong>.
                                </p>
                            </div>

                            <!-- Tabla de cuentas de compradores -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Real</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Sandbox</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="user in users" :key="user.id">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.first_name }} {{ user.last_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ getBuyerSandboxEmail(user.id) || '(No asignado)' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Configuración por defecto -->
                            <div class="mt-6 p-4 bg-gray-50 rounded">
                                <h5 class="font-medium text-gray-700 mb-2">Cuenta de comprador por defecto</h5>
                                <p class="text-sm text-gray-600">
                                    Esta cuenta se usará para compradores sin cuenta específica asignada: 
                                    <span class="font-mono bg-white px-2 py-1 rounded">{{ defaultBuyerAccount }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Instrucciones de configuración -->
                        <div class="mt-8 border-t pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Cómo configurar las cuentas</h4>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-sm text-gray-700 mb-4">
                                    Para asignar cuentas de sandbox a usuarios, edita el archivo <code>config/paypal.php</code> y actualiza los arrays correspondientes.
                                </p>
                                
                                <pre class="bg-gray-800 text-gray-100 p-4 rounded text-xs overflow-x-auto">
// Para vendedores:
'seller_sandbox_accounts' => [
    // id_usuario => correo_sandbox
    1 => 'sb-vendedor1@business.example.com',
    2 => 'sb-vendedor2@business.example.com',
    // Añade más según necesites
],

// Para compradores:
'buyer_sandbox_accounts' => [
    // id_usuario => correo_sandbox
    1 => 'sb-comprador1@personal.example.com',
    2 => 'sb-comprador2@personal.example.com',
    // Añade más según necesites
],
                                </pre>
                            </div>

                            <div class="mt-6">
                                <h5 class="font-medium text-gray-700 mb-2">Cómo crear cuentas de sandbox</h5>
                                <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-600">
                                    <li>Accede al <a href="https://developer.paypal.com/dashboard/" target="_blank" class="text-blue-600 hover:underline">Panel de desarrollador de PayPal</a></li>
                                    <li>Ve a "Sandbox" > "Accounts"</li>
                                    <li>Haz clic en "Create account"</li>
                                    <li>Para vendedores, selecciona "Business" como tipo de cuenta</li>
                                    <li>Para compradores, selecciona "Personal" como tipo de cuenta</li>
                                    <li>Completa los datos y crea la cuenta</li>
                                    <li>Copia el email generado (formato: sb-xxxx@example.com)</li>
                                    <li>Añade el email a la configuración en <code>config/paypal.php</code></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

export default defineComponent({
    components: {
        AdminLayout,
    },
    
    props: {
        users: {
            type: Array,
            required: true
        },
        sellerAccounts: {
            type: Object,
            required: true
        },
        buyerAccounts: {
            type: Object,
            required: true
        },
        defaultSellerAccount: {
            type: String,
            required: true
        },
        defaultBuyerAccount: {
            type: String,
            required: true
        },
        isSandboxMode: {
            type: Boolean,
            default: true
        }
    },
    
    setup(props) {
        const activeTab = ref('sellers');
        
        const getSellerSandboxEmail = (userId) => {
            return props.sellerAccounts[userId] || null;
        };
        
        const getBuyerSandboxEmail = (userId) => {
            return props.buyerAccounts[userId] || null;
        };
        
        return {
            activeTab,
            getSellerSandboxEmail,
            getBuyerSandboxEmail
        };
    }
});
</script>