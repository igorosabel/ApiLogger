<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\EntryTag;
use Osumi\OsumiFramework\App\Model\Tag;
use Osumi\OsumiFramework\App\Model\Entry;
use Osumi\OsumiFramework\App\Model\Photo;

class WebService extends OService {
	/**
	 * Obtiene la lista de entradas de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return array Lista de entradas del usuario
	 */
	public function getEntries(int $id_user): array{
		return Entry::where(['id_user' => $id_user], ['order_by' => 'updated_at#desc']);
	}

	/**
	 * Obtiene la lista de tags de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return array Lista de tags del usuario
	 */
	public function getTags(int $id_user): array {
		$tags = Tag::where(['id_user' => $id_user], ['order_by' => 'updated_at#desc']);
		$list = [];

		foreach ($tags as $tag) {
			$tag->loadNum();
			$list[] = $tag;
		}

		return $list;
	}

	/**
	 * Guarda o actualiza la lista de tags de una entrada
	 *
	 * @param Entry $entry Entrada a actualizar
	 *
	 * @param array $tags Lista de tags actual de la entrada
	 *
	 * @return void
	 */
	public function saveTags(Entry $entry, array $tags): void {
		$db = new ODB();
		$entry_tags = $entry->getTags();
		$to_be_checked = [];
		// Busco etiquetas de la entrada y las "marco" para borrar
		foreach ($entry_tags as $entry_tag) {
			$to_be_checked[$entry_tag->id] = false;
		}

		foreach ($tags as $t) {
			$tag = Tag::findOne(['id_user' => $entry->id_user, 'name' => $t['name']]);
			// Busco la etiqueta, si no existe creo una nueva
			//echo "Busco la etiqueta, si no existe creo una nueva.\n";
			if (is_null($tag)) {
				$tag = Tag::create();
				$tag->id_user = $entry->id_user;
				$tag->name    = $t['name'];
				$tag->save();
			}

			$et = EntryTag::findOne(['id_entry' => $entry->id, 'id_tag' => $tag->id]);
			// Si la entrada no tiene la etiqueta asociada se la añado
			if (is_null($et)) {
				$et = EntryTag::create();
				$et->id_entry = $entry->id;
				$et->id_tag   = $tag->id;
				$et->save();
				//echo "La etiqueta no tenía una entrada asociada.\n";
			}

			// Si la entrada ya tenía esta etiqueta asociada la marco para no borrarla
			if (array_key_exists($tag->id, $to_be_checked)) {
				$to_be_checked[$tag->id] = true;
			}
		}

		// Las tags que ya no estén asociadas borro la relación entre la entrada y la etiqueta
		foreach ($to_be_checked as $id_tag => $tbc) {
			if (!$tbc) {
				$sql = "DELETE FROM `entry_tag` WHERE `id_tag` = ?";
				$db->query($sql, [$id_tag]);
			}
		}

		// Borro las etiquetas "huerfanas" que ya no estén asociadas a ninguna entrada
		$this->cleanEmptyTags($entry->id_user);
	}

	/**
	 * Borra la lista de tags que no están asociadas a ninguna entrada
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return void
	 */
	public function cleanEmptyTags(int $id_user): void {
		$db = new ODB();
		$sql = "DELETE FROM `tag` WHERE `id` NOT IN (SELECT DISTINCT(`id_tag`) FROM `entry_tag` WHERE `id_entry` IN (SELECT `id` FROM `entry` WHERE `id_user` = ?))";
		$db->query($sql, [$id_user]);
	}

	/**
	 * Devuelve la lista de entradas asociadas a una tag
	 *
	 * @param int Id de la tag
	 *
	 * @return array Listado de entradas
	 */
	public function getTagEntries(int $id_tag): array {
		$db = new ODB();
		$sql = "SELECT * FROM `entry` WHERE `id` IN (SELECT `id_entry` FROM `entry_tag` WHERE `id_tag` = ?) ORDER BY `updated_at` DESC";
		$db->query($sql, [$id_tag]);
		$list = [];

		while ($res = $db->next()) {
			$entry = Entry::from($res);
			$list[] = $entry;
		}

		return $list;
	}

	/**
	 * Añade una foto nueva
	 *
	 * @param Entry $entry Entrada a la que añadir la foto
	 *
	 * @param string $data Contenido de la foto en formato Base64
	 *
	 * @return array Datos de la foto en array
	 */
	public function addPhoto(Entry $entry, string $data): array {
		$photo = Photo::create();
		$photo->id_entry = $entry->id;
		$photo->save();

		$route = $this->getConfig()->getDir('photos') . $photo->id;
		file_put_contents($route, $data);

		return $photo->toArray();
	}

	/**
	 * Función para obtener la lista de días en los que han habido entradas de un mes concreto.
	 *
	 * @param int $id_user Id del usuario del que obtener las entradas
	 *
	 * @param int $month Mes en el que buscar entradas
	 *
	 * @param int $year Año en el que buscar entradas
	 *
	 * @return array Lista de días del mes elegido en el que hay entradas
	 */
	public function getCalendar(int $id_user, int $month, int $year): array {
		$db = new ODB();
		$sql = "SELECT DAY(`created_at`) AS `fecha` FROM `entry` WHERE `id_user` = ? AND MONTH(`created_at`) = ? AND YEAR(`created_at`) = ? GROUP BY DAY(`created_at`)";
		$db->query($sql, [$id_user, $month, $year]);
		$list = [];

		while ($res = $db->next()) {
			$day_str = $res['fecha'] < 10 ? '0'.$res['fecha'] : $res['fecha'];
			$month_str = $month < 10 ? '0'.$month : $month;

			$list[] = $day_str . '-' . $month_str;
		}

		return $list;
	}

	/**
	 * Función para obtener el listado de entradas de la home
	 *
	 * @param int $id_user Id del usuario del que obtener las entradas
	 *
	 * @param int $day Día en el que buscar entradas
	 *
	 * @param int $month Mes en el que buscar entradas
	 *
	 * @param int $year Año en el que buscar entradas
	 *
	 * @param array $tags Lista de tags por las que filtrar
	 *
	 * @return array Listado de entradas obtenido
	 */
	public function getHomeEntries(int $id_user, int|null $day, int $month, int $year, array $tags, bool $first): array {
		$db = new ODB();
		if ($first) {
			$sql = "SELECT * FROM `entry` WHERE `id_user` = ? LIMIT 0,10";
			$db->query($sql, [$id_user]);
		}
		else {
			$sql = "SELECT * FROM `entry` WHERE `id_user` = ? AND MONTH(`created_at`) = ? AND YEAR(`created_at`) = ?";
			if (count($tags) > 0) {
				$sql .= " AND `id` IN (SELECT `id_entry` FROM `entry_tag` WHERE `id_tag` IN (" . implode(',', $tags) . "))";
			}
			if (!is_null($day)) {
				$sql .= " AND DAY(`created_at`) = ?";
				$db->query($sql, [$id_user, $month, $year, $day]);
			}
			else {
				$db->query($sql, [$id_user, $month, $year]);
			}
		}
		$list = [];

		while ($res = $db->next()) {
			$entry = Entry::from($res);
			$list[] = $entry;
		}

		return $list;
	}
}
