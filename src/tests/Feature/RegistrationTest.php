<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_validation_error_when_name_is_missing()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください。']);
    }

    /** @test */
    public function it_shows_validation_error_when_email_is_missing()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください。']);
    }

    /** @test */
    public function it_shows_validation_error_when_password_is_missing()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください。']);
    }

    /** @test */
    public function it_shows_validation_error_when_password_is_too_short()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください。']);
    }

    /** @test */
    public function it_shows_validation_error_when_passwords_do_not_match()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードが一致しません。']);
    }

    /** @test */
    public function it_registers_user_when_all_fields_are_valid()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice')); // テストケース一覧では「ログイン画面へリダイレクトすることを確認」とあるが、メール認証機能を実装したため会員登録後にメール認証確認画面に遷移するようにしている。なぜなら「メール認証しないとログインできないこと」という要件があるため。
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
