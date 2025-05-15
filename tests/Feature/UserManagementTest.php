<?php


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

describe('User Management', function () {

    it('only admin can create users', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $editor = User::factory()->create(['role' => 'editor']);

        actingAs($admin)
            ->postJson('/api/users', [
                'name' => 'Novo Usuário',
                'email' => 'novo@example.com',
                'password' => 'password',
            ])
            ->assertCreated();

        actingAs($editor)
            ->postJson('/api/users', [
                'name' => 'Outro Usuário',
                'email' => 'outro@example.com',
                'password' => 'password',
            ])
            ->assertForbidden();
    });

    it('new users have the role "user" by default', function () {
        $admin = User::factory()->create(['role' => 'admin']);

        actingAs($admin)
            ->postJson('/api/users', [
                'name' => 'Padrão',
                'email' => 'padrao@example.com',
                'password' => 'password',
            ])
            ->assertCreated();

        $created = User::where('email', 'padrao@example.com')->first();
        expect($created->role)->toBe('user');
    });

    it('only admin can edit other users', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $editor = User::factory()->create(['role' => 'editor']);
        $target = User::factory()->create();

        actingAs($editor)
            ->putJson("/api/users/{$target->id}", [
                'name' => 'Nome Editado',
            ])
            ->assertForbidden();

        actingAs($admin)
            ->putJson("/api/users/{$target->id}", [
                'name' => 'Nome Editado',
            ])
            ->assertOk();

        expect(User::find($target->id)->name)->toBe('Nome Editado');
    });

    it('users can edit their own profile but not others', function () {
        $user = User::factory()->create();
        $other = User::factory()->create();

        actingAs($user)
            ->putJson("/api/users/{$other->id}", ['name' => 'Hack'])->assertForbidden();

        actingAs($user)
            ->putJson("/api/users/{$user->id}", ['name' => 'Meu Nome'])->assertOk();

        expect(User::find($user->id)->name)->toBe('Meu Nome');
    });

    it('only admin can change user role', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $editor = User::factory()->create(['role' => 'editor']);
        $target = User::factory()->create();

        actingAs($editor)
            ->putJson("/api/users/{$target->id}/role", ['role' => 'admin'])->assertForbidden();

        actingAs($admin)
            ->putJson("/api/users/{$target->id}/role", ['role' => 'editor'])->assertOk();

        expect(User::find($target->id)->role)->toBe('editor');
    });

    it('users cannot be deleted', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create();

        actingAs($admin)
            ->deleteJson("/api/users/{$target->id}")
            ->assertStatus(405); // método não permitido, ou defina sua regra de negócio

        expect(User::find($target->id))->not()->toBeNull();
    });

    it('admin can update any password, users only their own', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $other = User::factory()->create();

        actingAs($user)
            ->putJson("/api/users/{$other->id}/password", [
                'password' => 'nova-senha'
            ])
            ->assertForbidden();

        actingAs($user)
            ->putJson("/api/users/{$user->id}/password", [
                'password' => 'minha-nova-senha'
            ])
            ->assertOk();

        actingAs($admin)
            ->putJson("/api/users/{$other->id}/password", [
                'password' => 'admin-altera'
            ])
            ->assertOk();
    });

});

