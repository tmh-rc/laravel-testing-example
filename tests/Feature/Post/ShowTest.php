<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('displays a specific post', function () {
    $post = Post::factory()->create();

    get(route('posts.show', $post->id))
        ->assertStatus(200)
        ->assertSee($post->image_path)
        ->assertSee($post->title)
        ->assertSee($post->body);
});

it('displays 404 page for a non-existing post', function () {
    get(route('posts.show', 0))
        ->assertStatus(404);
});
