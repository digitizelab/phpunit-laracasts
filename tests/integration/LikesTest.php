<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikesTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function a_user_can_like_a_post()
    {
        $post = factory(App\Post::class)->create();

        $user = factory(App\User::class)->create();

        $this->actingAs($user);

        $post->like();

        $this->seeInDatabase('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertTrue($post->isLiked());
    }

    /** @test */
    public function a_user_can_unlike_a_post()
    {
        $post = factory(App\Post::class)->create();

        $user = factory(App\User::class)->create();

        $this->actingAs($user);

        $post->like();
        $post->unlike();

        $this->notSeeInDatabase('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_posts_like_status()
    {
        $post = factory(App\Post::class)->create();

        $user = factory(App\User::class)->create();

        $this->actingAs($user);

        $post->toggle();
        $this->assertTrue($post->isLiked());

        $post->toggle();
        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_post_knows_how_many_likes_it_has()
    {
        $post = factory(App\Post::class)->create();

        $user = factory(App\User::class)->create();

        $this->actingAs($user);

        $post->like();

        $this->assertEquals(1, $post->likesCount);
    }
}