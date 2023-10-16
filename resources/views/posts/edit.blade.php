<x-app-layout>
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <input type="file" name="image" class="block">
        @error('image')
        <p class="text-red-500 text-sm">{{ $message }}</p>    
        @enderror
        <input type="text" name="title" value="{{ old('title', $post->title)}}" class="block">
        @error('title')
        <p class="text-red-500 text-sm">{{ $message }}</p>    
        @enderror
        <textarea name="body" value="{{ old('body', $post->body) }}" cols="30" rows="10" class="block">
            {{ old('body', $post->body) }}
        </textarea>
        @error('body')
        <p class="text-red-500 text-sm">{{ $message }}</p>    
        @enderror
        <button type="submit">Update</button>
    </form>
</x-app-layout>