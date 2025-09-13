<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Enclosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'limit',
        'feeding_at',
        'is_predator',
    ];

    protected $casts = [
        'is_predator' => 'boolean',
        'feeding_at' => 'string',
    ];

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
