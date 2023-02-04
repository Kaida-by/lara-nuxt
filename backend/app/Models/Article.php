<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Models;

use App\Http\Interfaces\EntityInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title',
        'description',
    ];

    protected $dates = [
        'created_at'
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'entity_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Profile::class,
            'id',
            'id',
            'author_id',
            'user_id'
        );
    }

    public function entityStatus(): HasOne
    {
        return $this->hasOne(EntityStatus::class, 'id', 'status_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'id', 'author_id');
    }
}
