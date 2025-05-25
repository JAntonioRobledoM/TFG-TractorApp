<!-- resources/js/Components/NotificacionesChat.vue -->
<template>
  <div class="relative">
    <!-- Icono de notificaciones -->
    <button 
      @click="toggleMenu" 
      class="relative p-2 text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500"
    >
      <svg 
        xmlns="http://www.w3.org/2000/svg" 
        class="h-6 w-6" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" 
        />
      </svg>
      
      <!-- Indicador de mensajes no leídos -->
      <span 
        v-if="totalNoLeidos > 0" 
        class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
      >
        {{ totalNoLeidos > 9 ? '9+' : totalNoLeidos }}
      </span>
    </button>
    
    <!-- Menú desplegable de chats -->
    <div 
      v-if="menuAbierto" 
      class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200"
    >
      <div class="px-4 py-2 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Conversaciones</h3>
      </div>
      
      <div v-if="cargando" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-green-500"></div>
      </div>
      
      <div v-else-if="conversations.length === 0" class="px-4 py-6 text-center text-gray-500">
        No tienes conversaciones activas
      </div>
      
      <div v-else class="max-h-96 overflow-y-auto">
        <div 
          v-for="conversation in conversations" 
          :key="conversation.id" 
          class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
          @click="abrirChat(conversation.id)"
        >
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium text-gray-800">
                {{ obtenerTituloConversacion(conversation) }}
              </p>
              <p class="text-sm text-gray-500">
                {{ obtenerParticipantes(conversation) }}
              </p>
            </div>
            <div>
              <span 
                v-if="conversation.unread_count > 0" 
                class="bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
              >
                {{ conversation.unread_count }}
              </span>
            </div>
          </div>
          <p class="text-xs text-gray-400 mt-1">
            {{ formatearFecha(conversation.updated_at) }}
          </p>
        </div>
      </div>
    </div>
    
    <!-- Componente de Chat -->
    <Chat 
      v-if="chatActivo" 
      :conversationId="conversationActiva" 
      :showChat="chatActivo" 
      :userId="userId"
      @close="cerrarChat" 
    />
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import Chat from './Chat.vue';

export default {
  components: {
    Chat
  },
  
  props: {
    userId: {
      type: Number,
      required: true
    }
  },
  
  setup(props) {
    const conversations = ref([]);
    const cargando = ref(true);
    const menuAbierto = ref(false);
    const chatActivo = ref(false);
    const conversationActiva = ref(null);
    const intervalo = ref(null);
    
    // Obtener la cantidad total de mensajes no leídos
    const totalNoLeidos = computed(() => {
      return conversations.value.reduce((total, conv) => total + (conv.unread_count || 0), 0);
    });
    
    // Cargar conversaciones
    const cargarConversaciones = async () => {
      try {
        cargando.value = true;
        const response = await axios.get('/chat/conversations');
        conversations.value = response.data.conversations;
        cargando.value = false;
      } catch (error) {
        console.error('Error al cargar conversaciones:', error);
        cargando.value = false;
      }
    };
    
    // Formatear fecha
    const formatearFecha = (fecha) => {
      const date = new Date(fecha);
      const hoy = new Date();
      const ayer = new Date(hoy);
      ayer.setDate(ayer.getDate() - 1);
      
      // Si es hoy, mostrar solo la hora
      if (date.toDateString() === hoy.toDateString()) {
        return `Hoy, ${date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false })}`;
      } 
      // Si es ayer, mostrar "Ayer" y la hora
      else if (date.toDateString() === ayer.toDateString()) {
        return `Ayer, ${date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false })}`;
      } 
      // Si es otro día, mostrar fecha completa
      else {
        return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
      }
    };
    
    // Abrir/cerrar menú
    const toggleMenu = () => {
      menuAbierto.value = !menuAbierto.value;
      if (menuAbierto.value) {
        cargarConversaciones();
      }
    };
    
    // Cerrar menú al hacer clic fuera
    const cerrarMenuAlClickFuera = (event) => {
      const targetElement = event.target;
      if (!targetElement.closest('.relative')) {
        menuAbierto.value = false;
      }
    };
    
    // Abrir chat
    const abrirChat = (conversationId) => {
      conversationActiva.value = conversationId;
      chatActivo.value = true;
      menuAbierto.value = false;
      
      // Actualizar el contador de no leídos
      const convIndex = conversations.value.findIndex(c => c.id === conversationId);
      if (convIndex !== -1) {
        conversations.value[convIndex].unread_count = 0;
      }
    };
    
    // Cerrar chat
    const cerrarChat = () => {
      chatActivo.value = false;
    };
    
    // Obtener título de la conversación
    const obtenerTituloConversacion = (conversation) => {
      if (conversation.request && conversation.request.listing && conversation.request.listing.tractor) {
        const tractor = conversation.request.listing.tractor;
        return `${tractor.brand} ${tractor.model}`;
      }
      return conversation.title || 'Conversación';
    };
    
    // Obtener nombres de participantes (excluyendo al usuario actual)
    const obtenerParticipantes = (conversation) => {
      if (!conversation.participants || conversation.participants.length === 0) {
        return 'Sin participantes';
      }
      
      const otrosParticipantes = conversation.participants
        .filter(p => p.id !== props.userId)
        .map(p => `${p.first_name} ${p.last_name}`);
      
      if (otrosParticipantes.length === 0) {
        return 'Solo tú';
      }
      
      return otrosParticipantes.join(', ');
    };
    
    // Configurar intervalo para actualizar conversaciones
    onMounted(() => {
      document.addEventListener('click', cerrarMenuAlClickFuera);
      cargarConversaciones(); // Cargar al inicio
      
      intervalo.value = setInterval(() => {
        if (!menuAbierto.value && !chatActivo.value) {
          cargarConversaciones();
        }
      }, 30000); // Cada 30 segundos
    });
    
    onUnmounted(() => {
      document.removeEventListener('click', cerrarMenuAlClickFuera);
      if (intervalo.value) {
        clearInterval(intervalo.value);
      }
    });
    
    return {
      conversations,
      cargando,
      menuAbierto,
      chatActivo,
      conversationActiva,
      totalNoLeidos,
      cargarConversaciones,
      formatearFecha,
      toggleMenu,
      abrirChat,
      cerrarChat,
      obtenerTituloConversacion,
      obtenerParticipantes
    };
  }
}
</script>