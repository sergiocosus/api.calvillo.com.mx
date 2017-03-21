<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get(User $user)
    {
        return $this->success(compact('user'));
    }
}
