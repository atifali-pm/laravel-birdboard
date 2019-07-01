<?php

namespace Tests\Feature;

use App\Project;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
             ->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
             ->assertSee('Test task');

        $this->assertInstanceOf(Task::class, $project->tasks->first());

    }

    /** @test */
    public function only_autherized_user_can_create_task()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->post($project->path() . '/tasks', $attributes = ['body' => 'Test task'])
             ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);

    }

    /** @test */
    public function user_can_update_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
             ->patch($project->tasks->first()->path(), ['body' => ''])
             ->assertSessionHasErrors('body');

        $this->actingAs($project->owner)
             ->patch($project->tasks->first()->path(), ['body' => 'Changed task body']);


        $this->assertDatabaseHas('tasks', ['body' => 'Changed task body']);

        $this->get($project->path())->assertSee('Changed task body');

    }

    /** @test */
    public function a_user_can_complete_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
             ->patch($project->tasks->first()->path(), $attributes = ['body' => 'Task general body', 'completed' => true]);

        $this->assertDatabaseHas('tasks', $attributes);

    }

    /** @test */
    public function a_user_can_incomplete_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
             ->patch($project->tasks->first()->path(), ['body' => 'Task body', 'completed' => false]);

//        $this->assertDatabaseHas('tasks', $attributes);

        $this->patch($project->path() . '/tasks/' . $project->tasks[0]->id, $attributes = ['body' => 'Task Changed body', 'completed' => false]);

        $this->assertDatabaseHas('tasks', $attributes);


    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        $attributes = factory(Task::class)->raw(['body' => '']);

        $this->actingAs($project->owner)
             ->post($project->path() . '/tasks', $attributes)
             ->assertSessionHasErrors('body');
    }
}
