<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Definition;
use App\Models\Tag;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class DefinitionTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    public function every_in_users_can_fetch_definition_list()
    {
        $user = User::factory()->create();
        $word = Word::factory()->create();
        $definition = Definition::factory()->create([
            'word_id' => $word->id,
            'user_id' => $user->id
        ]);
        $response = $this->get('api/definitions');
        $response->assertStatus(200);
        $data = $response->decodeResponseJson();
        $definitionsInResponse = array_map(function ($d) { return $d['id']; }, $data['data']);
        $this->assertEquals(count($definitionsInResponse), 1);
        $this->assertEquals($definitionsInResponse[0], $definition->id);
    }

    /**
     *  @test
     */
    public function every_in_users_can_fetch_definition_list_with_query()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $word = Word::factory()->create(['name' => 'something']);
        $definitionWithQueryWordInText = Definition::factory()->create([
            'text' => 'test',
            'word_id' => $word->id,
            'user_id' => $user->id
        ]);
        $definitionWithAnotherWord = Definition::factory()->create([
            'text' => 'anotherword',
            'word_id' => $word->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('api/definitions?q=test');
        $response->assertStatus(200);
        $data = $response->decodeResponseJson();
        $definitionsInResponse = array_map(function ($d) { return $d['id']; }, $data['data']);
        $this->assertEquals(count($definitionsInResponse), 1);
        $this->assertEquals($definitionsInResponse[0], $definitionWithQueryWordInText->id);

        $word = Word::factory()->create(['name' => 'wordinwordtable']);
        $definitionWithQueryWordInWordTable = Definition::factory()->create([
            'text' => 'test',
            'word_id' => $word->id,
            'user_id' => $user->id
        ]);
        $response = $this->get('api/definitions?q=wordinwordtable');
        $response->assertStatus(200);
        $data = $response->decodeResponseJson();
        $definitionsInResponse = array_map(function ($d) { return $d['id']; }, $data['data']);
        $this->assertEquals(count($definitionsInResponse), 1);
        $this->assertEquals($definitionsInResponse[0], $definitionWithQueryWordInWordTable->id);
    }

    /**
     *  @test
     */
    public function unauthenticated_users_cannot_create_a_definition()
    {
        // TODO
        // In Laravel 5.7 or above route checking happens before exception throwing, so unauthenticate override exception does not apply
        // and if application/json is not used it will send to route::login with a 500 errors because it does not exist
        // we should determine a better way to take this in consideration -> to investigate
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
    public function authenticated_users_can_create_a_definition_with_not_tags()
    {
        $this->actingAsLoggedUser();
        $count = Definition::all()->count();
        $countWord = Word::all()->count();

        $response = $this->post('api/definitions', $this->data());
        $response->assertStatus(201);
        $this->assertCount($count + 1, Definition::all());
        $this->assertCount($countWord + 1, Word::all());
    }

    /**
     *  @test
     */
    public function authenticated_users_can_create_a_definition_with_not_tags_and_a_word_that_already_exists()
    {
        $this->actingAsLoggedUser();
        $count = Definition::all()->count();
        Word::factory()->create(['name' => $this->data()['name']]);
        $countWord = Word::all()->count();

        $response = $this->post('api/definitions', $this->data());
        $response->assertStatus(201);
        $this->assertCount($count + 1, Definition::all());
        $this->assertCount($countWord, Word::all());
    }


    /**
     *  @test
     */
    public function authenticated_users_can_create_a_definition_with_new_tags()
    {
        $this->actingAsLoggedUser();
        $count = Definition::all()->count();
        $count = Tag::all()->count();
        $response = $this->post('api/definitions', array_merge($this->data(), [
            'tags' => ['toto', 'tata']
        ]));
        $response->assertStatus(201);
        $this->assertCount($count + 2, Tag::all());
    }

    /**
     *  @test
     */
    public function authenticated_users_can_create_a_definition_with_old_tags_that_are_not_created()
    {
        // should create only one tag, the one that didn't exist before
        $this->actingAsLoggedUser();
        Tag::factory()->create(['text' => 'toto']);
        $count = Tag::all()->count();
        $response = $this->post('api/definitions', array_merge($this->data(), [
            'tags' => ['toto', 'tata']
        ]));
        $response->assertStatus(201);
        $this->assertCount($count + 1, Tag::all());
    }

    /**
     *  @test
     */
    public function authenticated_users_can_delete_their_own_definition()
    {
        // should create only one tag, the one that didn't exist before
        // $this->withoutExceptionHandling();
        $this->actingAsLoggedUser();
        Tag::factory()->create(['text' => 'toto']);
        $count = Tag::all()->count();
        $response = $this->post('api/definitions', array_merge($this->data(), [
            'tags' => ['toto', 'tata']
        ]));
        $response->assertStatus(201);
        $this->assertCount($count + 1, Tag::all());
        $data = $response->decodeResponseJson();
        $count = Definition::all()->count();

        $response = $this->post('api/definitions/'.$data['data']['id']);
        $response->assertStatus(204);
        $this->assertCount($count - 1, Definition::all());
    }

    /**
     *  @test
    */
    public function authenticated_users_cannot_delete_other_definition()
    {
        // should create only one tag, the one that didn't exist before
        $this->actingAsLoggedUser();
        Tag::factory()->create(['text' => 'toto']);
        $count = Tag::all()->count();
        $response = $this->post('api/definitions', array_merge($this->data(), [
            'tags' => ['toto', 'tata']
        ]));
        $response->assertStatus(201);
        $this->assertCount($count + 1, Tag::all());
        $data = $response->decodeResponseJson();
        $count = Definition::all()->count();
        $this->actingAs(User::factory()->create(), 'api');
        $response = $this->post('api/definitions/'.$data['data']['id']);
        $response->assertStatus(401);
        $this->assertCount($count, Definition::all());
    }

    private function data()
    {
        return [
            'text' => 'next text definition',
            'name' => 'New word',
            'exemple' => 'New exemple for definition',
        ];
    }

    private function actingAsLoggedUser()
    {
        $this->actingAs(User::factory()->create(), 'api');
    }


}
