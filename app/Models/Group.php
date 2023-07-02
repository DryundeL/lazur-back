<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The users that belong to the Group.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTimestamps();
    }

    /**
     * The users that belong to the Group.
     */
    public function students()
    {
        return $this->hasMany(Student::class)->withTimestamps();
    }
}
