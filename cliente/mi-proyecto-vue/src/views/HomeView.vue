<template>
    <!-- Contenedor principal de la tabla de libros -->
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <!-- Tabla responsive -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <!-- Encabezado de la tabla -->
                    <thead>
                        <tr>
                            <th>Título</th>                   <!-- Columna para el título -->
                            <th>Autor</th>                   <!-- Columna para el autor -->
                            <th>Fecha de Publicación</th>    <!-- Columna para la fecha -->
                            <th>Acciones</th>               <!-- Columna para botones de acción -->
                        </tr>
                    </thead>
                    
                    <!-- Cuerpo de la tabla -->
                    <tbody class="table-group-divider">
                        <!-- Mensaje de carga (se muestra mientras loading=true) -->
                        <tr v-if="loading">
                            <td colspan="4" class="text-center">Cargando libros...</td>
                        </tr>
                        
                        <!-- Mensaje cuando no hay libros (se muestra si no hay datos) -->
                        <tr v-else-if="!libros || libros.length === 0">
                            <td colspan="4" class="text-center">No hay libros disponibles</td>
                        </tr>
                        
                        <!-- Iteración sobre cada libro (se muestra cuando hay datos) -->
                        <tr v-for="libro in libros" :key="libro.id">
                            <td>{{ libro.titulo }}</td>                      <!-- Muestra título -->
                            <td>{{ libro.autor }}</td>                      <!-- Muestra autor -->
                            <td>{{ formatFecha(libro.fecha_publicacion) }}</td> <!-- Muestra fecha formateada -->
                            <td>
                                <!-- Botón para editar (redirige a ruta de edición) -->
                                <router-link 
                                    :to="{ name: 'edit', params: { id: libro.id } }" 
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit"></i> Editar
                                </router-link>
                                
                                <!-- Botón para eliminar (dispara método eliminarLibro) -->
                                <button 
                                    class="btn btn-danger btn-sm" 
                                    @click="eliminarLibro(libro.id, libro.titulo)">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
// Importación de axios para peticiones HTTP
import axios from 'axios';
// Importación de funciones auxiliares para mostrar alertas y confirmaciones
import { mostrarAlerta, mostrarConfirmacion } from '../funciones.js';

export default {
    // Datos reactivos del componente
    data() {
        return {
            libros: [],      // Array para almacenar los libros
            loading: true,   // Bandera para estado de carga
            error: null      // Para almacenar mensajes de error
        };
    },
    
    // Hook que se ejecuta cuando el componente se monta en el DOM
    mounted() {
        this.fetchLibros(); // Carga los libros al iniciar el componente
    },
    
    methods: {
        // Método para obtener los libros desde la API
        async fetchLibros() {
            try {
                this.loading = true; // Activa el estado de carga
                const response = await axios.get('http://127.0.0.1:8000/api/libros');
                
                // Depuración: muestra la respuesta de la API
                console.log('Respuesta API:', response.data);
                
                // Asigna los libros según la estructura de la respuesta
                // (compatible con diferentes formatos de respuesta)
                this.libros = response.data.data || response.data.libros || response.data;
                
                // Muestra mensaje si no hay libros
                if (!this.libros || this.libros.length === 0) {
                    mostrarAlerta('No se encontraron libros', 'info');
                }
            } catch (error) {
                console.error('Error al cargar libros:', error);
                mostrarAlerta('Error al cargar los libros: ' + error.message, 'error');
                this.error = error.message;
            } finally {
                this.loading = false; // Desactiva el estado de carga
            }
        },
        
        // Método para formatear fechas
        formatFecha(fecha) {
            if (!fecha) return 'N/A'; // Si no hay fecha, devuelve 'N/A'
            
            try {
                // Formatea la fecha al estilo español
                return new Date(fecha).toLocaleDateString('es-ES', {
                    year: 'numeric',  // Año completo (ej: 2023)
                    month: 'long',   // Mes en texto completo (ej: "enero")
                    day: 'numeric'  // Día del mes (ej: 15)
                });
            } catch (e) {
                console.error('Error formateando fecha:', e);
                return fecha; // Si hay error, devuelve la fecha original
            }
        },
        
        // Método para eliminar un libro
        async eliminarLibro(id, titulo) {
            // Muestra diálogo de confirmación
            const confirmado = await mostrarConfirmacion(
                `¿Estás seguro de eliminar el libro "${titulo}"?`,
                'Esta acción no se puede deshacer'
            );
            
            if (confirmado) {
                try {
                    // Envía petición DELETE a la API
                    await axios.delete(`http://127.0.0.1:8000/api/libros/${id}`);
                    
                    // Muestra mensaje de éxito
                    mostrarAlerta(`Libro "${titulo}" eliminado correctamente`, 'success');
                    
                    // Actualiza la lista de libros
                    await this.fetchLibros();
                } catch (error) {
                    console.error('Error al eliminar libro:', error);
                    mostrarAlerta('Error al eliminar el libro: ' + error.message, 'error');
                }
            }
        }
    }
}
</script>

<!-- Estilos adicionales -->
<style scoped>
.table-responsive {
    margin-top: 20px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table th {
    background-color: #343a40;
    color: white;
    font-weight: 500;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Efecto hover para filas */
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
    transition: background-color 0.2s;
}
</style>