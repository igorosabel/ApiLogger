<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class EntryTag extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'entry_tag';
		$model = [
			'id_entry' => [
				'type'    => OModel::PK,
				'incr'    => false,
				'ref'     => 'entry.id',
				'comment' => 'Id de la entrada que tiene la etiqueta'
			],
			'id_tag' => [
				'type'    => OModel::PK,
				'incr'    => false,
				'ref'     => 'tag.id',
				'comment' => 'Id de la etiqueta que se usa en la entrada'
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

		parent::load($table_name, $model);
	}
}