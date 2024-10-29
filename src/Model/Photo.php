<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Photo extends OModel {
	#[OPK(
	  comment: 'Id única de cada foto'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id de la entrada en la que va la foto',
	  nullable: false,
	  ref: 'entry.id',
	  default: null
	)]
	public ?int $id_entry;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Obtiene el contenido de la foto
	 *
	 * @return string Contenido de la foto en formato Base64
	 */
	public function getData(): string {
		global $core;

		$route = $core->config->getDir('photos') . $this->id;
		return file_get_contents($route);
	}

	/**
	 * Borra una foto por completo, el archivo y el registro
	 *
	 * @return bool Devuelve si la foto ha sido borrada correctamente o no
	 */
	public function deleteFull(): bool {
		global $core;

		$route = $core->config->getDir('photos') . $this->id;
		if (file_exists($route)) {
			unlink($route);
			$this->delete();
			return true;
		}

		return false;
	}
}
