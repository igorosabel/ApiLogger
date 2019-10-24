<?php
class Tag extends OBase{
  function __construct(){
    $table_name  = 'tag';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id única de la etiqueta'
      ],
      'id_user' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'user.id',
        'comment' => 'Id del usuario que crea la etiqueta'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Texto de la etiqueta'
      ],
      'slug' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Slug del texto de la etiqueta'
      ],
      'created_at' => [
        'type'    => Base::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'    => Base::UPDATED,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de última modificación del registro'
      ]
    ];

    parent::load($table_name, $model);
  }

  public function toArray(){
    return [
      'id'        => $this->get('id'),
      'name'      => $this->get('name'),
      'slug'      => $this->get('slug'),
      'createdAt' => $this->get('created_at', 'd/m/Y'),
      'updatedAt' => $this->get('updated_at', 'd/m/Y')
    ];
  }
}