<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name'];

    /**
     * Karyawan yang memegang jabatan ini.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}