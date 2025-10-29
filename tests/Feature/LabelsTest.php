<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Label;
use App\Models\User;

class LabelsTest extends TestCase
{
    use RefreshDatabase;
    private $label;

    public function setUp(): void
    {
        parent::setUp();
        $this->label = new Label();
        $this->label->name = 'Тестовая метка';
        $this->label->save();
        $this->user = User::factory()->create();
    }

    public function test_labels_screen_can_be_rendered(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertStatus(200);
    }

    public function test_create_label(): void
    {
        $response = $this->get(route('labels.create'));
        $response->assertStatus(403);

        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('labels.create'));
        $response->assertStatus(200);

        $response = $this->post(route('labels.store'), [
            'name' => 'Новая тестовая метка',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('labels.index'));

    }

    public function test_edit_label(): void
    {
        $response = $this->get(route('labels.edit', $this->label));
        $response->assertStatus(403);

        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('labels.edit', $this->label));
        $response->assertStatus(200);

        $response = $this->patch(route('labels.update', $this->label), [
            'name' => 'Измененная тестовая метка',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('labels.index'));

    }

    public function test_delete_label(): void
    {
        $response = $this->delete(route('labels.destroy', $this->label));
        $response->assertStatus(403);

        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->delete(route('labels.destroy', $this->label));

        $response->assertStatus(302);

        $response->assertRedirect(route('labels.index'));

    }
}
