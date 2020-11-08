<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Definition;
use App\Models\Tag;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    public function unauthenticated_user_can_create_account()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/auth/signup', [
            'name' => 'paul',
            'email' => 'paul@gmail.com',
            'password' => 'toitoi'
        ]);
        $this->assertCount(1, User::all());
        $response->assertStatus(200);
        $this->actingAs(User::first());
        $response = $this->get('api/auth/me');
        $response->assertStatus(200);
    }
}