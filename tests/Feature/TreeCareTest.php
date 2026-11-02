<?php

namespace Tests\Feature;

use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TreeCareTest extends TestCase
{
    use RefreshDatabase;

    public function test_cuidado_exitoso(): void
    {
        $user = User::factory()->create();
        
        $tree = Tree::forceCreate([
            'user_id'  => $user->id,
            'name'     => 'Roble',
            'health'   => 50,
            'level'    => 0,
            'progress' => 0,
            'status'   => 'ACTIVE'
        ]);
        
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/trees/{$tree->id}/care");

        $response->assertOk()
            ->assertJsonPath('message', 'Cuidado aplicado con éxito')
            ->assertJsonStructure([
                'message',
                'tree' => ['id', 'level', 'health', 'progress', 'status', 'next_care_at'],
            ]);

        $tree->refresh();
        $this->assertSame(1, $tree->progress);
        $this->assertSame(70, $tree->health);
        $this->assertNotNull($tree->next_care_at);
        $this->assertNotNull($tree->last_cared_at);
    }

    public function test_bloqueo_por_cooldown(): void
    {
        $user = User::factory()->create();
        $tree = Tree::forceCreate([
            'user_id'  => $user->id,
            'name'     => 'Roble',
            'health'   => 50,
            'level'    => 0,
            'progress' => 0,
            'status'   => 'ACTIVE'
        ]);
        Sanctum::actingAs($user);

        $this->postJson("/api/trees/{$tree->id}/care")->assertOk();

        $this->postJson("/api/trees/{$tree->id}/care")
            ->assertStatus(409)
            ->assertJsonStructure(['error', 'next_care_at']);

        $this->assertSame(1, $tree->fresh()->progress);
    }

    public function test_ascenso_de_nivel(): void
    {
        $user = User::factory()->create();
        $tree = Tree::forceCreate([
            'user_id'  => $user->id,
            'name'     => 'Roble',
            'health'   => 50,
            'level'    => 0,
            'progress' => 0,
            'status'   => 'ACTIVE'
        ]);        
        Sanctum::actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson("/api/trees/{$tree->id}/care")->assertOk();

            Tree::whereKey($tree->id)->update(['next_care_at' => null]);
        }

        $tree->refresh();
        $this->assertSame(1, $tree->level);
        $this->assertSame(0, $tree->progress);
    }

    public function test_evolucion_estado_mature(): void
    {
        $user = User::factory()->create();

        $tree = Tree::forceCreate([
            'user_id'  => $user->id,
            'name'     => 'Roble',
            'health'   => 50,
            'level'    => 9,
            'progress' => 4,
            'status'   => 'ACTIVE'
        ]);
        Sanctum::actingAs($user);

        $this->postJson("/api/trees/{$tree->id}/care")
            ->assertOk()
            ->assertJsonPath('tree.status', 'MATURE');

        $tree->refresh();
        $this->assertSame(10, $tree->level);
        $this->assertSame('MATURE', $tree->status);
    }

    public function test_bloque_cuidado_arbol_ajeno(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $tree = Tree::forceCreate([
            'user_id'  => $owner->id,
            'name'     => 'Roble',
            'health'   => 50,
            'level'    => 0,
            'progress' => 0,
            'status'   => 'ACTIVE'
        ]); 
        Sanctum::actingAs($intruder);

        $this->postJson("/api/trees/{$tree->id}/care")->assertForbidden();

        $this->assertSame(0, $tree->fresh()->progress);
    }
}