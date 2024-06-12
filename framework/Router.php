<?php

namespace Framework;

use App\controllers\ErrorController;
use Framework\middleware\Authorise;

class Router {
    protected $routes = [];

        /**
     *add a GET route
     * @param string #method
     * @param string $uri
     * @param string $action
     */

     public function registerRoute($method, $uri, $action, $middleware = []){
      list($controller, $controllerMethod) = explode("@", $action);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware' => $middleware,
        ];
     }

    /**
     *add a GET route
     * 
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */

     public function get($uri, $controller, $middleware = []){
        $this->registerRoute("GET", $uri, $controller, $middleware);
     }

    /**
     *add a POST route
     * 
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */

     public function post($uri, $controller, $middleware = []){
        $this->registerRoute("POST", $uri, $controller, $middleware);
     }

    /**
     *add a PUT route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function put($uri, $controller, $middleware = []){
        $this->registerRoute("PUT", $uri, $controller, $middleware);
     }

    /**
     *add a DELETE route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */

     public function destroy($uri, $controller, $middleware = []){
        $this->registerRoute("DELETE", $uri, $controller, $middleware);
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


         $requestMethod = $_SERVER['REQUEST_METHOD'];

         if ($requestMethod === "POST" && isset($_POST['_method'])){
            $requestMethod = strtoupper($_POST['_method']);
            $method = $requestMethod;
         }

        foreach($this->routes as $route){

            if ($route['uri'] === $uri && $route['method'] === $method){

               foreach ($route['middleware'] as $middleware) {
                  (new Authorise())->handle($middleware);
               }

               $controller = "App\\controllers\\" . $route["controller"];
               $controllerMethod = $route["controllerMethod"];
               
               $controllerInstance = new $controller();
               $controllerInstance->$controllerMethod();
               return;

               //  require basePath($route['controller']);
               //  return;
            };
        };
        ErrorController::notFound();
      }

}

