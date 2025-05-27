<!-- resources/js/Components/PayPalButton.vue -->
<template>
  <div class="paypal-button-container">
    <!-- Contenedor para el botón de PayPal -->
    <div v-if="!paymentCompleted" ref="paypalButtonContainer" class="mt-4"></div>
    
    <!-- Mensaje de éxito después del pago -->
    <div v-else class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-medium">¡Pago completado correctamente!</span>
      </div>
      <p class="text-sm mt-1">ID de transacción: {{ transactionId }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    amount: {
      type: [Number, String],
      required: true
    },
    currency: {
      type: String,
      default: 'EUR'
    },
    itemName: {
      type: String,
      default: 'Pago por maquinaria agrícola'
    },
    receiverEmail: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      paymentCompleted: false,
      transactionId: '',
      scriptLoaded: false
    };
  },

  mounted() {
    this.loadPayPalScript();
  },

  methods: {
    loadPayPalScript() {
      // Comprobar si el script ya está cargado
      if (document.querySelector('script[src*="paypal.com/sdk/js"]')) {
        this.scriptLoaded = true;
        this.renderPayPalButton();
        return;
      }

      // Crear y cargar el script de PayPal
      const script = document.createElement('script');
      script.src = `https://www.paypal.com/sdk/js?client-id=${this.getPayPalClientId()}&currency=${this.currency}`;
      script.async = true;
      
      script.onload = () => {
        this.scriptLoaded = true;
        this.renderPayPalButton();
      };
      
      script.onerror = (err) => {
        console.error('Error cargando el script de PayPal:', err);
      };
      
      document.body.appendChild(script);
    },

    getPayPalClientId() {
      // En un entorno de producción, esto debería obtenerse de la configuración de la aplicación
      return import.meta.env.VITE_PAYPAL_CLIENT_ID || 'test';
    },

    renderPayPalButton() {
      if (!this.scriptLoaded || !window.paypal) {
        console.warn('PayPal no está cargado correctamente');
        return;
      }

      const amount = parseFloat(this.amount).toFixed(2);

      window.paypal.Buttons({
        // Estilo del botón
        style: {
          layout: 'vertical',
          color: 'gold',
          shape: 'rect',
          label: 'pay'
        },

        // Crear la orden
        createOrder: (data, actions) => {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: amount,
                currency_code: this.currency
              },
              description: this.itemName,
              payee: {
                email_address: this.receiverEmail
              }
            }]
          });
        },

        // Cuando el pago se completa correctamente
        onApprove: (data, actions) => {
          return actions.order.capture().then((details) => {
            // Guardar el ID de la transacción
            this.transactionId = details.id;
            
            // Marcar el pago como completado
            this.paymentCompleted = true;
            
            // Emitir evento para que el componente padre sepa que el pago se completó
            this.$emit('payment-completed', {
              orderId: data.orderID,
              details: details
            });
            
            console.log('Pago completado', details);
          });
        },

        // Manejo de errores
        onError: (err) => {
          console.error('Error en el pago:', err);
          this.$emit('payment-error', err);
        }
      }).render(this.$refs.paypalButtonContainer);
    }
  }
};
</script>

<style scoped>
.paypal-button-container {
  width: 100%;
  max-width: 350px;
  margin: 0 auto;
}
</style>