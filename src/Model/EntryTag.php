<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;

class EntryTag extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_entry',
				type: OMODEL_PK,
				incr: false,
				ref: 'entry.id',
				comment: 'Id de la entrada que tiene la etiqueta'
			),
			new OModelField(
				name: 'id_tag',
				type: OMODEL_PK,
				incr: false,
				ref: 'tag.id',
				comment: 'Id de la etiqueta que se usa en la entrada'
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
}
