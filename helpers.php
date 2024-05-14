<?php

/**
* Get the base path
*
* @param string $path
* @return string 
*/

//---------------------------------------------------------------------------FETCHING FILES

function basePath($path) {
    return __DIR__ . "/" . $path;
}

/**
 * Load specific page view
 * 
 * @param string $name
 * @param array $data
 * @return void
 */

 function loadView($name, $data = []) {
    $viewPath = basePath("views/{$name}.php");

    if (file_exists($viewPath)) {
        extract($data);
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


//---------------------------------------------------------------------------INSPECTIONG VALUES
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
};

//---------------------------------------------------------------------------STRING FORMATTERS

/**
 * Format salary string into standard /w regards to dollar currency
 * 
 * @param string $salary
 * @return string Formatter Salary
 */

 function formatSalary($salary){
    return "$".number_format($salary, 2, ".", ",");
 };