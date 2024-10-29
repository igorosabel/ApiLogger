<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Tag;

class Entry extends OModel {
	#[OPK(
	  comment: 'Id única de cada entrada'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del usuario que crea la entrada',
	  nullable: false,
	  ref: 'user.id'
	)]
	public ?int $id_user;

	#[OField(
	  comment: 'Título de la entrada',
	  nullable: false,
	  max: 100
	)]
	public ?string $title;

	#[OField(
	  comment: 'Cuerpo de la entrada',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $body;

	#[OField(
	  comment: 'Indica si la entrada es pública 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $is_public;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

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
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res = $db->next()) {
			$tag = Tag::from($res);
			$list[] = $tag;
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
		$list = Photo::where(['id_entry' => $this->id]);
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
		$db->query($sql, [$this->id]);
		$this->delete();
	}
}
