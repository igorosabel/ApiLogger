<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;

class Photo extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id única de cada foto'
			),
			new OModelField(
				name: 'id_entry',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'entry.id',
				comment: 'Id de la entrada en la que va la foto'
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				nullable: true,
				default: null,
				comment: 'Fecha de última modificación del registro'
			)
		);


		parent::load($model);
	}

	/**
	 * Obtiene el contenido de la foto
	 *
	 * @return string Contenido de la foto en formato Base64
	 */
	public function getData(): string {
		global $core;

		$route = $core->config->getDir('photos').$this->get('id');
		return file_get_contents($route);
	}

	/**
	 * Borra una foto por completo, el archivo y el registro
	 *
	 * @return bool Devuelve si la foto ha sido borrada correctamente o no
	 */
	public function deleteFull(): bool {
		global $core;

		$route = $core->config->getDir('photos').$this->get('id');
		if (file_exists($route)) {
			unlink($route);
			$this->delete();
			return true;
		}

		return false;
	}
}
