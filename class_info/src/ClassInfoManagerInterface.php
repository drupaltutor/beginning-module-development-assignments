<?php

namespace Drupal\class_info;

interface ClassInfoManagerInterface {

  /**
   * Returns the class information
   *
   * @param string $class_id
   * @return array|null
   */
  public function getClassInfo(string $class_id) : ?array;

}
