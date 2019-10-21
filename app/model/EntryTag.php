<?php
class EntryTag extends OBase{
  function __construct(){
    $table_name  = 'entry_tag';
    $model = [
      'id_entry' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'entry.id',
        'comment' => 'Id de la entrada que tiene la etiqueta'
      ],
      'id_tag' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'tag.id',
        'comment' => 'Id de la etiqueta que se usa en la entrada'
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
}