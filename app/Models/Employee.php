<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    /**
     * Kolom yang boleh diisi lewat mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'full_name',
        'brand_id',
        'division_id',
        'location_id',
        'position_id',
        'joined_date',
        'is_active',
        'deactivated_at',
    ];

    /**
     * Relasi yang selalu ikut dimuat agar menghindari N+1.
     *
     * @var list<string>
     */
    protected $with = ['brand', 'division', 'location', 'position'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joined_date' => 'date',
            'is_active' => 'boolean',
            'deactivated_at' => 'datetime',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Route model binding memakai kolom employee_id (bukan id numerik),
     * sehingga URL seperti /employees/KS-001 bisa langsung ter-resolve.
     */
    public function getRouteKeyName(): string
    {
        return 'employee_id';
    }

    /**
     * Filter pencarian (search / brand_id / division_id / location_id / status).
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $q, string $s) {
                $q->where(function (Builder $inner) use ($s) {
                    $inner->where('employee_id', 'like', "%{$s}%")
                        ->orWhere('full_name', 'like', "%{$s}%");
                });
            })
            ->when($filters['brand_id'] ?? null, fn (Builder $q, $b) => $q->where('brand_id', $b))
            ->when($filters['division_id'] ?? null, fn (Builder $q, $d) => $q->where('division_id', $d))
            ->when($filters['location_id'] ?? null, fn (Builder $q, $l) => $q->where('location_id', $l))
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                function (Builder $q) use ($filters) {
                    // Nilai filter: '1' = Aktif, '0' = Nonaktif.
                    $q->where('is_active', (string) $filters['status'] === '1');
                }
            );
    }

    /**
     * Label status manusiawi.
     */
    public function statusLabel(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }
}