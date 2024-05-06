<?php

/**
* Get the base path
*
* @param string $path
* @return string 
*/

function basePath($path) {
    return __DIR__ . "/" . $path;
}

/**
 * Load specific page view
 * 
 * @param string $name
 * @return void
 */

 function loadView($name) {
    $viewPath = basePath("views/{$name}.php");

    if (file_exists($viewPath)) {
        require $viewPath;
    }else{
        echo "ERROR: Specified view path: {$viewPath} does not exist";
    };
 }

 /**
 * Load specific page partial
 * 
 * @param string $name
 * @return void
 */

 function loadPartial($name) {

    $partialPath = basePath("views/partials/{$name}.php");

    if (file_exists($partialPath)) {
        require $partialPath;
    }else{
        echo "ERROR: Specified partial path: {$partialPath} does not exist";
    };

 }
 
 /**
  * Value Inspector
  *
  * @param mixed $value
  * @return void
  *
  */

function inspectValues($value) {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
};

 /**
  * Value Inspector & script halter
  *
  * @param mixed $value
  * @return void
  *
  */

  function inspectValueAndHold($value) {
    echo "<pre>";
    die(var_dump($value));
    echo "</pre>";
};