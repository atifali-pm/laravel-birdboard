<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate(['body' => 'required']);
        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {

        \request()->validate(
            [
                'body'      => 'required',
                'completed' => 'nullable',
            ]
        );

        $task->update(
            [
                'body' => \request('body'),


                'completed' => request()->has('completed') && true === request('completed') ? true : false,
            ]
        );

        return redirect($project->path());
    }
}
