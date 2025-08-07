<template>
    <!-- Contenedor principal del formulario -->
    <div class="row mt-4">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <!-- Encabezado de la tarjeta -->
                <div class="card-header bg-dark text-white text-center">Crear Libro</div>
                
                <!-- Cuerpo de la tarjeta con el formulario -->
                <div class="card-body">
                    <!-- Formulario con prevent para evitar submit tradicional -->
                    <form @submit.prevent="guardar">
                        
                        <!-- Campo para el título del libro -->
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <input type="text" v-model="titulo" class="form-control" maxlength="50" 
                                   placeholder="Título del libro" required>
                        </div>
                        
                        <!-- Campo para el autor del libro -->
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" v-model="autor" class="form-control" maxlength="50" 
                                   placeholder="Autor del libro" required>
                        </div>
                        
                        <!-- Campo para la fecha de publicación -->
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" v-model="fecha_publicacion" class="form-control" 
                                   placeholder="Fecha de Publicación" required>
                        </div>
                        
                        <!-- Botón de submit con estado de carga -->
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
// Importación de función auxiliar para mostrar alertas
import { mostrarAlerta } from '../funciones.js';

export default {
    // Datos reactivos del componente
    data() {
        return {
            titulo: '',              // Almacena el título del libro
            autor: '',               // Almacena el autor del libro
            fecha_publicacion: '',   // Almacena la fecha de publicación
            url: 'http://127.0.0.1:8000/api/libros',  // Endpoint de la API
            guardando: false        // Controla el estado de carga del botón
        };
    },
    methods: {
        // Método para guardar el libro
        async guardar() {
            // Validación del título
            if(this.titulo.trim() === '') {
                mostrarAlerta('Por favor, ingresa el título del libro.', 'warning');
                return;
            }
            
            // Validación del autor
            if(this.autor.trim() === '') {
                mostrarAlerta('Por favor, ingresa el autor del libro.', 'warning');
                return;
            }
            
            // Validación de la fecha
            if(this.fecha_publicacion.trim() === '') {
                mostrarAlerta('Por favor, selecciona la fecha de publicación.', 'warning');
                return;
            }
            
            this.guardando = true;  // Activa estado de carga
            
            // Preparamos los datos para enviar
            const parametros = {
                titulo: this.titulo.trim(),
                autor: this.autor.trim(),
                fecha_publicacion: this.fecha_publicacion.trim()
            };
            
            try {
                console.log('Enviando datos:', parametros); // Log para depuración
                
                // Enviamos la petición POST a la API
                const response = await fetch(this.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Para autenticación (si es necesaria):
                        // 'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(parametros)
                });
                
                console.log('Response status:', response.status); // Log de estado
                
                // Manejo de errores HTTP
                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Error del servidor:', errorData);
                    throw new Error(`Error ${response.status}: ${errorData.message || 'Error desconocido'}`);
                }
                
                // Procesamos la respuesta exitosa
                const data = await response.json();
                console.log('Respuesta exitosa:', data);
                
                if (data.success !== false) {
                    mostrarAlerta('Libro guardado exitosamente', 'success');
                    // Limpiamos el formulario
                    this.titulo = '';
                    this.autor = '';
                    this.fecha_publicacion = '';
                } else {
                    throw new Error(data.message || 'Error al guardar el libro');
                }
                
            } catch (error) {
                console.error('Error completo:', error);
                
                // Manejo específico de diferentes tipos de errores
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
                this.guardando = false; // Desactiva estado de carga
            }
        }
    }
}
</script>

<!-- Estilos adicionales podrían ir aquí -->
<style scoped>
/* Ejemplo de estilos específicos para este componente */
.card {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.input-group-text {
    min-width: 40px;
    justify-content: center;
}
</style>