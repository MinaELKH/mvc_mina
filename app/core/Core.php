<?php

namespace App\core;

class Core {
    protected $currentController = 'HomeController';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
       $url = $this->getUrl();
        if (empty($url[0])) {
            $this->currentController = 'HomeController';
            $this->currentMethod = 'index';
        }
        // Look in controllers for first value
        if(isset($url[0]) ){
            // If exists, set as controller
            if(file_exists('../app/controller/' . ucwords($url[0]). 'Controller.php'))
            {
                $this->currentController = ucwords($url[0])."Controller";
                // Unset 0 Index
                unset($url[0]);

            }else {
                $this->currentController = 'NotFoundController';

            }

        }

        // Require the controller
//        var_dump($this->currentController);
//        die() ;
        require_once '../app/controller/'. $this->currentController . '.php';

        // Instantiate controller class with proper namespace
        $controllerClass = "App\\controller\\" . $this->currentController;
        $this->currentController = new $controllerClass();

        // Check for second part of url
        if (isset($url[1])  ) {
            if( method_exists($this->currentController, $url[1]))
            {
                $this->currentMethod = $url[1];
                unset($url[1]);
            } else {

                require_once '../app/controller/NotFoundController.php';
                $this->currentController = new \App\Controller\NotFoundController();
                $this->currentMethod = 'index';
            }

        }
        if(isset($url[2])){
            // Check to see if method exists in controller
            if(method_exists($this->currentController, $url[2])){
                $this->currentMethod = $url[2];
                // Unset 1 index
                unset($url[2]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
?>