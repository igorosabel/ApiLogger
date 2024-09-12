<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\EntryTag;
use OsumiFramework\App\Model\Tag;
use OsumiFramework\App\Model\Entry;
use OsumiFramework\App\Model\Photo;

class webService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Obtiene la lista de entradas de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return array Lista de entradas del usuario
	 */
	public function getEntries(int $id_user): array{
		$db = new ODB();
		$sql = "SELECT * FROM `entry` WHERE `id_user` = ? ORDER BY `updated_at` DESC";
		$db->query($sql, [$id_user]);
		$list = [];

		while ($res = $db->next()) {
			$entry = new Entry();
			$entry->update($res);

			array_push($list, $entry);
		}

		return $list;
	}

	/**
	 * Obtiene la lista de tags de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return array Lista de tags del usuario
	 */
	public function getTags(int $id_user): array {
		$db = new ODB();
		$sql = "SELECT * FROM `tag` WHERE `id_user` = ? ORDER BY `updated_at` DESC";
		$db->query($sql, [$id_user]);
		$list = [];

		while ($res = $db->next()) {
			$tag = new Tag();
			$tag->update($res);
			$tag->loadNum();

			array_push($list, $tag);
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
			$to_be_checked[$entry_tag->get('id')] = false;
		}

		foreach ($tags as $t) {
			$sql = "SELECT * FROM `tag` WHERE `id_user` = ? AND `name` = ?";
			$db->query($sql, [$entry->get('id_user'), $t['name']]);

			$tag = new Tag();
			// Busco la etiqueta, si no existe creo una nueva
			//echo "Busco la etiqueta, si no existe creo una nueva.\n";
			if ($res = $db->next()) {
				$tag->update($res);
				//echo "La etiqueta ".$tag->get('id')." existe.\n";
			}
			else {
				$tag->set('id_user', $entry->get('id_user'));
				$tag->set('name', $t['name']);
				$tag->save();
				//echo "Nueva etiqueta ".$tag->get('id')." creada.\n";
			}

			$et = new EntryTag();
			// Si la entrada no tiene la etiqueta asociada se la añado
			if (!$et->find(['id_entry'=>$entry->get('id'), 'id_tag'=>$tag->get('id')])) {
				$et->set('id_entry', $entry->get('id'));
				$et->set('id_tag', $tag->get('id'));
				$et->save();
				//echo "La etiqueta no tenía una entrada asociada.\n";
			}

			// Si la entrada ya tenía esta etiqueta asociada la marco para no borrarla
			if (array_key_exists($tag->get('id'), $to_be_checked)) {
				$to_be_checked[$tag->get('id')] = true;
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
		$this->cleanEmptyTags($entry->get('id_user'));
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
			$entry = new Entry();
			$entry->update($res);

			array_push($list, $entry);
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
		$photo = new Photo();
		$photo->set('id_entry', $entry->get('id'));
		$photo->save();

		$route = $this->getConfig()->getDir('photos').$photo->get('id');
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

			array_push($list, $day_str.'-'.$month_str);
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
				$sql .= " AND `id` IN (SELECT `id_entry` FROM `entry_tag` WHERE `id_tag` IN (".implode(',', $tags)."))";
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
			$entry = new Entry();
			$entry->update($res);

			array_push($list, $entry);
		}

		return $list;
	}
}
