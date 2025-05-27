<!-- resources/js/Components/ChatButton.vue -->
<template>
  <div>
    <!-- Botón para iniciar chat -->
    <button
      @click="iniciarChat"
      class="flex items-center rounded-lg bg-green-600 px-4 py-2 font-bold text-white transition-colors duration-300 hover:bg-green-700"
      :disabled="cargando"
    >
      <span v-if="cargando" class="mr-2">
        <div class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
      </span>
      <span v-else class="mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
          />
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
      :requestInfo="requestInfo" 
      @close="cerrarChat" 
      @payment-completed="handlePaymentCompleted"
    />
  </div>
</template>

<script>
import axios from 'axios';
import { ref, watch } from 'vue';
import Chat from './Chat.vue';

export default {
  components: {
    Chat,
  },

  props: {
    requestId: {
      type: Number,
      required: true,
    },
    userId: {
      type: Number,
      required: true,
    },
    requestInfo: {
      type: Object,
      default: null
    }
  },

  setup(props, { emit }) {
    const chatActivo = ref(false);
    const conversationId = ref(null);
    const cargando = ref(false);
    const requestData = ref(props.requestInfo);

    // Actualizar requestData cuando cambie el prop
    watch(() => props.requestInfo, (newValue) => {
      requestData.value = newValue;
    });

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
        console.log('Respuesta del servidor:', response.data);

        // Asegurarnos de que tenemos una conversación válida
        if (response.data && response.data.conversation && response.data.conversation.id) {
          conversationId.value = response.data.conversation.id;
          chatActivo.value = true;
        } else {
          console.error('No se recibió un ID de conversación válido');
          alert('Error al iniciar el chat. No se recibió un ID de conversación válido.');
        }
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

    // Manejar pago completado
    const handlePaymentCompleted = (paymentData) => {
      console.log('Pago completado en ChatButton:', paymentData);
      
      // Emitir evento al componente padre para que pueda actualizar el estado si es necesario
      emit('payment-completed', {
        requestId: props.requestId,
        paymentData: paymentData
      });
    };

    return {
      chatActivo,
      conversationId,
      cargando,
      iniciarChat,
      cerrarChat,
      handlePaymentCompleted
    };
  },
};
</script>