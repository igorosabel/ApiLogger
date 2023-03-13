<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class Tag extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id única de la etiqueta'
			),
			new OModelField(
				name: 'id_user',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'user.id',
				comment: 'Id del usuario que crea la etiqueta'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 100,
				comment: 'Texto de la etiqueta'
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

	private ?bool $is_public = null;

	public function isPublic(): bool {
		if (is_null($this->is_public)) {
			$db = new ODB();
			$sql = "SELECT * FROM `entry` WHERE `id` IN (SELECT `id_entry` FROM `entry_tag` WHERE `id_tag` = ?) LIMIT 0,1";
			$db->query($sql, [$this->get('id')]);

			$res = $db->next();
			$entry = new Entry();
			$entry->update($res);

			$this->is_public = $entry->get('is_public');
		}

		return $this->is_public;
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
