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
      'slug' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Slug del título de la entrada'
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