<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class User extends OModel {
	#[OPK(
	  comment: 'Id único de cada usuario'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre de usuario',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $username;

	#[OField(
	  comment: 'Contraseña cifrada del usuario',
	  nullable: false,
	  max: 200,
	  default: null
	)]
	public ?string $pass;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	private ?string $token = null;

	public function setToken($t): void {
		$this->token = $t;
	}

	public function getToken(): ?string {
		return $this->token;
	}
}
