<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    private $status;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->status = new TaskStatus();
        $this->status->name = 'Тестовый статус';
        $this->status->save();

        $this->task = new Task();
        $this->task->name = 'Тестовая задача';
        $this->task->status_id = $this->status->id;
        $this->task->creator_by_id = $this->user->id;
        $this->task->save();
    }

    public function test_task_screen_can_be_rendered(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
    }

    public function test_create_task(): void
    {
        $response = $this->get(route('tasks.create'));
        $response->assertStatus(403);

        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('tasks.create'));
        $response->assertStatus(200);

        $response = $this->post(route('tasks.store'), [
            'name' => 'Новая тестовая задача',
            'status_id' => (string) $this->status->id,
            'creator_by_id' => $this->user->id,
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('tasks.index'));

    }

    public function test_edit_task(): void
    {
        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('tasks.edit', $this->task));
        $response->assertStatus(200);

        $response = $this->patch(route('tasks.update', $this->task), [
            'name' => 'Измененная задача',
            'status_id' => (string) $this->status->id,
            'creator_by_id' => $this->user->id,

        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('tasks.index'));

        $response = $this->actingAs($this->user)->post('/logout');

        $response = $this->get(route('tasks.edit', $this->task));
        $response->assertStatus(403);

    }

    public function test_delete_task(): void
    {
        $response = $this->delete(route('tasks.destroy', $this->task));
        $response->assertStatus(403);

        $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->delete(route('tasks.destroy', $this->task));

        $response->assertStatus(302);

        $response->assertRedirect(route('tasks.index'));

    }
}
