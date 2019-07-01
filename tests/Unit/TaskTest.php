<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function task_has_path()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->assertEquals($project->path() . '/tasks/' . $project->tasks[0]->id, $project->tasks[0]->path());
    }

    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = factory(Task::class)->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

}
