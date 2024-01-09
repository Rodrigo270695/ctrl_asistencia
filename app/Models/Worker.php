<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pdv(): BelongsTo
    {
        return $this->belongsTo(Pdv::class);
    }

    public function assists(): HasMany
    {
        return $this->hasMany(Assist::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

}
