<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Tests\Feature;

use App\Models\Article;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JsonException;
use Tests\TestCase;
use Tests\TestHelpers\TestHelperTrait;

class PersonalCabinetTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use TestHelperTrait;

    protected string $token;
    protected mixed $profile;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $user = $this->getNewUserForPC();
        $this->profile = $this->getNewProfileForPC($user->id);
        $this->token = $this->signIn($user);
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_get_articles(): void
    {
        /** @var Article $article */
        $article = $this->getNewArticleForPC($this->profile->id);

        $data = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('get', env('API_URL') . 'api/my-articles/')->getOriginalContent();

        $title = $data->first()->title;

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'title' => $title]);
    }

    /**
     * @return void
     */
    public function test_create_article(): void
    {
        $countArticles = Article::count();
        Article::factory()->create();

        $this->assertDatabaseCount('articles', ++$countArticles);
    }

    /**
     * @return void
     */
    public function test_update_article(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create(['author_id' => $this->profile->id]);
        $article->title = 'Updated article';

        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('post', env('API_URL') . 'api/article/' . $article->id, $article->toArray());

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'title' => 'Updated article']);
    }
}
