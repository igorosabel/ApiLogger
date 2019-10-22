<?php
class Entry extends OBase{
  function __construct(){
    $table_name  = 'entry';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id única de cada entrada'
      ],
      'id_user' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'user.id',
        'comment' => 'Id del usuario que crea la entrada'
      ],
      'title' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Título de la entrada'
      ],
      'body' => [
        'type'    => Base::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cuerpo de la entrada'
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
      this.get('id'),
      this.get('title'),
      this.get('slug'),
      this.get('body'),
      this.get('created_at', 'd/m/Y'),
      this.get('updated_at', 'd/m/Y')
    ];
  }
}