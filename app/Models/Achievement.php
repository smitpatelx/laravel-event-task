<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'achievement_type_id',
        'level',
        'created_at',
        'updated_at',
    ];

    // protected $casts = [
    //     'level' => 'integer',
    // ];

    /**
     * Get the user that wrote the comment.
     */
    public function achievement_type()
    {
        return $this->belongsTo(AchievementType::class);
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'achievement';
}
