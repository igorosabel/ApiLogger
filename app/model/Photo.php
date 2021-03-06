<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Photo extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'photo';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única de cada foto'
			],
			'id_entry' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'entry.id',
				'comment'  => 'Id de la entrada en la que va la foto'
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

	/**
	 * Obtiene el contenido de la foto
	 *
	 * @return string Contenido de la foto en formato Base64
	 */
	public function getData(): string {
		global $core;

		$route = $core->config->getDir('photos').$this->get('id');
		return file_get_contents($route);
	}

	/**
	 * Obtiene el contenido de la foto como un array (tipo/datos)
	 *
	 * @return array Datos de la foto
	 */
	public function getImage(): array {
		$data = $this->getData();
		$data_parts = explode(';', $data);
		return [
			'type' => str_ireplace('data:', '', $data_parts[0]),
			'image' => str_ireplace('base64,', '', $data_parts[1])
		];
	}

	/**
	 * Devuelve datos de la foto en la base de datos en formato array
	 *
	 * @return array Datos de la foto
	 */
	public function toArray(): array {
		return [
			'id'        => $this->get('id'),
			'createdAt' => $this->get('created_at', 'd/m/Y'),
			'updatedAt' => $this->get('updated_at', 'd/m/Y')
		];
	}

	/**
	 * Borra una foto por completo, el archivo y el registro
	 *
	 * @return bool Devuelve si la foto ha sido borrada correctamente o no
	 */
	public function deleteFull(): bool {
		global $core;

		$route = $core->config->getDir('photos').$this->get('id');
		if (file_exists($route)) {
			unlink($route);
			$this->delete();
			return true;
		}

		return false;
	}
}