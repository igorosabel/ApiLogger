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
    $sql = "SELECT * FROM `entry` WHERE `id_user` = ?";
    $db->query($sql, [$id_user]);
    $list = [];

    while ($res = $db->next()){
      $entry = new Entry();
      $entry->update($res);

      array_push($list, $entry->toArray());
    }

    return json_encode($list);
  }
}