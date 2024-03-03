<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Workbench\Database\Factories\UserFactory;

class User extends Model
{
    use HasFactory;

    protected $table = 'test_users';

    protected $fillable = [
        'username',
        'email',
        'mobile',
        'avatar',
        'password',
        'data',
    ];

    protected $appends = ['full_name', 'position'];

    protected $casts = ['data' => 'array'];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'test_user_tags', 'user_id', 'tag_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->profile['first_name']} {$this->profile['last_name']}";
    }

    public function getPositionAttribute(): string
    {
        return "{$this->profile->latitude} {$this->profile->longitude}";
    }
}
