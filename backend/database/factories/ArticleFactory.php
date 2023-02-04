<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'test title',
            'description' => 'test description',
            'author_id' => 1,
            'entity_type_id' => 1,
            'category_id' => 2,
            'status_id' => 1,
        ];
    }
}
