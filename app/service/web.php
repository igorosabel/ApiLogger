<?php declare(strict_types=1);
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
	 * @return string Lista de entradas del usuario en JSON
	 */
	public function getEntries(int $id_user): string{
		$db = new ODB();
		$sql = "SELECT * FROM `entry` WHERE `id_user` = ? ORDER BY `updated_at` DESC";
		$db->query($sql, [$id_user]);
		$list = [];

		while ($res = $db->next()) {
			$entry = new Entry();
			$entry->update($res);

			array_push($list, $entry->toArray());
		}

		return json_encode($list);
	}

	/**
	 * Obtiene la lista de tags de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return string Lista de tags del usuario en JSON
	 */
	public function getTags(int $id_user): string {
		$db = new ODB();
		$sql = "SELECT * FROM `tag` WHERE `id_user` = ? ORDER BY `updated_at` DESC";
		$db->query($sql, [$id_user]);
		$list = [];

		while ($res = $db->next()) {
			$tag = new Tag();
			$tag->update($res);

			array_push($list, $tag->toArray());
		}

		return json_encode($list);
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
			$to_be_checked[$entry_tag['id']] = false;
		}

		foreach ($tags as $t) {
			$sql = "SELECT * FROM `tag` WHERE `id_user` = ? AND `name` = ?";
			$db->query($sql, [$entry->get('id_user'), $t['name']]);

			$tag = new Tag();
			// Busco la etiqueta, si no existe creo una nueva
			if ($res = $db->next()) {
				$tag->update($res);
			}
			else {
				$tag->set('id_user', $entry->get('id_user'));
				$tag->set('name', $t['name']);
				$tag->set('slug', Base::slugify($t['name']));
				$tag->save();
			}

			$et = new EntryTag();
			// Si la entrada no tiene la etiqueta asociada se la añado
			if (!$et->find(['id_entry'=>$entry->get('id'), 'id_tag'=>$tag->get('id')])) {
				$et->set('id_entry', $entry->get('id'));
				$et->set('id_tag', $tag->get('id'));
				$et->save();
			}

			// Si la entrada ya tenía esta etiqueta asociada la marco para no borrarla
			if (array_key_exists($to_be_checked, $tag->get('id'))) {
				$to_be_checked[$tag->get('id')] = true;
			}
		}

		// Las tags que ya no estén asociadas borro la relación entre la entrada y la etiqueta
		foreach ($to_be_checked as $id_tag => $tbc) {
			if (!tbc) {
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
	 * @return string Listado de entradas en formato JSON
	 */
	public function getTagEntries(int $id_tag): string {
		$db = new ODB();
		$sql = "SELECT * FROM `entry` WHERE `id` IN (SELECT `id_entry` FROM `entry_tag` WHERE `id_tag` = ?) ORDER BY `updated_at` DESC";
		$db->query($sql, [$id_tag]);
		$list = [];

		while ($res = $db->next()) {
			$entry = new Entry();
			$entry->update($res);

			array_push($list, $entry->toArray());
		}

		return json_encode($list);
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
}