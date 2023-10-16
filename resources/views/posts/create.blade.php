<x-app-layout>
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" class="block">
        @error('image')
        <p class="text-red-500 text-sm">{{ $message }}</p>    
        @enderror
        <input type="text" name="title" value="{{ old('title') }}" class="block">
        @error('title')
        <p class="text-red-500 text-sm">{{ $message }}</p>    
        @enderror
        <textarea name="body" cols="30" rows="10" class="block">
            {{ old('body') }}
        </textarea>
        @error('body')
        <p class="text-red-500 text-sm">{{ $message }}</p>    
        @enderror
        <button type="submit">Create</button>
    </form>
</x-app-layout>