<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('post list screen can be rendered', function () {
    get(route('posts.index'))
        ->assertStatus(200);
});

it('display a paginated posts', function () {
    $firstPagePosts = Post::factory(10)->create();
    $latestPagePosts = Post::factory(10)->create();

    get(route('posts.index'))
        ->assertViewIs('posts.index')
        ->assertSeeInOrder([
            $latestPagePosts[2]->title,
            $latestPagePosts[1]->title,
            $latestPagePosts[0]->title,
        ])
        ->assertDontSee($firstPagePosts[0]->title);

    get(route('posts.index', ['page' => 2]))
        ->assertViewIs('posts.index')
        ->assertSeeInOrder([
            $firstPagePosts[2]->title,
            $firstPagePosts[1]->title,
            $firstPagePosts[0]->title,
        ])
        ->assertDontSee($latestPagePosts[0]->title);
});
