<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_mahasiswa_can_be_searched_by_name_nim_or_email(): void
    {
        Mahasiswa::create([
            'nama' => 'Raka Pratama',
            'nim' => '2810011566',
            'email' => 'daffa@kampus.ac.id',
        ]);
        Mahasiswa::create([
            'nama' => 'Nadia Putri',
            'nim' => '2810011999',
            'email' => 'nadia@kampus.ac.id',
        ]);

        $this->get('/mahasiswa?search=2810011566')
            ->assertOk()
            ->assertSee('Raka Pratama')
            ->assertDontSee('Nadia Putri');

        $this->get('/mahasiswa?search=nadia@kampus.ac.id')
            ->assertOk()
            ->assertSee('Nadia Putri')
            ->assertDontSee('Raka Pratama');

        $this->get('/mahasiswa?search=Raka')
            ->assertOk()
            ->assertSee('Raka Pratama')
            ->assertDontSee('Nadia Putri');
    }
}
