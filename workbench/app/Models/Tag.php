<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Workbench\Database\Factories\TagFactory;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'test_tags';

    protected $fillable = [
        'name',
    ];

    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'test_user_tags', 'tag_id', 'user_id');
    }
}
