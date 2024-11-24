<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_login_after_logout()
    {
        // ユーザーを作成
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password123'),
        ]);

        // ログインリクエストを送信
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        // ログイン後に認証済みであることを確認
        $this->assertAuthenticatedAs($user);

        // ログアウトリクエストを送信
        $response = $this->get(route('logout'));

        // ログイン画面にリダイレクトされることを確認
        $response->assertRedirect(route('login'));
    }
}
