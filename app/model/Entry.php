<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\App\Model\Tag;

class Entry extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'entry';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única de cada entrada'
			],
			'id_user' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'user.id',
				'comment'  => 'Id del usuario que crea la entrada'
			],
			'title' => [
				'type'     => OModel::TEXT,
				'nullable' => false,
				'default'  => null,
				'size'     => 100,
				'comment'  => 'Título de la entrada'
			],
			'slug' => [
				'type'     => OModel::TEXT,
				'nullable' => false,
				'default'  => null,
				'size'     => 100,
				'comment'  => 'Slug del título de la entrada'
			],
			'body' => [
				'type'     => OModel::LONGTEXT,
				'nullable' => true,
				'default'  => null,
				'comment'  => 'Cuerpo de la entrada'
			],
			'is_public' => [
				'type'     => OModel::BOOL,
				'nullable' => false,
				'default'  => false,
				'comment'  => 'Indica si la entrada es pública 1 o no 0'
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
		$sql = "SELECT * FROM `tag` WHERE `id` IN (SELECT `id_tag` FROM `entry_tag` WHERE `id_entry` = ?) ORDER BY `name` ASC";
		$this->db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res = $this->db->next()) {
			$tag = new Tag();
			$tag->update($res);

			array_push($list, $tag->toArray());
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
		$sql = "SELECT * FROM `photo` WHERE `id_entry` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res = $this->db->next()) {
			$photo = new Photo();
			$photo->update($res);

			array_push($list, $photo->toArray());
		}

		$this->setPhotos($list);
	}

	/**
	 * Borra una entrada y sus tags relacionadas
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$sql = "DELETE FROM `entry_tag` WHERE `id_entry` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$this->delete();
	}

	/**
	 * Devuelve los datos de una entrada como un array
	 *
	 * @return array Datos de la entrada en formato array
	 */
	public function toArray(): array {
		return [
			'id'        => $this->get('id'),
			'title'     => $this->get('title'),
			'slug'      => $this->get('slug'),
			'body'      => $this->get('body'),
			'createdAt' => $this->get('created_at', 'd/m/Y'),
			'updatedAt' => $this->get('updated_at', 'd/m/Y'),
			'tags'      => $this->getTags()
		];
	}
}