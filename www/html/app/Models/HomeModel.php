<?php

namespace App\Models;

use App\Core\Model;

class HomeModel extends Model
{
    public function getWelcomeMessage()
    {
        return "Welcome to the MVC Home Page!";
    }
}
