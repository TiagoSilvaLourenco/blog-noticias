<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['code' => 'home_top',          'label' => 'Top (Home)',             'width' => 970, 'height' => 200],
            ['code' => 'home_middle',       'label' => 'Middle (Home)',          'width' => 728, 'height' => 90],
            ['code' => 'home_cards',        'label' => 'Cards (Home)',           'width' => 728, 'height' => 90],
            ['code' => 'home_middle_sidebar','label' => 'Middle Sidebar (Home)', 'width' => 300, 'height' => 600],
            ['code' => 'home_bottom',       'label' => 'Bottom (Home)',          'width' => 728, 'height' => 90],
            ['code' => 'home_bottom_sidebar','label' => 'Bottom Sidebar (Home)', 'width' => 300, 'height' => 600],
            ['code' => 'home_footer',       'label' => 'Footer (Home)',          'width' => 970, 'height' => 90],
            ['code' => 'post_top',          'label' => 'Top (Post)',             'width' => 970, 'height' => 90],
            ['code' => 'post_top_sidebar',  'label' => 'Top Sidebar (Post)',     'width' => 300, 'height' => 600],
            ['code' => 'post_middle',       'label' => 'Middle (Post)',          'width' => 728, 'height' => 90],
            ['code' => 'post_middle_sidebar','label' => 'Middle Sidebar (Post)', 'width' => 300, 'height' => 600],
            ['code' => 'post_footer',       'label' => 'Footer (Post)',          'width' => 970, 'height' => 90],
        ];

        foreach ($positions as $pos) {
            Position::updateOrCreate(
                ['code' => $pos['code']],
                $pos
            );
        }
    }
}
