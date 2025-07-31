<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'active'];

    public function savingBases()
    {
        return $this->hasMany(SavingBase::class);
    }


}
