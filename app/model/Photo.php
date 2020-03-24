<?php
class Photo extends OModel{
  function __construct(){
    $table_name  = 'photo';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id única de cada foto'
      ],
      'id_entry' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'entry.id',
        'comment' => 'Id de la entrada en la que va la foto'
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
  
  public function getData(){
	  global $core;
	  
	  $route = $core->config->getDir('photos').$this->get('id');
	  return file_get_contents($route);
  }
  
  public function getImage(){
	  $data = $this->getData();
	  $data_parts = explode(';', $data);
	  return [
		  'type' => str_ireplace('data:', '', $data_parts[0]),
		  'image' => str_ireplace('base64,', '', $data_parts[1])
	  ];
  }
  
  public function toArray(){
    return [
      'id'        => $this->get('id'),
      'createdAt' => $this->get('created_at', 'd/m/Y'),
      'updatedAt' => $this->get('updated_at', 'd/m/Y')
    ];
  }
}