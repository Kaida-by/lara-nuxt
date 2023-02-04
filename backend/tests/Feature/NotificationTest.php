<?php

namespace Tests\Feature;

use App\Http\Controllers\NotificationsController;
use App\Http\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\TestHelpers\TestHelperTrait;
use Exception;

class NotificationTest extends TestCase
{
//    use RefreshDatabase;
//    use WithFaker;
//    use TestHelperTrait;
//
//    protected string $token;
//    protected mixed $profile;
//
//    /**
//     * @throws Exception
//     */
//    public function setUp(): void
//    {
//        parent::setUp();
//
//        $user = $this->getNewUserForPC();
//        $this->token = $this->signIn($user);
//    }

    public function test_get_notification(): void
    {
//        $this->mock(NotificationService::class, function (MockInterface $mock) {
//            $mock->shouldReceive('getNotification')
//                ->once()
//                ->andReturn(true);
//        });
//
//        app(NotificationsController::class)->getNotifications();
    }
}
