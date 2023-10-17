<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

it('authenticated user can see a post edit form', function () {
    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    get(route('posts.edit', $post->id))
        ->assertViewIs('posts.edit')
        ->assertStatus(200);
});

it('unauthenticated user cannot see a post edit form', function () {
    $post = Post::factory()->create();

    get(route('posts.edit', $post->id))
        ->assertRedirectToRoute('login');
});

it('authenticated user can update a post', function () {

    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    Storage::fake('public');

    $image = UploadedFile::fake()->image('test_image.png');

    $data = [
        'title' => 'Test Post Changed',
        'body' => 'This is a test post body Changed.',
        'image' => $image,
    ];

    put(route('posts.update', $post->id), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirectToRoute('posts.index');

    assertDatabaseHas('posts', [
        'title' => $data['title'],
        'body' => $data['body'],
        'image_path' => 'upload/image/'.$image->hashName(),
    ]);

    Storage::assertExists("upload/image/{$image->hashName()}");
});

it('post title is required', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('test_image.png');

    $data = [
        'title' => '',
        'body' => 'This is a test post body Changed.',
        'image' => $image,
    ];

    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    put(route('posts.update', $post->id), $data)
        ->assertSessionHasErrors([
            'title' => 'The title field is required.',
        ])
        ->assertSessionDoesntHaveErrors(['body', 'image']);
});

it('post title must not greater than 255 characters', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('test_image.png');

    $data = [
        'title' => str_repeat('a', 256),
        'body' => 'This is a test post body Changed.',
        'image' => $image,
    ];

    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    put(route('posts.update', $post->id), $data)
        ->assertSessionHasErrors([
            'title' => 'The title field must not be greater than 255 characters.',
        ])
        ->assertSessionDoesntHaveErrors(['body', 'image']);
});

it('post body is required', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('test_image.png');

    $data = [
        'title' => 'Test Post',
        'body' => '',
        'image' => $image,
    ];

    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    put(route('posts.update', $post->id), $data)
        ->assertSessionHasErrors([
            'body' => 'The body field is required.',
        ])
        ->assertSessionDoesntHaveErrors(['title', 'image']);
});

it('post body must be at least 5 characters.', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('test_image.png');

    $data = [
        'title' => 'Test Post',
        'body' => '1234',
        'image' => $image,
    ];

    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    put(route('posts.update', $post->id), $data)
        ->assertSessionHasErrors([
            'body' => 'The body field must be at least 5 characters.',
        ])
        ->assertSessionDoesntHaveErrors(['title', 'image']);
});

it('post image can be empty', function () {
    $data = [
        'title' => 'Test Post',
        'body' => 'This is a test post body.',
        'image' => '',
    ];

    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    put(route('posts.update', $post->id), $data)
        ->assertSessionHasNoErrors();
});

it('post image must be an image.', function () {

    $invalidImage = UploadedFile::fake()->create('test.txt', 1024);

    $data = [
        'title' => 'Test Post',
        'body' => 'This is a test post body.',
        'image' => $invalidImage,
    ];

    $user = User::factory()->create();
    actingAs($user);

    $post = Post::factory()->create();

    put(route('posts.update', $post->id), $data)
        ->assertSessionHasErrors([
            'image' => 'The image field must be an image.',
        ])
        ->assertSessionDoesntHaveErrors(['title', 'body']);
});
