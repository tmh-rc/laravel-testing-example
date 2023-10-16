<div class="w-2/3 mx-auto">
    <img src="{{ Storage::url($post->image_path) }}" alt="">
    <h1 class="text-2xl font-bold">{{ $post->title }}</h1>
    <p>{!! $post->body !!}</p>
    <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline">Go Home</a>
</div>