<?php

class Router {
    protected $routes = [];

        /**
     *add a GET route
     * @param string #method
     * @param string $uri
     * @param string $controller
     */

     public function registerRoute($method, $uri, $controller){
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
        ];
     }

    /**
     *add a GET route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function get($uri, $controller){
        $this->registerRoute("GET", $uri, $controller);
     }

    /**
     *add a POST route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function post($uri, $controller){
        $this->registerRoute("POST", $uri, $controller);
     }

    /**
     *add a PUT route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function put($uri, $controller){
        $this->registerRoute("PUT", $uri, $controller);
     }

    /**
     *add a DELETE route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function delete($uri, $controller){
        $this->registerRoute("DELETE", $uri, $controller);
     }

     /**
      * Reroute to an error page
      * @param int $statusCode
      * @return void
      */
     public function error($statusCode = 404){
        http_response_code($statusCode);
        loadView("error/{$statusCode}");
        exit;
     }

     /**
      * Route a request 
      *
      * @param string $uri
      * @param string $method
      * @return void
      */

      public function route($uri, $method){
        foreach($this->routes as $route){
            if ($route['uri'] === $uri && $route['method'] === $method){
                require basePath($route['controller']);
                return;
            };
            $this->error(404);

        };
      }

}