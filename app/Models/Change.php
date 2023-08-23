<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Change extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date'
    ];

    /**
     * Return unique key for redis
     *
     * @param int $id
     * @return string
     */
    public static function getCacheKey(int $id): string
    {
        return 'change_' . $id;
    }

    /**
     * The group that belong to the change.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * The class time that belong to the change.
     */
    public function classTime(): BelongsTo
    {
        return $this->belongsTo(ClassTime::class);
    }

    /**
     * The group that belong to the change.
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * The change details that belong to the schedule.
     */
    public function changeDetails(): HasMany
    {
        return $this->hasMany(ChangeDetail::class);
    }
}
