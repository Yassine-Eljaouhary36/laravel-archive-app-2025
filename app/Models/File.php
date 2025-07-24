<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

        protected $fillable = [
        'box_id',
        'file_number',
        'symbol',
        'year_of_opening',
        'judgment_number',
        'judgment_date',
        'order',
        'remark'
    ];

    public function box()
    {
        return $this->belongsTo(Box::class);
    }
}
