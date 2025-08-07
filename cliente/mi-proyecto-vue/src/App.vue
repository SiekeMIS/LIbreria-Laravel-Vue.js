<template>
  <!-- Barra de navegación principal -->
  <nav class="navbar navbar-expand-lg bg-info bg-opacity-25">
    <div class="container-fluid">
      <!-- Logo/Marca de la aplicación -->
      <a class="navbar-brand" href="#">Libros</a>
      
      <!-- Botón para menú colapsable en móviles -->
      <button 
        class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbarSupportedContent" 
        aria-controls="navbarSupportedContent" 
        aria-expanded="false" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- Contenido de la barra de navegación -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Menú de navegación -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <!-- Elemento del menú - Inicio -->
          <li class="nav-item">
            <router-link 
              to="/" 
              class="nav-link active" 
              aria-current="page" 
              href="#"
            >
              Home
            </router-link>
          </li>
          
          <!-- Elemento del menú - Crear Libros -->
          <li class="nav-item">
            <router-link 
              to="/create" 
              class="nav-link" 
              href="#"
            >
              Crear Libros
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Contenedor principal donde se renderizarán las vistas -->
  <div class="container-fluid mt-3">
    <router-view/>
  </div>
</template>

<script>
// No se requieren imports específicos para este componente básico
export default {
  name: 'App', // Nombre del componente principal
  
  // Puedes añadir lógica aquí si es necesario
  // Por ejemplo, para manejar el estado de autenticación
  data() {
    return {
      // Datos reactivos si son necesarios
    }
  },
  
  methods: {
    // Métodos adicionales si son requeridos
  },
  
  // Hooks del ciclo de vida
  mounted() {
    // Código a ejecutar cuando el componente se monta
  }
}
</script>

<!-- Estilos específicos para el componente -->
<style scoped>
/* Estilos para la barra de navegación */
.navbar {
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 0.75rem 1rem;
}

.navbar-brand {
  font-weight: 600;
  font-size: 1.5rem;
  color: #0d6efd; /* Color azul de Bootstrap */
}

/* Estilos para los enlaces de navegación */
.nav-link {
  font-weight: 500;
  padding: 0.5rem 1rem;
  transition: all 0.3s ease;
  border-radius: 0.25rem;
}

.nav-link:hover {
  background-color: rgba(13, 110, 253, 0.1);
  color: #0d6efd;
}

/* Estilo para el enlace activo */
.router-link-exact-active {
  color: #0d6efd;
  font-weight: 600;
}

/* Contenedor principal */
.container-fluid {
  padding: 0 2rem;
}

/* Responsive: ajustes para pantallas pequeñas */
@media (max-width: 768px) {
  .navbar-collapse {
    padding-top: 1rem;
  }
  
  .container-fluid {
    padding: 0 1rem;
  }
}
</style>

<!-- Comentarios adicionales:
1. Este componente sirve como layout principal de la aplicación
2. Incluye:
   - Barra de navegación con menú
   - Contenedor para las vistas dinámicas (router-view)
3. Características:
   - Responsive (se adapta a móviles)
   - Estilos modernos con transiciones
   - Integración con Vue Router
4. Mejoras posibles:
   - Menú desplegable para más opciones
   - Indicador visual de ruta activa
   - Soporte para temas oscuros/claros
   - Componente de búsqueda
   - Estado de usuario/logout
-->