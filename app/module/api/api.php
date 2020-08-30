<?php declare(strict_types=1);
/**
 * @type json
 * @prefix /api
*/
class api extends OModule {
	private ?webService $web_service = null;

	function __construct() {
		$this->web_service = new webService();
	}

	/**
	 * Función para registrar un nuevo usuario
	 *
	 * @url /register
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function register(ORequest $req): void {
		$status   = 'ok';
		$username = $req->getParamString('username');
		$pass     = $req->getParamString('pass');

		$id    = 'null';
		$token = '';

		if (is_null($username) || is_null($pass)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$u = new User();
			if ($u->find(['username'=>$username])) {
				$status = 'error';
			}
			else {
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

	/**
	 * Función para iniciar sesión
	 *
	 * @url /login
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function login(ORequest $req): void {
		$status   = 'ok';
		$username = $req->getParamString('username');
		$pass     = $req->getParamString('pass');

		$id    = 'null';
		$token = '';

		if (is_null($username) || is_null($pass)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$u = new User();
			if ($u->find(['username'=>$username])) {
				if (password_verify($pass, $u->get('pass'))) {
					$id = $u->get('id');

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id',   $id);
					$tk->addParam('username', $username);
					$tk->addParam('exp', mktime() + (24 * 60 * 60));
					$token = $tk->getToken();
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',     $status);
		$this->getTemplate()->add('id',         $id);
		$this->getTemplate()->add('username',   $username);
		$this->getTemplate()->add('token',      $token);
	}

	/**
	 * Función para obtener las entradas
	 *
	 * @url /getEntries
	 * @filter loginFilter
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function getEntries(ORequest $req): void {
		$status = 'ok';
		$filter = $req->getFilter('loginFilter');

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list = '[]';

		if ($status=='ok') {
			$list = $this->web_service->getEntries($filter['id']);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $list, 'nourlencode');
	}

	/**
	 * Función para obtener el detalle de una entrada
	 *
	 * @url /getEntry
	 * @filter loginFilter
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function getEntry(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('loginFilter');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$entry = 'null';

		if ($status=='ok') {
			$e = new Entry();
			if ($e->find(['id'=>$id])) {
				if ($e->get('id_user')==$filter['id']) {
					$entry = json_encode($e->toArray());
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('entry',  $entry, 'nourlencode');
	}

	/**
	 * Función para obtener el detalle de una entrada pública
	 *
	 * @url /getPublicEntry
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function getPublicEntry(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$entry = 'null';

		if ($status=='ok') {
			$e = new Entry();
			if ($e->find(['id'=>$id])) {
				if ($e->get('is_public')) {
					$entry = json_encode($e->toArray());
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('entry',  $entry, 'nourlencode');
	}

	/**
	 * Función para obtener la lista de tags de un usuario
	 *
	 * @url /getTags
	 * @filter loginFilter
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function getTags(ORequest $req): void {
		$status = 'ok';
		$filter = $req->getFilter('loginFilter');

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list = '[]';

		if ($status=='ok') {
			$list = $this->web_service->getTags($filter['id']);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $list, 'nourlencode');
	}

	/**
	 * Función para guardar una entrada
	 *
	 * @url /saveEntry
	 * @filter loginFilter
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function saveEntry(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$title  = $req->getParamString('title');
		$body   = $req->getParamString('body');
		$tags   = $req->getParam('tags');
		$filter = $req->getFilter('loginFilter');

		if (is_null($title) || is_null($body) || is_null($tags) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$entry = new Entry();
			if (!is_null($id)) {
				$entry->find(['id'=>$id]);
			}
			$entry->set('id_user', $filter['id']);
			$entry->set('title',   $title);
			$entry->set('slug',    OTools::slugify($title));
			$entry->set('body',    $body);
			$entry->save();

			$this->web_service->saveTags($entry, $tags);
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para obtener las entradas con una tag concreta
	 *
	 * @url /getTagEntries
	 * @filter loginFilter
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function getTagEntries(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('loginFilter');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
		  $status = 'error';
		}
		$tag  = 'null';
		$list = '[]';

		if ($status=='ok') {
			$t = new Tag();
			$t->find(['id'=>$id]);
			$tag = json_encode($t->toArray());
			$list = $this->web_service->getTagEntries($id);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('tag',    $tag,  'nourlencode');
		$this->getTemplate()->add('list',   $list, 'nourlencode');
	}

	/**
	 * Función para borrar una entrada
	 *
	 * @url /deleteEntry
	 * @filter loginFilter
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function deleteEntry(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('loginFilter');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$entry = new Entry();
			if ($entry->find(['id'=>$id])) {
				if ($entry->get('id_user')==$filter['id']) {
					$entry->deleteFull();
					$this->web_service->cleanEmptyTags($req['loginFilter']['id']);
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para obtener las fotos de una entrada concreta
	 *
	 * @url /getPhotos
	 * @filter loginFilter
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function getPhotos(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('loginFilter');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list = '[]';

		if ($status=='ok') {
			$entry = new Entry();
			if ($entry->find(['id'=>$id])) {
				if ($entry->get('id_user')==$filter['id']) {
					$list = json_encode($entry->getPhotos());
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $list, 'nourlencode');
	}

	/**
	 * Función para obtener una foto
	 *
	 * @url /getEntryPhoto/:id
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function getEntryPhoto(ORequest $req): void {
		$id = $req->getParamInt('id');
		if (is_null($id)) {
			echo 'error';
			exit;
		}
		else {
			$p = new Photo();
			if ($p->find(['id'=>$id])) {
				$photo_data = $p->getImage();
				header('Content-type: '.$photo_data['type']);
				echo base64_decode($photo_data['image']);
				exit;
			}
			else {
				echo 'error';
				exit;
			}
		}

		$this->getTemplate()->add('photo', $photo, 'nourlencode');
	}

	/**
	 * Función para guardar una nueva foto
	 *
	 * @url /uploadPhoto
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function uploadPhoto(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$photo = $req->getParamString('photo');

		$id_photo   = 'null';
		$created_at = 'null';
		$updated_at = 'null';

		if (is_null($id) || is_null($photo)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$entry = new Entry();
			if ($entry->find(['id'=>$id])) {
				$result = $this->web_service->addPhoto($entry, $photo);

				$id_photo   = $result['id'];
				$created_at = '"'.$result['createdAt'].'"';
				$updated_at = '"'.$result['updatedAt'].'"';
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',     $status);
		$this->getTemplate()->add('id',         $id_photo);
		$this->getTemplate()->add('created_at', $created_at, 'nourlencode');
		$this->getTemplate()->add('updated_at', $updated_at, 'nourlencode');
	}

	/**
	 * Nueva acción deletePhoto
	 *
	 * @url /deletePhoto
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function deletePhoto(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$photo = new Photo();
			if ($photo->find(['id'=>$id])) {
				$photo->deleteFull();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',     $status);
	}
}