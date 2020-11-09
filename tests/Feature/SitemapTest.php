<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    public function everyoneCanGetSitemapPage()
    {
        $response = $this->get('sitemap.xml');

        $response->assertStatus(200);
    }
}