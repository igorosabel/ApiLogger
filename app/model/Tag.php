<?php declare(strict_types=1);
class Tag extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
  function __construct() {
    $table_name  = 'tag';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id única de la etiqueta'
      ],
      'id_user' => [
        'type'     => OCore::NUM,
        'nullable' => false,
        'default'  => null,
        'ref'      => 'user.id',
        'comment'  => 'Id del usuario que crea la etiqueta'
      ],
      'name' => [
        'type'     => OCore::TEXT,
        'nullable' => false,
        'default'  => null,
        'size'     => 100,
        'comment'  => 'Texto de la etiqueta'
      ],
      'slug' => [
        'type'     => OCore::TEXT,
        'nullable' => false,
        'default'  => null,
        'size'     => 100,
        'comment'  => 'Slug del texto de la etiqueta'
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