<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Definition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class DefinitionTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    public function every_in_users_can_see_the_definition_list()
    {
        $response = $this->get('api/definitions');

        $response->assertStatus(200);
    }

    /**
     *  @test
     */
    public function unauthenticated_users_cannot_create_a_definition()
    {
        // In Laravel 5.7 or above route checking happens before exception throwing, so unauthenticate override exception does not apply
        // and if application/json is not used it will send to route::login with a 500 errors because it does not exist
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/definitions', $this->data());

        $response->assertStatus(401);
    }

    /**
     *  @test
     */
    public function authenticated_users_cannot_create_a_definition_that_miss_data()
    {
        $this->actingAsLoggedUser();
        $count = Definition::all()->count();
        $response = $this->post('api/definitions', array_merge($this->data(), ['name' => null]));
        $response->assertStatus(302);

        $response = $this->post('api/definitions', array_merge($this->data(), ['exemple' => null]));
        $response->assertStatus(302);

        $response = $this->post('api/definitions', array_merge($this->data(), ['text' => null]));
        $response->assertStatus(302);

        $response = $this->post('api/definitions', array_merge($this->data(), ['name' => '']));
        $response->assertStatus(302);

        $response = $this->post('api/definitions', array_merge($this->data(), ['exemple' => '']));
        $response->assertStatus(302);

        $response = $this->post('api/definitions', array_merge($this->data(), ['text' => '']));
        $response->assertStatus(302);

        $this->assertCount($count, Definition::all());
    }

    /**
     *  @test
     */
    public function authenticated_users_can_create_a_definition()
    {
        $this->actingAsLoggedUser();
        $count = Definition::all()->count();
        $response = $this->post('api/definitions', [
            'text' => 'next text definition',
            'name' => 'New word',
            'exemple' => 'New exemple for definition',
        ]);
        $response->assertStatus(201);
        $this->assertCount($count + 1, Definition::all());
    }

    private function data()
    {
        return [
            'test' => 'next text definition',
            'name' => 'New word',
            'exemple' => 'New exemple for definition',
        ];
    }

    private function actingAsLoggedUser()
    {
        $this->actingAs(User::factory()->create(), 'api');
    }


}
