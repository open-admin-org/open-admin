<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Workbench\Database\Factories\ProfileFactory;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'test_user_profiles';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'postcode',
        'address',
        'latitude',
        'longitude',
        'color',
        'start_at',
        'end_at',
    ];

    protected static function newFactory(): ProfileFactory
    {
        return ProfileFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
