<?php

namespace App\core;

class Controller {


    // 3yt L3a l view
    public function view($view, $data = []) {
        // check lview wach kayna
        if(file_exists('../app/view/' . $view . '.php')) {
            // Load  lview file
            require_once '../app/view/' . $view . '.php';
        } else {
            die("View " . $view . " does not exist");
        }
    }
}
