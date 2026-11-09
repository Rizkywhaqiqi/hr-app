<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name'];

    /**
     * Karyawan yang memakai brand ini.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}