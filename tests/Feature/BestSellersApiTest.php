<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class BestSellersApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    public function test_bestsellers_endpoint_with_no_parameters()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers');

        $response->assertStatus(200);
        
        Http::assertSent(function ($request) {
            return $request->url() == config('services.nyt.url') . '?api-key=' . config('services.nyt.key');
        });
    }

    public function test_bestsellers_endpoint_with_author_parameter()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?author=John%20Doe');

        $response->assertStatus(200);
        
        Http::assertSent(function ($request) {
            return $request->url() == config('services.nyt.url') . '?api-key=' . config('services.nyt.key') . '&author=John%20Doe';
        });
    }

    public function test_bestsellers_endpoint_with_isbn_parameter()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?isbn[]=1234567890&isbn[]=9876543210');

        $response->assertStatus(200);
        
        Http::assertSent(function ($request) {
            return $request->url() == config('services.nyt.url') . '?api-key=' . config('services.nyt.key') . '&isbn=1234567890;9876543210';
        });
    }

    public function test_bestsellers_endpoint_with_invalid_isbn()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?isbn[]=123');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['isbn.0']);
    }

    public function test_bestsellers_endpoint_with_title_parameter()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?title=Great%20Book');

        $response->assertStatus(200);
        
        Http::assertSent(function ($request) {
            return $request->url() == config('services.nyt.url') . '?api-key=' . config('services.nyt.key') . '&title=Great%20Book';
        });
    }

    public function test_bestsellers_endpoint_with_valid_offset()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?offset=20');

        $response->assertStatus(200);
        
        Http::assertSent(function ($request) {
            return $request->url() == config('services.nyt.url') . '?api-key=' . config('services.nyt.key') . '&offset=20';
        });
    }

    public function test_bestsellers_endpoint_with_invalid_offset()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?offset=15');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['offset']);
    }
}