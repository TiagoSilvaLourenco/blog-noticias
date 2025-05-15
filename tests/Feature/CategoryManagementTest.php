<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\deleteJson;

uses(RefreshDatabase::class);

describe('Categories Feature', function () {
    it('admin and editor can create category', function () {
        foreach (['admin', 'editor'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            actingAs($user)
                ->postJson('/api/categories', [
                    'name' => 'Nova Categoria',
                    'slug' => 'nova-categoria',
                ])
                ->assertCreated();
        }
    });

    it('user cannot create category', function () {
        $user = User::factory()->create(['role' => 'user']);

        actingAs($user)
            ->postJson('/api/categories', [
                'name' => 'Categoria Bloqueada',
                'slug' => 'categoria-bloqueada',
            ])
            ->assertForbidden();
    });

    it('admin and editor can edit category', function () {
        foreach (['admin', 'editor'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $category = Category::factory()->create();

            actingAs($user)
                ->putJson("/api/categories/{$category->id}", [
                    'name' => 'Editada',
                ])
                ->assertOk();
        }
    });

    it('user cannot edit category', function () {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();

        actingAs($user)
            ->putJson("/api/categories/{$category->id}", [
                'name' => 'Proibida',
            ])
            ->assertForbidden();
    });
});
