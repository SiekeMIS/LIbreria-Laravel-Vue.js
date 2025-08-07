// Importación de librerías externas
import Swal from "sweetalert2"; // Biblioteca para alertas visuales
import axios from "axios"; // Cliente HTTP para peticiones a la API

/**
 * Muestra una alerta visual personalizada
 * @param {string} titulo - Texto principal de la alerta
 * @param {string} icono - Tipo de icono ('success', 'error', 'warning', 'info', 'question')
 * @param {string} foco - ID del elemento HTML al que enfocar después (opcional)
 */
export function mostrarAlerta(titulo, icono, foco = '') {
    // Si se especificó un elemento para enfocar
    if (foco !== '') {
        document.getElementById(foco).focus(); // Enfoca el elemento
    }
    
    // Configura y muestra la alerta
    Swal.fire({
        title: titulo, // Título de la alerta
        icon: icono,   // Tipo de icono
        customClass: { // Clases CSS personalizadas
            confirmButton: 'btn btn-primary', // Estilo para botón de confirmación
            popup: 'animated zoomIn'         // Animación de entrada
        },
        buttonsStyling: false, // Desactiva estilos por defecto
    });
}

/**
 * Muestra un diálogo de confirmación para eliminar un libro y maneja la acción
 * @param {number|string} id - ID del libro a eliminar
 * @param {string} titulo - Título del libro para mostrar en el mensaje
 * @returns {Promise<boolean>} - True si se confirmó y completó la eliminación, False si se canceló
 */
export async function mostrarConfirmacion(id, titulo) {
    try {
        const url = `http://127.0.0.1:8000/api/libros/${id}`; // URL de la API
        
        // Muestra el diálogo de confirmación
        const result = await Swal.fire({
            title: `¿Eliminar "${titulo}"?`, // Título personalizado
            text: "Esta acción no se puede deshacer", // Texto adicional
            icon: 'warning', // Icono de advertencia
            showCancelButton: true, // Muestra botón de cancelar
            confirmButtonText: 'Sí, eliminar', // Texto botón confirmación
            cancelButtonText: 'Cancelar', // Texto botón cancelar
            customClass: { // Clases CSS personalizadas
                confirmButton: 'btn btn-danger me-2', // Estilo rojo para confirmar
                cancelButton: 'btn btn-secondary'      // Estilo gris para cancelar
            },
            buttonsStyling: false // Desactiva estilos por defecto
        });

        // Si el usuario confirma la eliminación
        if (result.isConfirmed) {
            // Envía petición DELETE a la API
            const response = await axios.delete(url);
            
            // Verifica si la eliminación fue exitosa
            if (response.data.success) {
                mostrarAlerta('Libro eliminado correctamente', 'success');
                return true; // Retorna éxito
            }
            // Si la API no retorna success=true
            throw new Error(response.data.message || 'Error al eliminar');
        }
        return false; // Retorna cancelación
    } catch (error) {
        console.error('Error al eliminar:', error);
        
        // Muestra mensaje de error detallado si está disponible
        const errorMessage = error.response?.data?.message || 
                           error.message || 
                           'Error al eliminar el libro';
        
        mostrarAlerta(errorMessage, 'error');
        return false; // Retorna fallo
    }
}

/* Notas adicionales:
1. La función mostrarAlerta es reusable para cualquier tipo de notificación
2. mostrarConfirmacion combina:
   - Diálogo de confirmación
   - Petición HTTP
   - Manejo de errores
   - Feedback visual
3. Buenas prácticas implementadas:
   - Validación de respuestas del servidor
   - Manejo detallado de errores
   - Retorno de valores booleanos para control de flujo
   - Estilos personalizados consistentes
4. Posibles mejoras:
   - Internacionalización de textos
   - Posibilidad de personalizar textos de confirmación
   - Manejo de headers de autenticación
   - Opción para acciones alternativas además de DELETE
*/