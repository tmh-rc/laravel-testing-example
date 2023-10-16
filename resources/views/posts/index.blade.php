<x-app-layout>
    
<div class="w-2/3">
    <a href="{{ route('posts.create') }}" class="border px-4 py-2 bg-blue-500 hover:bg-blue-700 mb-5 inline-block">Create</a>
    <ul>
        @foreach ($posts as $post)
            <li class="mb-5">
                <a href="{{ route('posts.show', $post->id) }}" class="text-blue-500 hover:underline">{{ $post->title }}</a>
                <div class="flex">
                    <a href="{{ route('posts.edit', $post->id) }}" class="block text-green-500">Edit</a>
                    <div class="mx-5">|</div>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="text-red-500">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
    
    {{ $posts->links() }}
</div>
</x-app-layout>