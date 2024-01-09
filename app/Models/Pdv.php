<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pdv extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }

    public function horaries(): BelongsToMany
    {
        return $this->belongsToMany(Horary::class, 'detail_horaries');
    }

    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }

}
