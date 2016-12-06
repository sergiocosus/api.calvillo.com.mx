<?php

use Illuminate\Database\Seeder;

class MigrationFromOldVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fotos = DB::connection('mysql2')->select('select * from fotos');
        $categorias = DB::connection('mysql2')->select('select * from categorias');


        $categories = [];
        foreach ($categorias as $categoria) {
            $category = new \CalvilloComMx\Core\Category();
            $category->title = $categoria->titulo;
            $category->image_code = uniqid();
            $category->link = strtolower($categoria->link);
            $category->description = $categoria->descripcion;
            $category->created_at = new Carbon\Carbon($categoria->fechaSubida,'America/Mexico_City');
            $category->title = $categoria->titulo;
            if (!$categoria->visible) {
                $category->deleted_at = \Carbon\Carbon::now() ;
            }
            $category->save();
            $categories[] = $category;
        }

        foreach ($categorias as $index => $categoria) {
            /** @var \CalvilloComMx\Core\Category $category */
            $category = $categories[$index];
            foreach ($categorias as $indexPadre => $categoriaPadre) {
                if ($categoriaPadre->id == $categoria->catId) {
                    $category->category()->associate($categories[$indexPadre]);
                    $category->save();
                }
            }
        }

        foreach($fotos as $foto) {
            $taken_at = new Carbon\Carbon($foto->fechaTomada,'America/Mexico_City');
            if($taken_at < \Carbon\Carbon::now()->subYears(10)){
                $taken_at = null;
            }

            $picture = new \CalvilloComMx\Core\Picture();
            $picture->title =  $foto->titulo;
            $picture->link = strtolower($foto->link);
            $picture->image_code = uniqid();
            $picture->description = $foto->descripcion;
            $picture->created_at = new Carbon\Carbon($foto->fechaSubida,'America/Mexico_City');
            $picture->taken_at = $taken_at;
            if (!$foto->visible) {
                $picture->deleted_at = \Carbon\Carbon::now() ;
            }
            $picture->save();

            foreach ($categorias as $indexPadre => $categoriaPadre) {
                if ($categoriaPadre->id == $foto->catID) {
                    $picture->categories()->attach($categories[$indexPadre]->id, [
                        'priority' => $foto->prioridad < 0 ? 0 : $foto->prioridad
                    ]);
                }
            }

        }
    }
}
