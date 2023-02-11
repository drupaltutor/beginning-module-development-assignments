<?php

namespace Drupal\class_info\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;

class ClassInfoController extends ControllerBase {

  public function overview(string $class_id) {
    $build = [];
    $build['class'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Course: @course', ['@course' => $class_id]),
    ];
    $build['teacher'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Teacher: @teacher', ['@teacher' => 'John Doe']),
    ];
    return $build;
  }

  public function overviewTitle(string $class_id) {
    return Html::escape($class_id);
  }

}
