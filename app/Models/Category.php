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
    // Columnas por las vuales se pueden ordenar las consultas
    protected $allowSort = ['id', 'name', 'slug'];

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

    // Ordenar las conuslta por parametros 
    public function scopeSort(Builder $query)
    {
        if(empty($this->allowSort) || empty(request('sort'))){
            return;
        }

        $parameters = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);

        foreach($parameters as $sortfield){
            // el orden por defento de las consultas en ascendente
            $orden = 'ASC';
            // Si el primer caracter de la variable es un - significa que debemos ordenar la consulta de forma descendente asi que camviamos el valor de $order y le quemos el signo - a la consulta para que el parametro de orden quede limpio 
            if(substr($sortfield, 0, 1) == '-'){
                $orden = 'DESC';
                $sortfield = substr($sortfield, 1);
            }
            
            if($allowSort->contains($sortfield)){
                $query->OrderBy($sortfield, $orden);
            }
        }
    }
}
