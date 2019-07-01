<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use Tests\Setup\ProjectFactory;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));

    }

    public function show(Project $project)
    {

        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        //Validate
        $attributes = request()->validate(
            [
                'title'       => 'required',
                'description' => 'required',
            ]
        );

        //Persist
        auth()->user()->projects()->create($attributes);

        //Redirect
        return redirect('/projects');

    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);

        $attributes = \request()->validate(
            [
                'title'       => 'required',
                'description' => 'required',
                'notes'       => 'nullable',
            ]
        );


        $project->update($attributes);

        return redirect($project->path());

    }

}
