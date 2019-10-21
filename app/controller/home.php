<?php
class home extends OController{
  /*
   * Página temporal, sitio cerrado
   */
  public function closed($req){
    OUrl::goToUrl('https://logger.osumi.es');
  }

  /*
   * Página de error 404
   */
  public function notFound($req){
    OUrl::goToUrl('https://logger.osumi.es');
  }

  /*
   * Home pública
   */
  public function index($req){
    OUrl::goToUrl('https://logger.osumi.es');
  }
}
