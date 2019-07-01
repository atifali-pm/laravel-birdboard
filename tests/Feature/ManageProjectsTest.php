<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guest_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('/login');

        $this->patch($project->path(), [])->assertRedirect('/login');

    }

    /** @test */
    public function a_user_can_create_project()
    {

        $this->withoutExceptionHandling();
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attribures = [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $attribures)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attribures);

        $this->get('/projects')->assertSee($attribures['title']);

    }


    /** @test */
    public function only_authenticated_user_can_update_their_own_project()
    {

        $project = ProjectFactory::create();

        $this->signIn();

        $attributes = ['title' => 'Changed title', 'description' => 'Changed description', 'notes'];
        $this->patch($project->path(), $attributes)->assertStatus(403);

        $project = ProjectFactory::create();

        $attributes = ['title' => 'Changed title', 'description' => 'Changed description', 'notes' => 'General notes'];

        $this->actingAs($project->owner)
             ->patch($project->path(), $attributes);

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
             ->assertSee('Changed title')
             ->assertSee('Changed description')
             ->assertSee('General notes');
    }


    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);
        $this->get($project->path())
             ->assertSee($project->title)
             ->assertSee($project->description);
    }

    /** @test */
    public function an_authicated_user_cannot_view_project_of_others()
    {
        $this->signIn();
        $project = factory('App\Project')->create();
        $this->get($project->path())->assertStatus(403);

    }


    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_description()
    {

        $this->signIn();
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

}
