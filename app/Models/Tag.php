<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\MockObject\Api;

class Tag extends Model
{
    use HasFactory, ApiTrait;

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
