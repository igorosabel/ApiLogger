<?php
  /*
   * Clase con funciones generales para usar a lo largo del sitio
   */
class webService extends OService{
  function __construct($controller=null){
    $this->setController($controller);
  }

  public function getEntries($id_user){
    $db = $this->getController()->getDb();
    $sql = "SELECT * FROM `entry` WHERE `id_user` = ? ORDER BY `updated_at` DESC";
    $db->query($sql, [$id_user]);
    $list = [];

    while ($res = $db->next()){
      $entry = new Entry();
      $entry->update($res);

      array_push($list, $entry->toArray());
    }

    return json_encode($list);
  }

  public function getTags($id_user){
    $db = $this->getController()->getDb();
    $sql = "SELECT * FROM `tag` WHERE `id_user` = ? ORDER BY `updated_at` DESC";
    $db->query($sql, [$id_user]);
    $list = [];

    while ($res = $db->next()){
      $tag = new Tag();
      $tag->update($res);

      array_push($list, $tag->toArray());
    }

    return json_encode($list);
  }

  public function saveTags($entry, $tags){
    $db = $this->getController()->getDb();
    $entry_tags = $entry->getTags();
    $to_be_checked = [];
    // Busco etiquetas de la entrada y las "marco" para borrar
    foreach ($entry_tags as $entry_tag){
      $to_be_checked[$entry_tag['id']] = false;
    }

    foreach ($tags as $t){
      $sql = "SELECT * FROM `tag` WHERE `id_user` = ? AND `name` = ?";
      $db->query($sql, [$entry->get('id_user'), $t['name']]);

      $tag = new Tag();
      // Busco la etiqueta, si no existe creo una nueva
      if ($res = $db->next()){
        $tag->update($res);
      }
      else{
        $tag->set('id_user', $entry->get('id_user'));
        $tag->set('name', $t['name']);
        $tag->set('slug', Base::slugify($t['name']));
        $tag->save();
      }

      $et = new EntryTag();
      // Si la entrada no tiene la etiqueta asociada se la añado
      if (!$et->find(['id_entry'=>$entry->get('id'), 'id_tag'=>$tag->get('id')])){
        $et->set('id_entry', $entry->get('id'));
        $et->set('id_tag', $tag->get('id'));
        $et->save();
      }

      // Si la entrada ya tenía esta etiqueta asociada la marco para no borrarla
      if (array_key_exists($to_be_checked, $tag->get('id'))){
        $to_be_checked[$tag->get('id')] = true;
      }
    }

    // Las tags que ya no estén asociadas borro la relación entre la entrada y la etiqueta
    foreach ($to_be_checked as $id_tag => $tbc){
      if (!tbc){
        $sql = "DELETE FROM `entry_tag` WHERE `id_tag` = ?";
        $db->query($sql, [$id_tag]);
      }
    }

    // Borro las etiquetas "huerfanas" que ya no estén asociadas a ninguna entrada
    $this->cleanEmptyTags($entry->get('id_user'));
  }

  public function cleanEmptyTags($id_user){
    $db = $this->getController()->getDb();
    $sql = "DELETE FROM `tag` WHERE `id` NOT IN (SELECT DISTINCT(`id_tag`) FROM `entry_tag` WHERE `id_entry` IN (SELECT `id` FROM `entry` WHERE `id_user` = ?))";
    $db->query($sql, [$id_user]);
  }
}