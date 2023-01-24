<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    protected $allowInclude = ['posts', 'posts.user'];

    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // $query hace referencia a la consulta que estamos haciendo en el controlador--- Category::included()->findOrFail($id);
    public function scopeIncluded(Builder $query)
    {
        if(empty($this->allowInclude) ||empty(request('included'))){
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
}
