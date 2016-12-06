<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \CalvilloComMx\Core\Category::create([
            'title' => 'Main Category',
            'image_code' => '',
            'description' => '',
            'priority' => 0,
            'link' => '',
        ]);
    }
}
