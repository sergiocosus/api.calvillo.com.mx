<?php

namespace CalvilloComMx\Core\User;

use CalvilloComMx\Core\User;
use Illuminate\Database\Eloquent\Model;

class SocialToken extends Model
{
    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
