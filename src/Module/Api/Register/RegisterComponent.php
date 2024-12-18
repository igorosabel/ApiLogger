<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Register;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OToken;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\App\Component\Model\User\UserComponent;

class RegisterComponent extends OComponent {
  public string $status = 'ok';
  public ?UserComponent $user = null;

  public function __construct() {
    parent::__construct();
    $this->user = new UserComponent();
  }

	/**
	 * Función para registrar un nuevo usuario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$username   = $req->getParamString('username');
		$pass       = $req->getParamString('pass');

		if (is_null($username) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$u = User::findOne(['username' => $username]);
			if (!is_null($u)) {
				$this->status = 'error';
			}
			else {
        $u = User::create();
				$u->username = $username;
				$u->pass     = password_hash($pass, PASSWORD_BCRYPT);
				$u->save();

				$id = $u->id;

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id', $id);
				$tk->addParam('username', $username);
				$token = $tk->getToken();
				$u->setToken($token);

				$this->user->user = $u;
			}
		}
	}
}
