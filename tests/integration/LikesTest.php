<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikesTest extends TestCase
{

    use DatabaseTransactions;

    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = factory(App\Post::class)->create();

        $this->signIn();
    }

    /** @test */
    public function a_user_can_like_a_post()
    {
        $this->post->like();

        $this->seeInDatabase('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post)
        ]);

        $this->assertTrue($this->post->isLiked());
    }

    /** @test */
    public function a_user_can_unlike_a_post()
    {
        $this->post->like();
        $this->post->unlike();

        $this->notSeeInDatabase('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post)
        ]);

        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_posts_like_status()
    {
        $this->post->toggle();
        $this->assertTrue($this->post->isLiked());

        $this->post->toggle();
        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_post_knows_how_many_likes_it_has()
    {
        $this->post->like();

        $this->assertEquals(1, $this->post->likesCount);
    }
}