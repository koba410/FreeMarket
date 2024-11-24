<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_logged_in_user_can_post_a_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comment.store', $item), [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect(); // リダイレクトが正しく行われたことを確認
        $this->assertDatabaseHas('item_comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment.',
        ]);
    }

    /** @test */
    public function a_guest_user_cannot_post_a_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comment.store', $item), [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect(route('login')); // ログイン画面へリダイレクトされることを確認
        $this->assertDatabaseMissing('item_comments', [
            'comment' => 'This is a test comment.',
        ]);
    }

    /** @test */
    public function comment_is_required_for_submission()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comment.store', $item), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment' => 'コメント内容を入力してください。']);
    }

    /** @test */
    public function comment_must_not_exceed_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comment.store', $item), [
            'comment' => str_repeat('a', 256), // 256文字のコメント
        ]);

        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以内で入力してください。']);
    }
}
