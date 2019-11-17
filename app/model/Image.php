<?php
class Image extends OBase{
  function __construct(){
    $table_name  = 'image';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id única de cada imagen'
      ],
      'id_entry' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'entry.id',
        'comment' => 'Id de la entrada en la que va la imagen'
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
      'createdAt' => $this->get('created_at', 'd/m/Y'),
      'updatedAt' => $this->get('updated_at', 'd/m/Y')
    ];
  }
}