<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Definition;
use App\Models\Tag;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    public function every_users_can_get_definitions_for_tag()
    {
        $this->withoutExceptionHandling();
        User::factory()->create();
        Word::factory()->create();
        $tag = Tag::factory()->create(['text' => 'toto']);
        $definition1 = Definition::factory()->create(array_merge($this->data()));
        $definition2 = Definition::factory()->create();
        $definition1->tags()->sync([$tag['id']], false);
        $response = $this->get('api/tags/'.$tag['id'].'/definitions');
        $response->assertStatus(200);
        $this->assertEquals(1, count($response['data']));  
        $this->assertEquals($definition1['id'], $response['data'][0]['id']);  
    }

    private function data()
    {
        return [
            'text' => 'next text definition',
            'exemple' => 'New exemple for definition',
        ];
    }
}
