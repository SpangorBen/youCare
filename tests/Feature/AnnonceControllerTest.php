<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Annonce;
use App\Models\User;
use App\Models\Type;
use Database\Factories\AnnonceFactory;
use Database\Factories\TypeFactory;
use Illuminate\Support\Facades\Auth;

class AnnonceControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndex()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/annonce');

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $this->withoutExceptionHandling();

        $annonce = Annonce::factory()->create();

        $response = $this->get('/api/annonce/' . $annonce->id);

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $this->withoutExceptionHandling();
        $type = Type::factory()->create();

        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date,
            'location' => $this->faker->address,
            'required_skills' => $this->faker->sentence,
            'type_id' => $type->id,
        ];

        $response = $this->post('/api/annonce', $data);

        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $annonce = Annonce::factory()->create();

        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date,
            'location' => $this->faker->address,
            'required_skills' => $this->faker->sentence,
            'type_id' => 1,
        ];

        $response = $this->put('/api/annonce/' . $annonce->id, $data);

        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $annonce = Annonce::factory()->create();

        $response = $this->delete('/api/annonce/' . $annonce->id);

        $response->assertStatus(204);
    }

    public function testFilter()
    {
        $this->withoutExceptionHandling();

        // $user = User::factory()->create();
        $user = User::factory()->withRoleId(2)->create();
        Auth::login($user);

        $response = $this->get('/api/annonces/filter?type_id=1&location=New+York');

        $response->assertStatus(200);
    }
}
