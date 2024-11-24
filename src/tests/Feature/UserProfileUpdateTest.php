<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_profile_update_form_displays_correct_initial_values()
    {
        // ユーザーとプロフィールを作成
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image' => 'profile_image/default.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区新宿1-1-1',
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // プロフィール編集ページにアクセス
        $response = $this->get(route('profile.edit'));

        // レスポンスのステータスを確認
        $response->assertStatus(200);
        $response->assertViewIs('edit');

        // 初期値が正しく表示されていることを確認
        $response->assertSee('Test User'); // ユーザー名
        $response->assertSee('123-4567'); // 住所
        $response->assertSee('東京都新宿区新宿1-1-1'); // 住所
        $response->assertSee('profile_image/default.jpg'); // プロフィール画像
    }
}
