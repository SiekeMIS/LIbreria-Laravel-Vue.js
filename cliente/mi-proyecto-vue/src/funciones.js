import Swal from "sweetalert2";
import axios from "axios";

export function mostrarAlerta(titulo, icono, foco = '') {
    if (foco !== '') {
        document.getElementById(foco).focus();
    }
    Swal.fire({
        title: titulo,
        icon: icono,
        customClass: {
            confirmButton: 'btn btn-primary',
            popup: 'animated zoomIn'
        },
        buttonsStyling: false,
    });
}

export async function mostrarConfirmacion(id, titulo) {
    try {
        const url = `http://127.0.0.1:8000/api/libros/${id}`;
        
        const result = await Swal.fire({
            title: `¿Eliminar "${titulo}"?`,
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        });

        if (result.isConfirmed) {
            const response = await axios.delete(url);
            
            if (response.data.success) {
                mostrarAlerta('Libro eliminado correctamente', 'success');
                return true;
            }
            throw new Error(response.data.message || 'Error al eliminar');
        }
        return false;
    } catch (error) {
        console.error('Error al eliminar:', error);
        mostrarAlerta(error.response?.data?.message || 'Error al eliminar el libro', 'error');
        return false;
    }
}

// Esta función ya no es necesaria si usas la versión actualizada de mostrarConfirmacion
// export function enviarPeticionEliminar() {...}