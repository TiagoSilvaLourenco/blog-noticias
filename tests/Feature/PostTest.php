<?php
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

describe('Posts Feature', function () {

    it('can create a post', function () {
        $user = User::factory()->create(['role' => 'admin']);

        actingAs($user)
            ->postJson('/api/posts', [
                'title' => 'Título do Post',
                'slug' => 'titulo-do-post',
                'content' => 'Conteúdo do post',
                'category_id' => 1,
            ])
            ->assertCreated();

        expect(Post::first()->title)->toBe('Título do Post');
    });

    it('can edit a post', function () {
        $user = User::factory()->create(['role' => 'admin']);
        $post = Post::factory()->create();

        actingAs($user)
            ->putJson("/api/posts/{$post->id}", [
                'title' => 'Novo Título',
            ])
            ->assertOk();

        expect(Post::find($post->id)->title)->toBe('Novo Título');
    });

    it('can view a post', function () {
        $post = Post::factory()->create();

        getJson("/api/posts/{$post->id}")
            ->assertOk()
            ->assertJsonFragment([
                'title' => $post->title,
            ]);
    });

    it('can delete a post', function () {
        $user = User::factory()->create(['role' => 'admin']);
        $post = Post::factory()->create();

        actingAs($user)
            ->deleteJson("/api/posts/{$post->id}")
            ->assertNoContent();

        expect(Post::find($post->id))->toBeNull();
    });

    it('can schedule a post', function () {
        $user = User::factory()->create(['role' => 'admin']);
        $post = Post::factory()->create();

        actingAs($user)
            ->putJson("/api/posts/{$post->id}", [
                'status' => 'scheduled',
                'published_at' => now()->addDays(1),
            ])
            ->assertOk();

        expect(Post::find($post->id)->status)->toBe('scheduled');
    });

    it('can save post as draft', function () {
        $user = User::factory()->create(['role' => 'admin']);

        actingAs($user)
            ->postJson('/api/posts', [
                'title' => 'Rascunho',
                'slug' => 'rascunho',
                'content' => 'Conteúdo',
                'status' => 'draft',
                'category_id' => 1,
            ])
            ->assertCreated();

        expect(Post::where('slug', 'rascunho')->first()->status)->toBe('draft');
    });

});
