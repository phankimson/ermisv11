<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Guest access to home should be redirected to login flow.
     */
    public function test_guest_is_redirected_from_root(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertHeader('Location');
    }
}
