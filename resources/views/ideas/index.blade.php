@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto space-y-4">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Ideas</h1>

            <a href="{{ route('ideas.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded">
                New Idea
            </a>
        </div>

        {{-- Success message --}}
        @if(session('status'))
            <div class="p-2 bg-green-100 border rounded">
                {{ session('status') }}
            </div>
        @endif

        {{-- List of ideas --}}
        @forelse($ideas as $idea)
            <div class="p-4 bg-white border rounded">

                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold">
                            <a href="{{ route('ideas.show', $idea) }}">
                                {{ $idea->title }}
                            </a>
                        </h2>

                        <p class="text-sm text-gray-600">
                            By {{ $idea->user?->name ?? 'Unknown' }}
                            • {{ $idea->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Boutons Edit/Delete pour propriétaire ou admin --}}
                    @if(auth()->id() === $idea->user_id || auth()->user()->isAdmin())
                        <div class="flex space-x-2">
                            <a href="{{ route('ideas.edit', $idea) }}" class="text-blue-600 text-sm">Modifier</a>
                            <form action="{{ route('ideas.destroy', $idea) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-sm">Supprimer</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="mt-2 text-sm">
                    {{ \Illuminate\Support\Str::limit($idea->description, 200) }}
                </div>
            </div>

        @empty
            <p>No ideas yet.</p>
        @endforelse

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $ideas->links() }}
        </div>

    </div>

@endsection
