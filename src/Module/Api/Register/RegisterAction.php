<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Register;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OToken;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\App\Component\Model\User\UserComponent;

class RegisterAction extends OAction {
  public string $status = 'ok';
  public ?UserComponent $user = null;

	/**
	 * Función para registrar un nuevo usuario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$username   = $req->getParamString('username');
		$pass       = $req->getParamString('pass');
		$this->user = new UserComponent(['User' => null]);

		if (is_null($username) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$u = new User();
			if ($u->find(['username' => $username])) {
				$this->status = 'error';
			}
			else {
				$u->set('username', $username);
				$u->set('pass', password_hash($pass, PASSWORD_BCRYPT));
				$u->save();

				$id = $u->get('id');

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id', $id);
				$tk->addParam('username', $username);
				$token = $tk->getToken();
				$u->setToken($token);

				$this->user->setValue('User', $u);
			}
		}
	}
}
