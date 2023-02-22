<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Tests\TestHelpers;

use App\Models\Article;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

trait TestHelperTrait
{
    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        $user = User::factory()->create();

        return JWTAuth::fromUser($user);
    }

    public function signIn($user): string
    {
        return JWTAuth::fromUser($user);
    }

    /**
     * @throws Exception
     */
    public function getNewUserWithRoleAdminForAP(): mixed
    {
        return User::factory()->create([
            'name' => 'test admin name',
            'email' => random_int(10000, 999999) . '@gmail.com',
            'role_id' => 1,
            'status_id' => 2,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }

    public function getNewProfileWithRoleAdminForAP(int $id): mixed
    {
        return Profile::factory()->create([
            'name' => 'test profile name',
            'surname' => 'test profile surname',
            'patronymic' => 'test profile patronymic',
            'user_id' => $id,
            'entity_type_id' => 3,
        ]);
    }

    /**
     * @throws Exception
     */
    public function getNewUserForPC(): mixed
    {
        return User::factory()->create([
            'name' => 'test admin name',
            'email' => random_int(10000, 999999) . '@gmail.com',
            'role_id' => 3,
            'status_id' => 2,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }

    public function getNewProfileForPC(int $id): mixed
    {
        return Profile::factory()->create([
            'name' => 'test profile name',
            'surname' => 'test profile surname',
            'patronymic' => 'test profile patronymic',
            'user_id' => $id,
            'entity_type_id' => 3,
        ]);
    }

    /**
     * @return Collection|Model
     */
    public function getNewArticleForPC(int $id): Collection|Model
    {
        return Article::factory()->create([
            'title' => 'test title1234',
            'description' => 'test description',
            'author_id' => $id,
            'entity_type_id' => 1,
            'category_id' => 2,
            'status_id' => 1,
        ]);
    }
}
