<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discipline extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'hours',
    ];

    /**
     * The speciality that belong to the Group.
     */
    public function specialities(): BelongsToMany
    {
        return $this->belongsToMany(Speciality::class, 'speciality_disciplines')->withTimestamps();
    }

    /**
     * Return unique key for redis
     *
     * @param int $id
     * @return string
     */
    public static function getCacheKey(int $id): string
    {
        return 'discipline_' . $id;
    }
}
