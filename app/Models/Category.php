<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];
    // Relaciones del modelo 
    protected $allowInclude = ['posts', 'posts.user'];
    // Columnas de la tabla categories
    protected $allowFilter = ['id', 'name', 'slug'];

    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /* 
    Scope para añadir relaciones a la consulta de las categorias 
    $query hace referencia a la consulta que estamos haciendo en el controlador--- Category::included()->findOrFail($id);
    */
    public function scopeIncluded(Builder $query)
    {
        if(empty($this->allowInclude) || empty(request('included'))){
            return;
        }
        // request es un helper que trae los parametros que viene por la url
        $relations = explode(',', request('included')); //['post', 'relationship2']
        $allowInclude = collect($this->allowInclude);

        foreach($relations as $key => $relationship)
        {
            if(!$allowInclude->contains($relationship)){
                unset($relations[$key]);
            }
        }
        $query->with($relations);
    }

    // scope para añadir paremetros a la consulta
    public function scopeFilter(Builder $query)
    {
        // validar cuando no hay parametros de busqueda en la url
        if(empty($this->allowFilter) || empty(request('filter'))){
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach($filters as $colum => $value){
            if($allowFilter->contains($colum)){
                $query->where($colum,'LIKE', '%' . $value . '%');
            }
        }
    }
}
