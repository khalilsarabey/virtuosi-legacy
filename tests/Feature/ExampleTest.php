<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_home_page_redirects_to_the_frontend()
    {
        $response = $this->get('/');

        $response->assertRedirect('/v2/frontend');
    }
}
