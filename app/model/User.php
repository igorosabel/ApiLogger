<?php
class User extends OModel{
  function __construct(){
    $table_name  = 'user';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id único de cada usuario'
      ],
      'username' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre de usuario'
      ],
      'pass' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 200,
        'comment' => 'Contraseña cifrada del usuario'
      ],
      'created_at' => [
        'type'    => OCore::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'    => OCore::UPDATED,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de última modificación del registro'
      ]
    ];

    parent::load($table_name, $model);
  }
}