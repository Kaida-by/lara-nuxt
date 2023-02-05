<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelpers\TestHelperTrait;

class AuthTest extends TestCase
{
//    use RefreshDatabase;
    use WithFaker;
    use TestHelperTrait;

    /**
     * @return void
     */
    public function test_registration(): void
    {
        $countUsers = User::count();
        User::factory()->create();

        $this->assertDatabaseCount('users', ++$countUsers);
    }

    /**
     * @return void
     */
    public function test_login(): void
    {
        $baseUrl = 'api/auth/login';
        $email = 'admin@admin.com';
        $password = '12345678';

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => $email,
            'password' => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token', 'success', 'data'
            ]);
    }

    /**
     * @return void
     */
    public function test_logout(): void
    {
        $token = $this->getAuthToken();
        $baseUrl = 'api/auth/logout?token=' . $token;
        $response = $this->json('GET', $baseUrl);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['success']);
    }
}
