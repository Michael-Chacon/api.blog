<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];
    // Relaciones del modelo 
    protected $allowInclude = ['posts', 'posts.user'];
    // Columnas de la tabla categories
    protected $allowFilter = ['id', 'name', 'slug'];
    // Columnas por las vuales se pueden ordenar las consultas
    protected $allowSort = ['id', 'name', 'slug'];

    use HasFactory, ApiTrait;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
