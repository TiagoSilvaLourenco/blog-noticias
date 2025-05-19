<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Criar usuários fixos
        $tiago = User::updateOrCreate(
            ['email' => 'tiago@test.com'],
            [
                'name' => 'Tiago',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => true,
            ]
        );

        $editor = User::updateOrCreate(
            ['email' => 'editor@test.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('12345678'),
                'role' => 'editor',
                'status' => true,
            ]
        );

        // Categorias fixas
        $categorias = [
            'Política', 'Esportes', 'Saúde', 'Policial', 'Cidades', 'Utilidade Pública'
        ];

        foreach ($categorias as $cat) {
            Category::updateOrCreate(['name' => $cat], [
                'slug' => Str::slug($cat),
                'description' => $cat
            ]);
        }

        // Tags temáticas
        $tags = [
            'Governo', 'Eleições', 'Orçamento Público', 'Transparência', 'Prefeitura',
            'Esporte Amador', 'Campeonato', 'Hospital', 'Vacinação', 'Unidade de Saúde',
            'Polícia Civil', 'Ocorrência', 'Trânsito', 'Mobilidade Urbana',
            'Educação Pública', 'Saneamento', 'Projetos Sociais', 'Cultura', 'Feira Livre',
            'Audiência Pública'
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(['name' => $tag], [
                'slug' => Str::slug($tag)
            ]);
        }

        // IDs fixos para usuários e categorias
        $userIds = [$tiago->id, $editor->id];
        $categoryIds = Category::pluck('id')->toArray();
        $tagIds = Tag::pluck('id')->toArray();

        // Gerar 50 posts
        Post::factory(50)->create()->each(function ($post) use ($userIds, $categoryIds, $tagIds) {
            $post->user_id = collect($userIds)->random();
            $post->category_id = collect($categoryIds)->random();
            $post->save();

            $tags = collect($tagIds)->random(rand(2, 4));
            $post->tags()->attach($tags);
        });

         // Seed de posições
        $this->call(PositionSeeder::class);

        // Gerar anúncios
        Ad::factory(5)->create();


    }
}
