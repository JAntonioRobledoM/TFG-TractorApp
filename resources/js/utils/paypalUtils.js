// resources/js/utils/paypalUtils.js

/**
 * Utilidades para trabajar con PayPal en modo sandbox
 */
export default {
    /**
     * Determina si el sistema está en modo sandbox
     * @returns {boolean} true si estamos en modo sandbox, false en caso contrario
     */
    isSandboxMode() {
        // Esta información debería venir del backend, normalmente a través de props
        // Por ahora asumimos que podemos acceder a una variable global o prop
        return window.paypalConfig?.mode === 'sandbox';
    },

    /**
     * Muestra instrucciones para iniciar sesión en PayPal según el entorno
     * @param {string} userType - 'buyer' o 'seller'
     * @param {string} email - Email de la cuenta de PayPal
     */
    showLoginInstructions(userType, email) {
        if (this.isSandboxMode()) {
            const message = `
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500 mb-4">
                    <h4 class="font-medium text-blue-700">Instrucciones para PayPal Sandbox:</h4>
                    <p class="text-sm mt-2">Utiliza las siguientes credenciales para iniciar sesión en PayPal durante el proceso de pago:</p>
                    <div class="mt-2 p-2 bg-white rounded">
                        <p><strong>Email:</strong> ${email}</p>
                        <p><strong>Contraseña:</strong> Consulta la contraseña en el Panel de Desarrollador de PayPal</p>
                    </div>
                    <p class="text-xs mt-2 italic">Nota: Estas son cuentas de prueba. No se realizarán cargos reales.</p>
                </div>
            `;
            
            // Crear un elemento div temporal
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = message;
            
            // Añadir al DOM - esto debería adaptarse según cómo quieras mostrarlo
            // Por ejemplo, podrías usar un modal o añadirlo a un contenedor específico
            document.body.appendChild(tempDiv);
            
            // También podrías devolver el HTML para que lo use el componente
            return message;
        } else {
            // En producción, solo muestra un mensaje informativo
            return `
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500 mb-4">
                    <p>Serás redirigido a PayPal para completar ${userType === 'buyer' ? 'el pago' : 'recibir el pago'}.</p>
                </div>
            `;
        }
    },

    /**
     * Obtiene una cuenta de sandbox para un usuario específico
     * @param {Object} accounts - Objeto con las cuentas configuradas
     * @param {number} userId - ID del usuario
     * @param {string} defaultAccount - Cuenta por defecto si no hay asignación específica
     * @returns {string} Email de la cuenta de PayPal
     */
    getSandboxAccount(accounts, userId, defaultAccount) {
        return accounts[userId] || defaultAccount;
    }
};