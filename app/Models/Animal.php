<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'species',
        'born_at',
        'is_predator',
        'image_path',
        'enclosure_id',
    ];

    protected $casts = [
        'is_predator' => 'boolean',
        'born_at' => 'datetime',
    ];

    public function enclosure()
    {
        return $this->belongsTo(Enclosure::class);
    }
}
