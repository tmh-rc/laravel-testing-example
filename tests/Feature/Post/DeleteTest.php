<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('authenticated user can delete a post', function () {
    $user = User::factory()->create();
    actingAs($user);

    if (! File::isDirectory(storage_path('app/public/upload/image'))) {
        File::makeDirectory(storage_path('app/public/upload/image/'), 0755, true);
    }
    File::put(storage_path('app/public/upload/image/').'test_image.png', '');

    $post = Post::factory()->create([
        'image_path' => 'upload/image/test_image.png',
    ]);

    delete(route('posts.destroy', $post->id))
        ->assertRedirectToRoute('posts.index');

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);

    Storage::assertMissing('upload/image/test_image.png');
});

it('unauthenticated user cannot delete a post', function () {
    $post = Post::factory()->create();

    delete(route('posts.destroy', $post->id))
        ->assertRedirectToRoute('login');
});
