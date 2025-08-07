<?php

namespace App\Models; // Define el espacio de nombres para los modelos de la aplicación

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait para usar factories en pruebas
use Illuminate\Database\Eloquent\Model; // Clase base para modelos Eloquent

class Libro extends Model // Define el modelo Libro que extiende de Model
{
    use HasFactory; // Habilita funcionalidad de factories para este modelo

    protected $table = 'libros'; // Especifica el nombre de la tabla en la base de datos (opcional si sigue convención de nombres)

    protected $fillable = [ // Campos que pueden ser asignados masivamente (mass assignment)
        'titulo',       // - Título del libro
        'autor',        // - Autor del libro
        'fecha_publicacion' // - Fecha de publicación del libro
    ];
    
    // Nota: Los campos $hidden (ocultos en serialización) y $casts (conversión de tipos)
    // no están definidos pero podrían añadirse según necesidades
}