<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangeDetail extends Model
{
    use HasFactory;

    /**
     * Return unique key for redis
     *
     * @param int $id
     * @return string
     */
    public static function getCacheKey(int $id): string
    {
        return 'change_detail_' . $id;
    }

    /**
     * The schedule that belong to the change details.
     */
    public function change(): BelongsTo
    {
        return $this->belongsTo(Change::class);
    }

    /**
     * The employee that belong to the change details.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * The audience that belong to the change details.
     */
    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }
}
