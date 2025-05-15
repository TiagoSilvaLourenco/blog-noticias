<?php

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\deleteJson;

uses(RefreshDatabase::class);

describe('Tags Feature', function () {
    it('admin and editor can create tag', function () {
        foreach (['admin', 'editor'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            actingAs($user)
                ->postJson('/api/tags', [
                    'name' => 'Nova Tag',
                    'slug' => 'nova-tag',
                ])
                ->assertCreated();
        }
    });

    it('user cannot create tag', function () {
        $user = User::factory()->create(['role' => 'user']);

        actingAs($user)
            ->postJson('/api/tags', [
                'name' => 'Tag Bloqueada',
                'slug' => 'tag-bloqueada',
            ])
            ->assertForbidden();
    });

    it('admin and editor can edit tag', function () {
        foreach (['admin', 'editor'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $tag = Tag::factory()->create();

            actingAs($user)
                ->putJson("/api/tags/{$tag->id}", [
                    'name' => 'Editada',
                ])
                ->assertOk();
        }
    });

    it('user cannot edit tag', function () {
        $user = User::factory()->create(['role' => 'user']);
        $tag = Tag::factory()->create();

        actingAs($user)
            ->putJson("/api/tags/{$tag->id}", [
                'name' => 'Proibida',
            ])
            ->assertForbidden();
    });
});

