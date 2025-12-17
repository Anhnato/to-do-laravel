<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryFeatureTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_create_a_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'New Project'
        ]);

        $response->assertRedirect(); // or assertOk()

        $this->assertDatabaseHas('categories', [
            'name' => 'New Project',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function category_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('categories.store'), [
            'name' => '' // Empty
        ]);

        $response->assertSessionHasErrors(['name']);
    }
}
