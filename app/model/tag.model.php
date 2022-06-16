<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Tag extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única de la etiqueta'
			],
			'id_user' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'user.id',
				'comment'  => 'Id del usuario que crea la etiqueta'
			],
			'name' => [
				'type'     => OModel::TEXT,
				'nullable' => false,
				'default'  => null,
				'size'     => 100,
				'comment'  => 'Texto de la etiqueta'
			],
			'slug' => [
				'type'     => OModel::TEXT,
				'nullable' => false,
				'default'  => null,
				'size'     => 100,
				'comment'  => 'Slug del texto de la etiqueta'
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'     => OModel::UPDATED,
				'nullable' => true,
				'default'  => null,
				'comment'  => 'Fecha de última modificación del registro'
			]
		];

		parent::load($model);
	}

	/**
	 * Devuelve los datos de la tag como un array
	 *
	 * @return array Datos de la tag
	 */
	public function toArray(): array {
		return [
			'id'        => $this->get('id'),
			'name'      => $this->get('name'),
			'slug'      => $this->get('slug'),
			'createdAt' => $this->get('created_at', 'd/m/Y'),
			'updatedAt' => $this->get('updated_at', 'd/m/Y')
		];
	}
}
