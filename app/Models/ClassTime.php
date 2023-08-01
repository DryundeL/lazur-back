<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassTime extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'time_start',
        'time_end',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
    ];

    /**
     * Return unique key for redis
     *
     * @param int $id
     * @return string
     */
    public static function getCacheKey(int $id): string
    {
        return 'class_time_' . $id;
    }

    /**
     * The schedules that belong to the class times.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
