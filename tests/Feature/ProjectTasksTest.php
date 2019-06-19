<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        $project = factory(Project::class)->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
             ->assertSee('Test task');


    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

//        $project = factory(Project::class)->create(['owner_id' => auth()->id()]);

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path(), '/tasks', $attributes)->assertSessionHasErrors('body');

    }
}
