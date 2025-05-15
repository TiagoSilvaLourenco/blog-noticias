<?php

use App\Models\Ad;
use App\Models\User;
use function Pest\Laravel\actingAs;

describe('Ads Feature', function () {
    it('admin can create an ad', function () {
        $user = User::factory()->create(['role' => 'admin']);

        actingAs($user)
            ->postJson('/api/ads', [
                'title' => 'Anúncio de Teste',
                'content' => 'Conteúdo do anúncio',
                'starts_at' => now()->addDay()->toDateTimeString(),
                'ends_at' => now()->addDays(10)->toDateTimeString(),
            ])
            ->assertCreated();
    });

    it('only admin can create ads', function () {
        foreach (['editor', 'user'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            actingAs($user)
                ->postJson('/api/ads', [
                    'title' => 'Proibido',
                    'content' => 'Conteúdo bloqueado',
                ])
                ->assertForbidden();
        }
    });

    it('admin can edit, pause and delete ads', function () {
        $user = User::factory()->create(['role' => 'admin']);
        $ad = Ad::factory()->create();

        actingAs($user)
            ->putJson("/api/ads/{$ad->id}", ['title' => 'Atualizado'])
            ->assertOk();

        actingAs($user)
            ->putJson("/api/ads/{$ad->id}/pause")
            ->assertOk();

        actingAs($user)
            ->deleteJson("/api/ads/{$ad->id}")
            ->assertNoContent();
    });

    it('ad status is correct based on schedule', function () {
        $user = User::factory()->create(['role' => 'admin']);

        $activeAd = Ad::factory()->create([
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        $expiredAd = Ad::factory()->create([
            'starts_at' => now()->subDays(10),
            'ends_at' => now()->subDay(),
        ]);

        actingAs($user)
            ->getJson("/api/ads/{$activeAd->id}/status")
            ->assertOk()
            ->assertJsonPath('status', 'active');

        actingAs($user)
            ->getJson("/api/ads/{$expiredAd->id}/status")
            ->assertOk()
            ->assertJsonPath('status', 'expired');
    });
});
