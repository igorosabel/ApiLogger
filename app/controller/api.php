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
    $username = OTools::getParam('username', $req['params'], false);
    $pass     = OTools::getParam('pass',     $req['params'], false);

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
    $username = OTools::getParam('username', $req['params'], false);
    $pass     = OTools::getParam('pass',     $req['params'], false);

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
    if ($req['loginFilter']['status']!=='ok'){
      $status = 'error';
    }
    $list = '[]';

    if ($status=='ok'){
      $id_user = $req['loginFilter']['id'];
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
    $id     = OTools::getParam('id', $req['params'], false);
    if ($req['loginFilter']['status']!=='ok' || $id===false){
      $status = 'error';
    }
    $entry = 'null';

    if ($status=='ok'){
      $e = new Entry();
      if ($e->find(['id'=>$id])){
        if ($e->get('id_user')==$req['loginFilter']['id']){
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
   * Función para obtener el detalle de una entrada pública
   */
  function getPublicEntry($req){
    $status = 'ok';
    $id     = OTools::getParam('id', $req['params'], false);
    $entry = 'null';

    if ($status=='ok'){
      $e = new Entry();
      if ($e->find(['id'=>$id])){
        if ($e->get('is_public')){
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
    if ($req['loginFilter']['status']!=='ok'){
      $status = 'error';
    }
    $list = '[]';

    if ($status=='ok'){
      $id_user = $req['loginFilter']['id'];
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
	  if ($req['loginFilter']['status']!=='ok'){
        $status = 'error';
      }

      if ($status=='ok'){
	    $id    = OTools::getParam('id',    $req['params'], false);
        $title = OTools::getParam('title', $req['params'], false);
        $body  = OTools::getParam('body',  $req['params'], false);
        $tags  = OTools::getParam('tags',  $req['params'], false);

        if ($id===false || $title===false || $body===false || $tags===false){
	        $status = 'error';
        }
        else{
	        $entry = new Entry();
	        if ($id!==null){
		        $entry->find(['id'=>$id]);
	        }
	        $entry->set('id_user', $req['loginFilter']['id']);
	        $entry->set('title',   $title);
	        $entry->set('slug',    OTools::slugify($title));
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
  function getTagEntries($req){
	$status = 'ok';
	if ($req['loginFilter']['status']!=='ok'){
	  $status = 'error';
	}
	$tag  = 'null';
	$list = '[]';

	if ($status=='ok'){
      $id = OTools::getParam('id', $req['params'], false);
      if ($id===false){
	      $status = 'error';
      }
      else{
	      $t = new Tag();
	      $t->find(['id'=>$id]);
	      $tag = json_encode($t->toArray());
	      $list = $this->web_service->getTagEntries($id);
      }
	}

	$this->getTemplate()->add('status', $status);
	$this->getTemplate()->add('tag',    $tag,  'nourlencode');
    $this->getTemplate()->add('list',   $list, 'nourlencode');
  }

  /*
   * Función para borrar una entrada
   */
  function deleteEntry($req){
	$status = 'ok';
	if ($req['loginFilter']['status']!=='ok'){
	  $status = 'error';
	}

	if ($status=='ok'){
      $id = OTools::getParam('id', $req['params'], false);
      if ($id===false){
	      $status = 'error';
      }
      else{
	    $entry = new Entry();
	    if ($entry->find(['id'=>$id])){
		   if ($entry->get('id_user')==$req['loginFilter']['id']){
			   $entry->deleteFull();
			   $this->web_service->cleanEmptyTags($req['loginFilter']['id']);
		   }
		   else{
			   $status = 'error';
		   }
	    }
	    else{
		    $status = 'error';
	    }
	  }
	}

	$this->getTemplate()->add('status', $status);
  }

  /*
   * Función para obtener las fotos de una entrada concreta
   */
  function getPhotos($req){
	$status = 'ok';
	if ($req['loginFilter']['status']!=='ok'){
	  $status = 'error';
	}
	$list = '[]';

	if ($status=='ok'){
      $id = OTools::getParam('id', $req['params'], false);
      if ($id===false){
	      $status = 'error';
      }
      else{
	    $entry = new Entry();
	    if ($entry->find(['id'=>$id])){
		   if ($entry->get('id_user')==$req['loginFilter']['id']){
			   $list = json_encode($entry->getPhotos());
		   }
		   else{
			   $status = 'error';
		   }
	    }
	    else{
		    $status = 'error';
	    }
	  }
	}

	$this->getTemplate()->add('status', $status);
	$this->getTemplate()->add('list',   $list, 'nourlencode');
  }

  /*
   * Función para añadir una foto a una entrada
   */
  function uploadPhoto($req){
	$status = 'ok';
	if ($req['loginFilter']['status']!=='ok'){
	  $status = 'error';
	}
	$id = 'null';
	$created_at = 'null';
	$updated_at = 'null';

	if ($status=='ok'){
      $id    = OTools::getParam('id',    $req['params'], false);
      $photo = OTools::getParam('photo', $req['params'], false);
      if ($id===false || $photo===false){
	      $status = 'error';
      }
      else{
	    $entry = new Entry();
	    if ($entry->find(['id'=>$id])){
		   if ($entry->get('id_user')==$req['loginFilter']['id']){
			   $new_photo = $this->web_service->addPhoto($entry, $photo);

			   $id = $new_photo['id'];
			   $created_at = '"'.$new_photo['createdAt'].'"';
			   $updated_at = '"'.$new_photo['updatedAt'].'"';
		   }
		   else{
			   $status = 'error';
		   }
	    }
	    else{
		    $status = 'error';
	    }
	  }
	}

	$this->getTemplate()->add('status',     $status);
	$this->getTemplate()->add('id',         $id,         'nourlencode');
	$this->getTemplate()->add('created_at', $created_at, 'nourlencode');
	$this->getTemplate()->add('updated_at', $updated_at, 'nourlencode');
  }

  /*
   * Función para obtener una foto
   */
  function getEntryPhoto($req){
	$id = OTools::getParam('id', $req, false);

	if ($id===false){
		echo 'error';
		exit();
	}
	else{
		$p = new Photo();
		if ($p->find(['id'=>$id])){
			$photo_data = $p->getImage();
			header('Content-type: '.$photo_data['type']);
			echo base64_decode($photo_data['image']);
			exit();
		}
		else{
			echo 'error';
			exit();
		}
	}

	$this->getTemplate()->add('photo', $photo, 'nourlencode');
  }
}