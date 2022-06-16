<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OToken;
use OsumiFramework\App\Model\User;

#[OModuleAction(
	url: '/login'
)]
class loginAction extends OAction {
	/**
	 * Función para iniciar sesión
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
				if (password_verify($pass, $u->get('pass'))) {
					$id = $u->get('id');

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id',   $id);
					$tk->addParam('username', $username);
					$tk->addParam('exp', time() + (24 * 60 * 60));
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
}
