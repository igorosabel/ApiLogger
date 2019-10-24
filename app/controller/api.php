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
   * Función para obtener las entradas
   */
  function getEntries($req){
    $status = 'ok';
    if ($req['filter']['status']!=='ok'){
      $status = 'error';
    }
    $list = 'null';

    if ($status=='ok'){
      $id_user = $req['filter']['id'];
      $list = $this->web_service->getEntries($id_user);
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('list',   $list, 'nourlencode');
  }

  /*
   * Función para obtener el detalle de una entrada
   */
  function getEntry($req){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);
    if ($req['filter']['status']!=='ok' || $id===false){
      $status = 'error';
    }
    $entry = 'null';

    if ($status=='ok'){
      $e = new Entry();
      if ($e->find(['id'=>$id])){
        if ($e->get('id_user')==$req['filter']['id']){
          $entry = json_encode($e->toArray());
        }
        else{
          $status = 'error';
        }
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('entry',  $entry, 'nourlencode');
  }

  /*
   * Función para obtener la lista de tags de un usuario
   */
  function getTags($req){
    $status = 'ok';
    if ($req['filter']['status']!=='ok'){
      $status = 'error';
    }
    $list = 'null';

    if ($status=='ok'){
      $id_user = $req['filter']['id'];
      $list = $this->web_service->getTags($id_user);
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('list',   $list, 'nourlencode');
  }
 
  /*
   * Función para guardar una entrada
   */
  function saveEntry($req){
	  $status = 'ok';
	  if ($req['filter']['status']!=='ok'){
        $status = 'error';
      }
      
      if ($status=='ok'){
	    $id    = Base::getParam('id',    $req['url_params'], false);
        $title = Base::getParam('title', $req['url_params'], false);
        $body  = Base::getParam('body',  $req['url_params'], false);
        $tags  = Base::getParam('tags',  $req['url_params'], false);
        
        if ($id===false || $title===false || $body===false || $tags===false){
	        $status = 'error';
        }
        else{
	        $entry = new Entry();
	        if ($id!==null){
		        $entry->find(['id'=>$id]);
	        }
	        $entry->set('id_user', $req['filter']['id']);
	        $entry->set('title',   $title);
	        $entry->set('slug',    Base::slugify($title));
	        $entry->set('body',    $body);
	        $entry->save();
	        
	        $this->web_service->saveTags($entry, $tags);
        }
      }
	  
	  $this->getTemplate()->add('status', $status);
  }
 
  /*
   * Función para obtener las entradas con una tag concreta
   */
  function getTagEntries($req){}
}