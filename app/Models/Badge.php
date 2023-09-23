<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Badge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'no_of_achievement',
        'created_at',
        'updated_at',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'badge';

    // protected $casts = [
    //     'no_of_achievement' => 'integer',
    // ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'badge_id', 'id');
    }
}
