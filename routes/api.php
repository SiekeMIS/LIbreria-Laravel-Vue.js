<?php

use Illuminate\Http\Request; // Importa la clase Request para manejar peticiones HTTP
use Illuminate\Support\Facades\Route; // Importa la fachada Route para definir rutas
use App\Http\Controllers\Api\libroController; // Importa el controlador de libros

// Ruta GET para obtener todos los libros
// URL: /libros
// Método: index() del libroController
Route::get('/libros', [libroController::class, 'index']);

// Ruta GET para obtener un libro específico por ID
// URL: /libros/{id} (ej: /libros/1)
// Método: show() del libroController
Route::get('/libros/{id}', [libroController::class, 'show']);

// Ruta POST para crear un nuevo libro
// URL: /libros
// Método: store() del libroController
Route::post('/libros', [libroController::class, 'store']);

// Ruta PUT para actualización completa de un libro por ID
// URL: /libros/{id}
// Método: update() del libroController
Route::put('/libros/{id}', [libroController::class, 'update']);

// Ruta PATCH para actualización parcial de un libro por ID
// URL: /libros/{id}
// Método: updatePartial() del libroController
Route::patch('/libros/{id}', [libroController::class, 'updatePartial']);

// Ruta DELETE para eliminar un libro por ID
// URL: /libros/{id}
// Método: delete() del libroController
Route::delete('/libros/{id}', [libroController::class, 'delete']);

/* 
Notas adicionales:
1. Las rutas siguen el estándar RESTful para recursos:
   - GET /libros → Listar todos
   - GET /libros/{id} → Mostrar uno
   - POST /libros → Crear
   - PUT/PATCH /libros/{id} → Actualizar
   - DELETE /libros/{id} → Eliminar

2. Convenciones recomendadas:
   - Nombre del controlador en PascalCase (LibroController)
   - Rutas en plural (/libros)
   - Parámetros en singular (/libros/{id})

3. Para agrupar estas rutas podrías usar:
   Route::resource('libros', libroController::class);
   que crea automáticamente todas estas rutas estándar
*/