<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Login;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OToken;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\App\Component\Model\User\UserComponent;

class LoginComponent extends OComponent {
  public string $status = 'ok';
  public ?UserComponent $user = null;

  public function __construct() {
    parent::__construct();
    $this->user = new UserComponent();
  }

	/**
	 * Función para iniciar sesión
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$username = $req->getParamString('username');
		$pass     = $req->getParamString('pass');

		if (is_null($username) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$u = User::findOne(['username' => $username]);
			if (!is_null($u)) {
				if (password_verify($pass, $u->pass)) {
					$id = $u->id;

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id', $id);
					$tk->addParam('username', $username);
					$tk->addParam('exp', time() + (24 * 60 * 60));
					$token = $tk->getToken();
					$u->setToken($token);

					$this->user->user = $u;
				}
				else {
					$this->status = 'error';
				}
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
