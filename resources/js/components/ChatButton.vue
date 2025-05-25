<!-- resources/js/Components/ChatButton.vue -->
<template>
  <div>
    <!-- Botón para iniciar chat -->
    <button 
      @click="iniciarChat" 
      class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors duration-300"
      :disabled="cargando"
    >
      <span v-if="cargando" class="mr-2">
        <div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
      </span>
      <span v-else class="mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
      </span>
      Iniciar Conversación
    </button>

    <!-- Componente de Chat (se muestra solo cuando chatActivo es true) -->
    <Chat 
      v-if="conversationId" 
      :conversationId="conversationId" 
      :showChat="chatActivo" 
      :userId="userId"
      @close="cerrarChat" 
    />
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import Chat from './Chat.vue';

export default {
  components: {
    Chat
  },
  
  props: {
    requestId: {
      type: Number,
      required: true
    },
    userId: {
      type: Number,
      required: true
    }
  },
  
  setup(props) {
    const chatActivo = ref(false);
    const conversationId = ref(null);
    const cargando = ref(false);
    
    // Función para iniciar chat
    const iniciarChat = async () => {
      if (cargando.value) return;
      
      try {
        cargando.value = true;
        
        // Si ya tenemos un conversationId, solo abrimos el chat
        if (conversationId.value) {
          chatActivo.value = true;
          cargando.value = false;
          return;
        }
        
        // Si no tenemos conversationId, creamos una nueva conversación para esta solicitud
        const response = await axios.get(`/chat/request/${props.requestId}/conversation`);
        conversationId.value = response.data.conversation.id;
        chatActivo.value = true;
      } catch (error) {
        console.error('Error al iniciar chat:', error);
        alert('No se pudo iniciar el chat. Por favor, inténtelo de nuevo.');
      } finally {
        cargando.value = false;
      }
    };
    
    // Función para cerrar chat
    const cerrarChat = () => {
      chatActivo.value = false;
    };
    
    return {
      chatActivo,
      conversationId,
      cargando,
      iniciarChat,
      cerrarChat
    };
  }
}
</script>