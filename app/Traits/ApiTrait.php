<?php

namespace App\Traits;
use Illuminate\Contracts\Database\Eloquent\Builder;

 trait ApiTrait{
    
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
            /* Si el primer caracter de la variable es un - significa que debemos ordenar la consulta de forma descendente 
            asi que camviamos el valor de $order y le quitamos el signo - a la consulta para que el parametro de orden quede limpio */
            if(substr($sortfield, 0, 1) == '-'){
                $orden = 'DESC';
                $sortfield = substr($sortfield, 1);
            }
            // Ejecutar la consulta.
            if($allowSort->contains($sortfield)){
                $query->OrderBy($sortfield, $orden);
            }
        }
    }

    // Devolver los resultados paginados o no 
    public function scopeGetOrPagination(Builder $query)
    {
        if(request('perPag')){
            $perPag = intval(request('perPag'));
            if($perPag){
                return $query->paginate($perPag);
            }
        }
        return $query->get();
    }
 }