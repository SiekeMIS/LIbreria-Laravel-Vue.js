<template>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Fecha de Publicación</th>
                            <th>Acciones</th> <!-- Columna añadida para los botones -->
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <!-- Mensaje de carga -->
                        <tr v-if="loading">
                            <td colspan="4" class="text-center">Cargando libros...</td>
                        </tr>
                        
                        <!-- Mensaje si no hay libros -->
                        <tr v-else-if="!libros || libros.length === 0">
                            <td colspan="4" class="text-center">No hay libros disponibles</td>
                        </tr>
                        
                        <!-- Lista de libros -->
                        <tr v-for="libro in libros" :key="libro.id">
                            <td>{{ libro.titulo }}</td>
                            <td>{{ libro.autor }}</td>
                            <td>{{ formatFecha(libro.fecha_publicacion) }}</td>
                            <td>
                                <router-link :to="{ name: 'edit', params: { id: libro.id } }" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit"></i> Editar
                                </router-link>
                                <button class="btn btn-danger btn-sm" @click="eliminarLibro(libro.id, libro.titulo)">
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
import axios from 'axios';
import { mostrarAlerta, mostrarConfirmacion } from '../funciones.js';

export default {
    data() {
        return {
            libros: [],
            loading: true,
            error: null
        };
    },
    mounted() {
        this.fetchLibros();
    },
    methods: {
        async fetchLibros() {
            try {
                this.loading = true;
                const response = await axios.get('http://127.0.0.1:8000/api/libros');
                
                // Verifica la estructura de la respuesta
                console.log('Respuesta API:', response.data);
                
                // Ajusta según la estructura real de tu API
                this.libros = response.data.data || response.data.libros || response.data;
                
                if (!this.libros || this.libros.length === 0) {
                    mostrarAlerta('No se encontraron libros', 'info');
                }
            } catch (error) {
                console.error('Error al cargar libros:', error);
                mostrarAlerta('Error al cargar los libros: ' + error.message, 'error');
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },
        formatFecha(fecha) {
            if (!fecha) return 'N/A';
            try {
                // Asegúrate de que la fecha esté en formato válido
                return new Date(fecha).toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                console.error('Error formateando fecha:', e);
                return fecha; // Devuelve la fecha original si no se puede formatear
            }
        },
        async eliminarLibro(id, titulo) {
            mostrarConfirmacion(id, titulo);
            // Actualiza la lista después de eliminar
            await this.fetchLibros();
        }
    }
}
</script>