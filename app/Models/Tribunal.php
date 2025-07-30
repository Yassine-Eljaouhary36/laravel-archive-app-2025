<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tribunal extends Model
{
    use HasFactory;

    protected $table = 'tribunaux';

    protected $fillable = [
        'tribunal',
        'circonscription_judiciaire',
        'active',
        'centres_de_conservation',
    ];

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }

}
