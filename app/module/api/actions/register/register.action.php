<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OToken;
use OsumiFramework\App\Model\User;

#[OModuleAction(
	url: '/register'
)]
class registerAction extends OAction {
	/**
	 * Función para registrar un nuevo usuario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
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
			if ($u->find(['username' => $username])) {
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
}
