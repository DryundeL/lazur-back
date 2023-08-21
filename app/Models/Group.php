<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'education_type',
    ];

    /**
     * The schedules that belong to the group.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * The employee that belong to the Group.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * The users that belong to the Group.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)->withTimestamps();
    }

    /**
     * The semesters that belong to the Group.
     */
    public function semesters(): BelongsToMany
    {
        return $this->belongsToMany(Semester::class)->withTimestamps();
    }

    /**
     * The speciality that belong to the Group.
     */
    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class);
    }

    /**
     * Return unique key for redis
     *
     * @param int $id
     * @return string
     */
    public static function getCacheKey(int $id): string
    {
        return 'group_' . $id;
    }

    /**
     * The journal dates that belong to the group.
     */
    public function journalDates(): HasMany
    {
        return $this->hasMany(JournalDate::class);
    }

}
