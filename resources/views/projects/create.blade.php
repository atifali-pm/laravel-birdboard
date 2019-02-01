@extends('layouts.app')
@section('content')
    <h1>Create a project</h1>
    <form method="post" action="/projects">
        @csrf
        <div>Title:</div>
        <div><input type="text" value="" name="title"></div>

        <div>Description:</div>
        <div><textarea name="description"></textarea></div>

        <div><input type="submit">
            <a href="/projects">Cancel</a>
        </div>
    </form>
@endsection
