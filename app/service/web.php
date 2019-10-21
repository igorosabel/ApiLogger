<?php
  /*
   * Clase con funciones generales para usar a lo largo del sitio
   */
class webService extends OService{
  function __construct($controller=null){
    $this->setController($controller);
  }

  
}