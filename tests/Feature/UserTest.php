<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Definition;
use App\Models\Tag;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    public function authenticated_users_can_like_a_definition()
    {
        $this->actingAsLoggedUser();
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        Word::factory()->create();
        $definition = Definition::factory()->create();
        $response = $this->post('api/definitions/'.$definition['id'].'/like');
        $response->assertStatus(200);
        $this->assertEquals(Definition::find($definition['id'])->like, 1);
    }

    /**
     *  @test
     */
    public function authenticated_users_can_dislike_a_definition()
    {
        $this->actingAsLoggedUser();
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        Word::factory()->create();
        $definition = Definition::factory()->create();
        $response = $this->post('api/definitions/'.$definition['id'].'/dislike');
        $response->assertStatus(200);
        Definition::find($definition['id']);
        $this->assertEquals(Definition::find($definition['id'])->dislike, 1);
    }

    private function actingAsLoggedUser()
    {
        $this->actingAs(User::factory()->create(), 'api');
    }
}
