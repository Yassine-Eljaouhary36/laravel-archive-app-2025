<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    protected $fillable = [
        'saving_base_number',
        'box_number',
        'file_type',
        'type',
        'year_of_judgment',
        'total_files',
        'user_id',
        'validated_by',
        'validated_at',
        'tribunal_id'
    ];

    protected $dates = ['validated_at'];

    public function files()
    {
        return $this->hasMany(File::class)->orderBy('order');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function isValidated()
    {
        return !is_null($this->validated_at);
    }

    public function tribunal()
    {
        return $this->belongsTo(Tribunal::class);
    }

}
