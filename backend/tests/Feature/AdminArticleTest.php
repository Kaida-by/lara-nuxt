<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Tests\Feature;

use App\Models\Article;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelpers\TestHelperTrait;

class AdminArticleTest extends TestCase
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

        $user = $this->getNewUserWithRoleAdminForAP();
        $this->profile = $this->getNewProfileWithRoleAdminForAP($user->id);
        $this->token = $this->signIn($user);
    }

    /**
     * @return void
     */
    public function test_get_articles(): void
    {
        /** @var Article $article */
        $article = $this->getNewArticleForAP($this->profile->id);

        $data = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('get', env('API_URL') . 'api/admin/articles')->getOriginalContent();

        $title = $data->last()->title;

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'title' => $title]);
    }

    public function test_get_categories(): void
    {
        $data = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('get', env('API_URL') . 'api/admin/article-categories')->getOriginalContent();

        $slug = $data['categories']->first()->slug;

        $this->assertDatabaseHas('categories', ['id' => 1, 'slug' => $slug]);
    }

    public function test_update_article_from_ap(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create(['author_id' => $this->profile->id, 'status_id' => 1]);
        $input['checked'] = false;

        $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('patch', env('API_URL') . 'api/admin/article/approve/' . $article->id, $input);

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'status_id' => 2]);
    }

    public function test_delete_article_from_ap(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('delete', env('API_URL') . 'api/admin/article/delete/' . $article->id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
