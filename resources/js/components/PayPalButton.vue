<!-- resources/js/Components/PayPalButton.vue -->
<template>
    <div class="paypal-button-container">
        <!-- Contenedor para el botón de PayPal -->
        <div v-if="!paymentCompleted" ref="paypalButtonContainer" class="mt-4"></div>

        <!-- Mensaje de éxito después del pago -->
        <div v-else class="mt-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-medium">¡Pago completado correctamente!</span>
            </div>
            <p class="mt-1 text-sm">ID de transacción: {{ transactionId }}</p>
        </div>

        <!-- Error al cargar PayPal -->
        <div v-if="loadError" class="mt-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
            <p>{{ loadError }}</p>
            <button @click="retryLoading" class="mt-2 rounded bg-red-500 px-3 py-1 text-sm text-white hover:bg-red-600">Reintentar</button>
        </div>

        <!-- Modo sandbox (solo se muestra en desarrollo) -->
        <div v-if="isSandboxMode" class="mt-2 rounded border border-yellow-400 bg-yellow-100 px-4 py-2 text-xs text-yellow-700">
            Modo Sandbox activo - Los pagos son de prueba
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        amount: {
            type: [Number, String],
            required: true,
        },
        currency: {
            type: String,
            default: 'EUR',
        },
        itemName: {
            type: String,
            default: 'Pago por maquinaria agrícola',
        },
        receiverEmail: {
            type: String,
            required: true,
        },
        receiverId: {
            type: [Number, String],
            required: true,
        },
        requestId: {
            type: [Number, String],
            required: true,
        },
        isSandbox: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            paymentCompleted: false,
            transactionId: '',
            scriptLoaded: false,
            loadError: null,
            clientId: null,
            loadAttempts: 0,
            isSandboxMode: false,
        };
    },

    computed: {
        senderId() {
            // Asumimos que el ID del usuario actual está disponible a través de alguna forma
            // Por ejemplo, por props o almacenado en algún lugar
            return this.$page?.props?.auth?.user?.id || null;
        },
    },

    mounted() {
        this.loadPayPalScript();
    },

    methods: {
        async loadPayPalScript() {
            try {
                this.loadError = null;
                this.loadAttempts++;

                // Si ya hay demasiados intentos, mostrar error permanente
                if (this.loadAttempts > 3) {
                    this.loadError = 'No se pudo cargar PayPal después de varios intentos. Por favor, inténtalo más tarde.';
                    console.error('Demasiados intentos fallidos para cargar PayPal');
                    return;
                }

                // Comprobar si el script ya está cargado
                if (document.querySelector('script[src*="paypal.com/sdk/js"]') && window.paypal) {
                    this.scriptLoaded = true;
                    this.renderPayPalButton();
                    return;
                }

                // Primero, obtener la configuración de PayPal desde el servidor
                const response = await axios.get('/paypal/config');
                this.clientId = response.data.clientId;
                const currency = response.data.currency || this.currency;
                this.isSandboxMode = response.data.mode === 'sandbox';

                console.log('PayPal config:', { clientId: this.clientId, currency, mode: response.data.mode });

                if (!this.clientId) {
                    this.loadError =
                        'No se pudo obtener la configuración de PayPal. Comprueba que PAYPAL_CLIENT_ID está configurado en el archivo .env';
                    return;
                }

                // Crear y cargar el script de PayPal
                const script = document.createElement('script');
                // Añadimos intent=capture para que funcione en sandbox
                script.src = `https://www.paypal.com/sdk/js?client-id=${this.clientId}&currency=${currency}&intent=capture`;
                script.async = true;

                script.onload = () => {
                    console.log('Script de PayPal cargado correctamente');
                    this.scriptLoaded = true;
                    this.renderPayPalButton();
                };

                script.onerror = (err) => {
                    console.error('Error cargando el script de PayPal:', err);
                    this.loadError = 'Error al cargar PayPal. Por favor, verifica tu conexión a internet.';
                };

                document.body.appendChild(script);
            } catch (error) {
                console.error('Error al obtener la configuración de PayPal:', error);
                this.loadError = 'Error al configurar PayPal. Por favor, inténtalo de nuevo más tarde.';
            }
        },

        retryLoading() {
            this.loadPayPalScript();
        },

        renderPayPalButton() {
            if (!this.scriptLoaded || !window.paypal) {
                console.warn('PayPal no está cargado correctamente');
                this.loadError = 'PayPal no se ha cargado correctamente. Por favor, recarga la página.';
                return;
            }

            const amount = parseFloat(this.amount).toFixed(2);
            console.log('Renderizando botón de PayPal con importe:', amount);

            try {
                window.paypal
                    .Buttons({
                        // Estilo del botón
                        style: {
                            layout: 'vertical',
                            color: 'gold',
                            shape: 'rect',
                            label: 'pay',
                        },

                        // Crear la orden
                        createOrder: (data, actions) => {
                            console.log('Creando orden de PayPal');

                            // Configuración de la orden
                            const orderConfig = {
                                intent: 'CAPTURE',
                                purchase_units: [
                                    {
                                        amount: {
                                            value: amount,
                                            currency_code: this.currency,
                                        },
                                        description: this.itemName,
                                        reference_id: `req_${this.requestId}`,
                                    },
                                ],
                            };

                            // Si no estamos en sandbox, podemos añadir el payee (vendedor)
                            if (!this.isSandboxMode || this.isSandbox === false) {
                                // En producción o cuando se indica explícitamente
                                orderConfig.purchase_units[0].payee = {
                                    email_address: this.receiverEmail,
                                };
                            }

                            return actions.order.create({
                                intent: 'CAPTURE',
                                purchase_units: [
                                    {
                                        amount: {
                                            value: amount,
                                            currency_code: this.currency,
                                        },
                                        description: this.itemName,
                                        // NO incluir payee en sandbox
                                    },
                                ],
                            });
                        },

                        // Cuando el pago se completa correctamente
                        onApprove: (data, actions) => {
                            console.log('Pago aprobado, capturando fondos...');
                            return actions.order.capture().then((details) => {
                                console.log('Pago capturado:', details);

                                // Guardar el ID de la transacción
                                this.transactionId = details.id;

                                // Marcar el pago como completado
                                this.paymentCompleted = true;

                                // Notificar al servidor (sin procesar pagos, solo registrar)
                                this.registerPaymentOnServer(data.orderID, details);

                                // Emitir evento para que el componente padre sepa que el pago se completó
                                this.$emit('payment-completed', {
                                    orderId: data.orderID,
                                    details: details,
                                });
                            });
                        },

                        // Manejo de errores
                        onError: (err) => {
                            console.error('Error en el pago:', err);
                            this.$emit('payment-error', err);
                        },

                        // Si el usuario cancela
                        onCancel: (data) => {
                            console.log('Pago cancelado por el usuario:', data);
                            this.$emit('payment-cancelled', data);
                        },
                    })
                    .render(this.$refs.paypalButtonContainer);

                console.log('Botón de PayPal renderizado correctamente');
            } catch (error) {
                console.error('Error al renderizar el botón de PayPal:', error);
                this.loadError = 'Error al mostrar el botón de PayPal. Por favor, recarga la página.';
            }
        },

        // Registrar el pago en el servidor (sin procesarlo)
        registerPaymentOnServer(orderId, details) {
            try {
                console.log('Registrando pago en el servidor:', { orderId, details });

                const paymentData = {
                    order_id: orderId,
                    request_id: this.requestId,
                    amount: this.amount,
                    currency: this.currency,
                    payment_status: details.status,
                    payer_email: details.payer?.email_address || null,
                    payee_email: this.receiverEmail,
                    payment_details: details,
                };

                axios
                    .post('/paypal/payments', paymentData)
                    .then((response) => {
                        console.log('Pago registrado en el servidor:', response.data);
                    })
                    .catch((error) => {
                        console.error('Error al registrar el pago en el servidor:', error);
                    });
            } catch (error) {
                console.error('Error al preparar datos para registro de pago:', error);
            }
        },
    },
};
</script>

<style scoped>
.paypal-button-container {
    width: 100%;
    max-width: 350px;
    margin: 0 auto;
}
</style>
