<?php
class api extends OController{
  private $web_service;

  function __construct(){
    $this->web_service = new webService($this);
  }

  /*
   * Función para registrar un nuevo usuario
   */
  function register($req){
    $status   = 'ok';
    $username = Base::getParam('username', $req['url_params'], false);
    $pass     = Base::getParam('pass',     $req['url_params'], false);

    $id    = 'null';
    $token = '';

    if ($username===false || $pass===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $u = new User();
      if ($u->find(['username'=>$username])){
        $status = 'error';
      }
      else{
        $u->set('username', $username);
        $u->set('pass', password_hash($pass, PASSWORD_BCRYPT));
        $u->save();

        $id = $u->get('id');

        $tk = new OToken($this->getConfig()->getExtra('secret'));
        $tk->addParam('id',   $id);
        $tk->addParam('username', $username);
        $token = $tk->getToken();
      }

      $this->getTemplate()->add('status',   $status);
      $this->getTemplate()->add('id',       $id);
      $this->getTemplate()->add('username', $username);
      $this->getTemplate()->add('token',    $token);
  }

  /*
   * Función para iniciar sesión
   */
  function login($req){
    $status   = 'ok';
    $username = Base::getParam('username', $req['url_params'], false);
    $pass     = Base::getParam('pass',     $req['url_params'], false);

    $id    = 'null';
    $token = '';

    if ($username===false || $pass===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $u = new User();
      if ($u->find(['username'=>$username])){
        if (password_verify($pass, $u->get('pass'))){
          $id = $u->get('id');

          $tk = new OToken($this->getConfig()->getExtra('secret'));
          $tk->addParam('id',   $id);
          $tk->addParam('username', $username);
          $tk->addParam('exp', mktime() + (24 * 60 * 60));
          $token = $tk->getToken();
        }
        else{
          $status = 'error';
        }
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status',     $status);
    $this->getTemplate()->add('id',         $id);
    $this->getTemplate()->add('username',   $username);
    $this->getTemplate()->add('token',      $token);
  }

  /*
   * Función para obtener las entradas de un día concreto
   */
  function getEntries($req){}
  /*
   * Función para obtener la lista de tags de un usuario
   */
  function getTags($req){}
  /*
   * Función para guardar una entrada
   */
  function saveEntry($req){}
  /*
   * Función para obtener las entradas con una tag concreta
   */
  function getTagEntries($req){}
}