@extends('layouts.app')
@section('content')
    <h1>{{ $project->title }}</h1>
    <div>{{ $project->description }}</div>
    <div>
        @foreach($project->tasks as $task)
            <div>{{$task->body}}</div>
        @endforeach

    </div>

    <p>{{ $project->notes }}</p>
    <a href="/projects">Go back!</a>
@endsection
