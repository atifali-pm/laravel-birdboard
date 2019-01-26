<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_project(){

        $this->withoutExceptionHandling();

        $attribures = [
          'title'  => $this->faker->sentence,
          'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $attribures)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attribures);

        $this->get('/projects')->assertSee($attribures['title']);

    }
}
