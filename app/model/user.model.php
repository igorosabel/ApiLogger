<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class User extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada usuario'
			],
			'username' => [
				'type'     => OModel::TEXT,
				'nullable' => false,
				'default'  => null,
				'size'     => 50,
				'comment'  => 'Nombre de usuario'
			],
			'pass' => [
				'type'     => OModel::TEXT,
				'nullable' => false,
				'default'  => null,
				'size'     => 200,
				'comment'  => 'Contraseña cifrada del usuario'
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
}
