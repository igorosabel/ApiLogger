<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Tag extends OModel {
	#[OPK(
	  comment: 'Id única de la etiqueta'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del usuario que crea la etiqueta',
	  nullable: false,
	  ref: 'user.id',
	  default: null
	)]
	public ?int $id_user;

	#[OField(
	  comment: 'Texto de la etiqueta',
	  nullable: false,
	  max: 100,
	  default: null
	)]
	public ?string $name;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	private ?bool $is_public = null;

	public function isPublic(): bool {
		if (is_null($this->is_public)) {
			$db = new ODB();
			$sql = "SELECT * FROM `entry` WHERE `id` IN (SELECT `id_entry` FROM `entry_tag` WHERE `id_tag` = ?) LIMIT 0,1";
			$db->query($sql, [$this->id]);

			$res = $db->next();
			$entry = Entry::from($res);

			$this->is_public = $entry->is_public;
		}

		return $this->is_public;
	}

	private int $num = 0;

	public function setNum(int $num): void {
		$this->num = $num;
	}

	public function getNum(): int {
		return $this->num;
	}

	public function loadNum(): void {
		$num = EntryTag::count(['id_tag' => $this->id]);
		$this->setNum($num);
	}

	/**
	 * Devuelve los datos de la tag como un array
	 *
	 * @return array Datos de la tag
	 */
	public function toArray(): array {
		return [
			'id'        => $this->id,
			'name'      => $this->name,
			'slug'      => $this->slug,
			'createdAt' => $this->get('created_at', 'd/m/Y'),
			'updatedAt' => $this->get('updated_at', 'd/m/Y')
		];
	}
}
