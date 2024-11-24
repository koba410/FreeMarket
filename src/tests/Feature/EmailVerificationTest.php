<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test*/
    public function it_registers_user_and_sends_verification_email()
    {
        // NotificationとEventの偽装
        Notification::fake();
        Event::fake();

        // ユーザー登録リクエスト
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // データベースにユーザーが登録されているか確認
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // Registeredイベントが発火されたことを確認
        Event::assertDispatched(Registered::class);

        // メール認証ページへリダイレクトされるか確認
        $response->assertRedirect(route('verification.notice'));

    }


    /** @test */
    public function it_verifies_email_and_redirects_to_profile_edit()
    {
        Notification::fake();

        // ユーザー作成と認証トークン生成
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 認証メールを送信
        $user->sendEmailVerificationNotification();

        // 認証URLの生成
        $verificationUrl = url("/email/verify/{$user->id}/" . sha1($user->getEmailForVerification()));

        // ミドルウェアを無効化して認証リクエストを送信
        $response = $this->actingAs($user)->withoutMiddleware()->get($verificationUrl);

        // プロフィール編集画面へリダイレクトされるか確認
        $response->assertRedirect(route('profile.edit'));

        // メールが認証されていることを確認
        $this->assertNotNull($user->fresh()->email_verified_at);
    }


    /** @test */
    public function it_shows_verification_notice_page_after_registration()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 認証確認ページへアクセス
        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');
    }
}
