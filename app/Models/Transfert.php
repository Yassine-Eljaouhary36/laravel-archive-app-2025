<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;
    protected $casts = [
        'transfert_date' => 'date',
    ];
    protected $fillable = [
        'transfert_number',
        'tribunal_id',
        'transfert_date',
        'notes'
    ];

    public function tribunal()
    {
        return $this->belongsTo(Tribunal::class);
    }

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }
}
