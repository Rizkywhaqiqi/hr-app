<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name'];

    /**
     * Karyawan yang bekerja di lokasi ini.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}