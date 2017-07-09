<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 9/07/17
 * Time: 02:13 PM
 */

namespace CalvilloComMx\Core;


trait ImageUrlTrait
{
    protected function getImageUrl($key){
        if (env('STORAGE_LOCATION') == 's3') {
            return \Storage::disk('s3')->url($key);
        } else {
            return env('APP_URL').'/storage/'.$key;
        }
    }
}