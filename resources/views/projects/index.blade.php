@extends('layouts.app')
@section('content')
    <div class="flex items-center">
        <h1 class="mr-auto mb-3">Birdboard</h1>
        <a href="/projects/create">New project</a>
    </div>
    <div>
        <ul>
            @forelse($projects as $project)
                <li><a href="{{ $project->path() }}">{{$project->title}}</a></li>
            @empty
                No projects found!
            @endforelse
        </ul>
    </div>
@endsection
