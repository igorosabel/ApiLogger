<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;
use Osumi\OsumiFramework\DB\ODB;
use Osumi\OsumiFramework\App\Model\Tag;

class Entry extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id única de cada entrada'
			),
			new OModelField(
				name: 'id_user',
				type: OMODEL_NUM,
				nullable: false,
				ref: 'user.id',
				comment: 'Id del usuario que crea la entrada'
			),
			new OModelField(
				name: 'title',
				type: OMODEL_TEXT,
				nullable: false,
				size: 100,
				comment: 'Título de la entrada'
			),
			new OModelField(
				name: 'body',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: null,
				comment: 'Cuerpo de la entrada'
			),
			new OModelField(
				name: 'is_public',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si la entrada es pública 1 o no 0'
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

	private ?array $tags = null;

	/**
	 * Obtiene la lista de tags de una entrada
	 *
	 * @return array Lista de tags
	 */
	public function getTags(): array {
		if (is_null($this->tags)) {
			$this->loadTags();
		}
		return $this->tags;
	}

	/**
	 * Guarda la lista de tags
	 *
	 * @param array Lista de tags
	 *
	 * @return void
	 */
	public function setTags(array $tags): void {
		$this->tags = $tags;
	}

	/**
	 * Carga la lista de tags
	 *
	 * @return void
	 */
	public function loadTags(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `tag` WHERE `id` IN (SELECT `id_tag` FROM `entry_tag` WHERE `id_entry` = ?) ORDER BY `name` ASC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res = $db->next()) {
			$tag = new Tag();
			$tag->update($res);

			array_push($list, $tag);
		}

		$this->setTags($list);
	}

	private ?array $photos = null;

	/**
	 * Obtiene la lista de fotos de una entrada
	 *
	 * @return array Lista de fotos
	 */
	public function getPhotos(): array {
		if (is_null($this->photos)) {
			$this->loadPhotos();
		}
		return $this->photos;
	}

	/**
	 * Guarda la lista de fotos
	 *
	 * @param array Lista de fotos
	 *
	 * @return void
	 */
	public function setPhotos(array $photos): void {
		$this->photos = $photos;
	}

	/**
	 * Carga la lista de fotos de una entrada
	 *
	 * @return void
	 */
	public function loadPhotos(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `photo` WHERE `id_entry` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res = $db->next()) {
			$photo = new Photo();
			$photo->update($res);

			array_push($list, $photo);
		}

		$this->setPhotos($list);
	}

	/**
	 * Borra una entrada y sus tags relacionadas
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();
		$sql = "DELETE FROM `entry_tag` WHERE `id_entry` = ?";
		$db->query($sql, [$this->get('id')]);
		$this->delete();
	}
}
