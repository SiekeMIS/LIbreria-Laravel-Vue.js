<?php

namespace App\Http\Controllers\Api; // Define el espacio de nombres para el controlador API

use App\Http\Controllers\Controller; // Clase base para controladores
use App\Models\Libro; // Modelo Eloquent para la tabla libros
use Illuminate\Http\Request; // Para manejar peticiones HTTP
use Illuminate\Support\Facades\Validator; // Para validar datos de entrada
use Carbon\Carbon; // Librería para manejo de fechas (no se usa actualmente)

class LibroController extends Controller // Controlador para gestionar libros
{
    /**
     * Obtener todos los libros (GET /api/libros)
     */
    public function index()
    {
        $libros = Libro::all(); // Consulta todos los libros de la base de datos
        
        return response()->json([ // Retorna respuesta JSON
            'success' => true, // Indicador de éxito
            'data' => $libros, // Array con los libros
            'message' => 'Libros obtenidos correctamente' // Mensaje descriptivo
        ]);
    }

    /**
     * Crear un nuevo libro (POST /api/libros)
     */
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255', // Título obligatorio
            'autor' => 'required|string|max:255', // Autor obligatorio
            'fecha_publicacion' => 'required|date' // Fecha válida obligatoria
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'success' => false, // Indicador de error
                'message' => 'Error de validación', // Mensaje de error
                'errors' => $validator->errors() // Detalles de los errores
            ], 422); // Código HTTP 422 (Validación fallida)
        }

        // Crear el libro en la base de datos
        $libro = Libro::create([
            'titulo' => $request->titulo, // Asigna título
            'autor' => $request->autor, // Asigna autor
            'fecha_publicacion' => $request->fecha_publicacion // Asigna fecha
        ]);

        // Retorna respuesta exitosa
        return response()->json([
            'success' => true, // Indicador de éxito
            'data' => $libro, // Datos del libro creado
            'message' => 'Libro creado exitosamente' // Mensaje de confirmación
        ], 201); // Código HTTP 201 (Recurso creado)
    }

    /**
     * Mostrar un libro específico (GET /api/libros/{id})
     */
    public function show($id)
    {
        $libro = Libro::find($id); // Busca el libro por ID

        // Si no encuentra el libro
        if (!$libro) {
            return response()->json([
                'success' => false, // Indicador de error
                'message' => 'Libro no encontrado' // Mensaje de error
            ], 404); // Código HTTP 404 (No encontrado)
        }

        // Retorna el libro encontrado
        return response()->json([
            'success' => true, // Indicador de éxito
            'data' => $libro, // Datos del libro
            'message' => 'Libro obtenido correctamente' // Mensaje de confirmación
        ]);
    }

    /**
     * Eliminar un libro (DELETE /api/libros/{id})
     */
    public function delete($id)
    {
        $libro = Libro::find($id); // Busca el libro por ID

        // Si no encuentra el libro
        if (!$libro) {
            return response()->json([
                'success' => false, // Indicador de error
                'message' => 'Libro no encontrado' // Mensaje de error
            ], 404); // Código HTTP 404 (No encontrado)
        }

        $libro->delete(); // Elimina el libro de la base de datos

        // Retorna confirmación de eliminación
        return response()->json([
            'success' => true, // Indicador de éxito
            'message' => 'Libro eliminado correctamente', // Mensaje de confirmación
            'status' => 200 // Código HTTP 200 (OK)
        ]);
    }

    /**
     * Actualización completa de un libro (PUT /api/libros/{id})
     */
    public function update(Request $request, $id)
    {
        $libro = Libro::find($id); // Busca el libro por ID

        // Si no encuentra el libro
        if (!$libro) {
            return response()->json([
                'success' => false, // Indicador de error
                'message' => 'Libro no encontrado' // Mensaje de error
            ], 404); // Código HTTP 404 (No encontrado)
        }

        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255', // Título obligatorio
            'autor' => 'required|string|max:255', // Autor obligatorio
            'fecha_publicacion' => 'required|date' // Fecha válida obligatoria
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'success' => false, // Indicador de error
                'message' => 'Error de validación', // Mensaje de error
                'errors' => $validator->errors() // Detalles de los errores
            ], 422); // Código HTTP 422 (Validación fallida)
        }

        // Actualiza todos los campos del libro
        $libro->update([
            'titulo' => $request->titulo, // Actualiza título
            'autor' => $request->autor, // Actualiza autor
            'fecha_publicacion' => $request->fecha_publicacion // Actualiza fecha
        ]);

        // Retorna el libro actualizado
        return response()->json([
            'success' => true, // Indicador de éxito
            'data' => $libro, // Datos del libro actualizado
            'message' => 'Libro actualizado correctamente' // Mensaje de confirmación
        ]);
    }

    /**
     * Actualización parcial de un libro (PATCH /api/libros/{id})
     */
    public function updatePartial(Request $request, $id)
    {
        $libro = Libro::find($id); // Busca el libro por ID

        // Si no encuentra el libro
        if (!$libro) {
            return response()->json([
                'success' => false, // Indicador de error
                'message' => 'Libro no encontrado' // Mensaje de error
            ], 404); // Código HTTP 404 (No encontrado)
        }

        // Validar solo los campos que vengan en la petición
        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|string|max:255', // Título opcional pero si viene debe ser válido
            'autor' => 'sometimes|required|string|max:255', // Autor opcional pero si viene debe ser válido
            'fecha_publicacion' => 'sometimes|required|date' // Fecha opcional pero si viene debe ser válida
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'success' => false, // Indicador de error
                'message' => 'Error de validación', // Mensaje de error
                'errors' => $validator->errors() // Detalles de los errores
            ], 422); // Código HTTP 422 (Validación fallida)
        }

        // Actualiza solo los campos que vienen en la petición
        $libro->update($request->only(['titulo', 'autor', 'fecha_publicacion']));

        // Retorna el libro actualizado
        return response()->json([
            'success' => true, // Indicador de éxito
            'data' => $libro, // Datos del libro actualizado
            'message' => 'Libro actualizado parcialmente' // Mensaje de confirmación
        ]);
    }
}