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
            'title' => 'Categoría Principal',
            'image_code' => '',
            'description' => '',
            'priority' => 0,
            'link' => '',
        ]);
    }
}
