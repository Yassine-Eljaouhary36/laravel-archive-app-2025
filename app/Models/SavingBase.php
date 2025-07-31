<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingBase extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'description',
        'file_type_id'
    ];


    public function fileType()
    {
        return $this->belongsTo(FileType::class);
    }

    // Add this relationship
    public function boxes()
    {
        return $this->hasMany(Box::class);
    }

}
