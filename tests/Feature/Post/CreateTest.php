<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('authenticated user can see a post create form', function () {
    $user = User::factory()->create();
    actingAs($user);

    get(route('posts.create'))
        ->assertViewIs('posts.create')
        ->assertStatus(200);
});

it('unauthenticated user cannot see a post create form', function () {
    get(route('posts.create'))
        ->assertRedirectToRoute('login');
});

it('authenticated user can create a post', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('test_image.png');

    $data = [
        'title' => str_repeat('a', 255),
        'body' => 'This is a test post body.',
        'image' => $image,
    ];

    $user = User::factory()->create();
    actingAs($user);

    post(route('posts.store'), $data)
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
        'body' => 'This is a test post body.',
        'image' => $image,
    ];

    $user = User::factory()->create();
    actingAs($user);

    post(route('posts.store'), $data)
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
        'body' => 'This is a test post body.',
        'image' => $image,
    ];

    $user = User::factory()->create();
    actingAs($user);

    post(route('posts.store'), $data)
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

    post(route('posts.store'), $data)
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

    post(route('posts.store'), $data)
        ->assertSessionHasErrors([
            'body' => 'The body field must be at least 5 characters.',
        ])
        ->assertSessionDoesntHaveErrors(['title', 'image']);
});

it('post image is required', function () {
    $data = [
        'title' => 'Test Post',
        'body' => 'This is a test post body.',
        'image' => '',
    ];

    $user = User::factory()->create();
    actingAs($user);

    post(route('posts.store'), $data)
        ->assertSessionHasErrors([
            'image' => 'The image field is required.',
        ])
        ->assertSessionDoesntHaveErrors(['title', 'body']);
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

    post(route('posts.store'), $data)
        ->assertSessionHasErrors([
            'image' => 'The image field must be an image.',
        ])
        ->assertSessionDoesntHaveErrors(['title', 'body']);
});
