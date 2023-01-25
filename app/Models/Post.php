<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'name',
        'slug', 
        'stract',
        'body',
        'status',
        'category_id',
        'user_id',
    ];

    // Relaciones del modelo 
    protected $allowInclude = ['category', 'tags', 'images', 'user'];
    // Columnas de la tabla categories
    protected $allowFilter = ['id', 'name', 'slug'];
    // Columnas por las vuales se pueden ordenar las consultas
    protected $allowSort = ['id', 'name', 'slug'];

    use HasFactory, ApiTrait;

    const BORRADOR = 0;
    const PUBLICADO = 1;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

}
