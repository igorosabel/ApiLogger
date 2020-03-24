<?php
class Entry extends OModel{
  function __construct(){
    $table_name  = 'entry';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id única de cada entrada'
      ],
      'id_user' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'user.id',
        'comment' => 'Id del usuario que crea la entrada'
      ],
      'title' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Título de la entrada'
      ],
      'slug' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Slug del título de la entrada'
      ],
      'body' => [
        'type'    => OCore::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cuerpo de la entrada'
      ],
      'is_public' => [
        'type'    => OCore::BOOL,
        'nullable' => false,
        'default' => false,
        'comment' => 'Indica si la entrada es pública 1 o no 0'
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

  private $tags = null;

  public function getTags(){
    if (is_null($this->tags)){
      $this->loadTags();
    }
    return $this->tags;
  }

  public function setTags($tags){
    $this->tags = $tags;
  }

  public function loadTags(){
    $sql = "SELECT * FROM `tag` WHERE `id` IN (SELECT `id_tag` FROM `entry_tag` WHERE `id_entry` = ?) ORDER BY `name` ASC";
    $this->db->query($sql, [$this->get('id')]);
    $list = [];

    while ($res = $this->db->next()){
      $tag = new Tag();
      $tag->update($res);

      array_push($list, $tag->toArray());
    }

    $this->setTags($list);
  }

  private $photos = null;

  public function getPhotos(){
	if (is_null($this->photos)){
		$this->loadPhotos();
	}
	return $this->photos;
  }

  public function setPhotos($photos){
	  $this->photos = $photos;
  }

  public function loadPhotos(){
	  $sql = "SELECT * FROM `photo` WHERE `id_entry` = ?";
	  $this->db->query($sql, [$this->get('id')]);
	  $list = [];

	  while ($res = $this->db->next()){
		  $photo = new Photo();
		  $photo->update($res);

		  array_push($list, $photo->toArray());
	  }

	  $this->setPhotos($list);
  }

  public function deleteFull(){
	  $sql = "DELETE FROM `entry_tag` WHERE `id_entry` = ?";
	  $this->db->query($sql, [$this->get('id')]);
	  $this->delete();
  }

  public function toArray(){
    return [
      'id'        => $this->get('id'),
      'title'     => $this->get('title'),
      'slug'      => $this->get('slug'),
      'body'      => $this->get('body'),
      'createdAt' => $this->get('created_at', 'd/m/Y'),
      'updatedAt' => $this->get('updated_at', 'd/m/Y'),
      'tags'      => $this->getTags()
    ];
  }
}