<?php
Class API{
  
  function load($class)
  {
    $this->$class = new $class;
  }
  
}
function __autoload($class_name) {
    require_once 'modules/'.$class_name.'/'.$class_name .'.class.php';
}

$API = new API();
$API->load("Forum");
$API->Forum->hello();
?>