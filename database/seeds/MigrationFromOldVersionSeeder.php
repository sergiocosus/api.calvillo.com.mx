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

        $this->migrateLocalities();
        $this->migrateDirectories();

    }

    public function migrateLocalities() {
        $categories = [];
        $categorias = DB::connection('mysql2')->select('select * from localidadcat');
        $localidades = DB::connection('mysql2')->select('select * from localidades');


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

        foreach ($categorias as $index => $categoria)    {
            /** @var \CalvilloComMx\Core\Category $category */
            $category = $categories[$index];
            foreach ($categorias as $indexPadre => $categoriaPadre) {
                if ($categoriaPadre->id == $categoria->catId) {
                    $category->category()->associate($categories[$indexPadre]);
                    $category->save();
                }
            }
        }




        foreach($localidades as $localidad) {
            $locality = new \CalvilloComMx\Core\Category();
            $locality->title =  $localidad->titulo;
            $locality->link = strtolower($localidad->link);
            $locality->image_code = uniqid();
            $locality->description = $localidad->descripcion;

            if (!$localidad->visible) {
                $locality->deleted_at = \Carbon\Carbon::now() ;
            }
            $locality->save();

            foreach ($categorias as $indexPadre => $categoriaPadre) {
                if ($categoriaPadre->id == $localidad->catId) {
                    $locality->category()->associate($categories[$indexPadre]);
                    $locality->save();
                }
            }

        }
    }

    public function migrateDirectories() {
        $categories = [];
        $categorias = DB::connection('mysql2')->select('select * from directoriocat');
        $directorios = DB::connection('mysql2')->select('select * from directorio');


        foreach ($categorias as $categoria) {
            $category = new \CalvilloComMx\Core\Category();
            $category->title = $categoria->titulo;
            $category->image_code = uniqid();
            $category->link = strtolower($categoria->link) . '_dir';
            $category->description = $categoria->descripcion;
            $category->created_at = new Carbon\Carbon($categoria->fechaSubida,'America/Mexico_City');
            $category->title = $categoria->titulo;
            if (!$categoria->visible) {
                $category->deleted_at = \Carbon\Carbon::now() ;
            }
            $category->save();
            $categories[] = $category;
        }

        foreach ($categorias as $index => $categoria)    {
            /** @var \CalvilloComMx\Core\Category $category */
            $category = $categories[$index];
            foreach ($categorias as $indexPadre => $categoriaPadre) {
                if ($categoriaPadre->id == $categoria->catId) {
                    $category->category()->associate($categories[$indexPadre]);
                    $category->save();
                }
            }
        }




        foreach($directorios as $directorio) {
            $directory = new \CalvilloComMx\Core\Directory();
            $directory->title =  $directorio->titulo;
            $directory->link = strtolower(str_replace(" ","-",$directorio->titulo));
            $directory->image_code = uniqid();
            $directory->description = $directorio->descripcion;
            $directory->cellphone = $directorio->celular;
            $directory->phone = $directorio->telefono;
            $directory->address = $directorio->direccion;
            $directory->email = $directorio->email;
            $directory->website_url = $directorio->website;
            $directory->facebook_url = $directorio->facebook;
            $directory->youtube_url = $directorio->youtube;
            $directory->latitude = $directorio->latitud;
            $directory->longitude = $directorio->longitud;

            if (!$directorio->visible) {
                $directory->deleted_at = \Carbon\Carbon::now() ;
            }
            $directory->save();

            foreach ($categorias as $indexPadre => $categoriaPadre) {
                if ($categoriaPadre->id == $directorio->catId) {
                    $directory->categories()->attach($categories[$indexPadre]->id);
                    $directory->save();
                }
            }

        }
    }
}
