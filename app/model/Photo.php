<?php declare(strict_types=1);
class Photo extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
  function __construct() {
    $table_name  = 'photo';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id única de cada foto'
      ],
      'id_entry' => [
        'type'     => OCore::NUM,
        'nullable' => false,
        'default'  => null,
        'ref'      => 'entry.id',
        'comment'  => 'Id de la entrada en la que va la foto'
      ],
      'created_at' => [
        'type'    => OCore::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'     => OCore::UPDATED,
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
}