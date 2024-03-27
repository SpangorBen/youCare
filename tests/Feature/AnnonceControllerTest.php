<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Annonce;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AnnonceControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndex()
    {
        Annonce::factory()->count(3)->create();

        $response = $this->get('/api/annonce');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [['id', 'title', 'description', 'date', 'location', 'required_skills', 'organizer_id', 'type_id']]]);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $annonceData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => now()->format('Y-m-d'),
            'location' => $this->faker->address,
            'required_skills' => 'Skill 1, Skill 2',
            'type_id' => 1
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/annonce', $annonceData);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Annonce created successfully']);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $annonce = Annonce::factory()->create();

        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'date' => now()->addDays(1)->format('Y-m-d'),
            'location' => 'Updated Location',
            'required_skills' => 'Skill 1, Skill 2, Skill 3',
            'type_id' => 2
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/annonce/' . $annonce->id, $updatedData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Annonce updated successfully']);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $annonce = Annonce::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete('/api/annonce/' . $annonce->id);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Annonce deleted successfully']);
    }
}
