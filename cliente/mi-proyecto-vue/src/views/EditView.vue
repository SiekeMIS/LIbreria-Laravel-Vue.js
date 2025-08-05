<template>
    <div class="row mt-4">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">Editar Libro</div>
                <div class="card-body">
                    <form @submit.prevent="guardar">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <input type="text" v-model="titulo" class="form-control" maxlength="50" placeholder="Título del libro" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" v-model="autor" class="form-control" maxlength="50" placeholder="Autor del libro" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" v-model="fecha_publicacion" class="form-control" placeholder="Fecha de Publicación" required>
                        </div>
                        <div class="d-grid col-6 mx-auto">
                            <button type="submit" class="btn btn-primary" :disabled="guardando">
                                <i class="fas fa-save"></i> {{ guardando ? 'Guardando...' : 'Guardar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mostrarAlerta } from '../funciones.js';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
    data() {
        return {
            id: 0,
            titulo: '',
            autor: '',
            fecha_publicacion: '',
            url: 'http://127.0.0.1:8000/api/libros',
            guardando: false
        };
    },
    mounted() {
        const route = useRoute();
        this.id = route.params.id;
        this.url = `${this.url}/${this.id}`; // Corregido: no duplicar la URL
        this.cargarLibro();
    },

    methods: {
        cargarLibro() {
            axios.get(this.url).then(response => {
                this.titulo = response.data['titulo'];
                this.autor = response.data['autor'];
                this.fecha_publicacion = response.data['fecha_publicacion'];
            });
        },
        async guardar() {
            // Validaciones
            if(this.titulo.trim() === '') {
                mostrarAlerta('Por favor, ingresa el título del libro.', 'warning');
                return;
            }
            if(this.autor.trim() === '') {
                mostrarAlerta('Por favor, ingresa el autor del libro.', 'warning');
                return;
            }
            if(this.fecha_publicacion.trim() === '') {
                mostrarAlerta('Por favor, selecciona la fecha de publicación.', 'warning');
                return;
            }
            
            this.guardando = true;
            
            const parametros = {
                titulo: this.titulo.trim(),
                autor: this.autor.trim(),
                fecha_publicacion: this.fecha_publicacion.trim()
            };
            
            try {
                console.log('Enviando datos:', parametros); // Para debugging
                
                const response = await fetch(this.url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Agregar token si usas autenticación
                        // 'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(parametros)
                });
                
                console.log('Response status:', response.status); // Para debugging
                console.log('Response headers:', response.headers); // Para debugging
                
                // Verificar si la respuesta fue exitosa
                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Error del servidor:', errorData);
                    throw new Error(`Error ${response.status}: ${errorData.message || 'Error desconocido'}`);
                }
                
                const data = await response.json();
                console.log('Respuesta exitosa:', data); // Para debugging
                
                // Verificar estructura de respuesta según tu API
                if (data.success !== false) { // Ajustar según tu API
                    mostrarAlerta('Libro actualizado exitosamente', 'success');
                    // Limpiar formulario
                    this.titulo = '';
                    this.autor = '';
                    this.fecha_publicacion = '';
                } else {
                    throw new Error(data.message || 'Error al guardar el libro');
                }
                
            } catch (error) {
                console.error('Error completo:', error);
                
                // Manejo específico de errores
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    mostrarAlerta('Error de conexión. Verifica que el servidor esté corriendo.', 'error');
                } else if (error.message.includes('422')) {
                    mostrarAlerta('Datos inválidos. Verifica los campos.', 'error');
                } else if (error.message.includes('500')) {
                    mostrarAlerta('Error interno del servidor.', 'error');
                } else {
                    mostrarAlerta(error.message || 'Error al guardar el libro', 'error');
                }
            } finally {
                this.guardando = false;
            }
        }
    }
}
</script>