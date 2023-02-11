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

  public function roster(string $class_id) {
    $build = [];
    $build['title'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Students'),
    ];
    $header = [
      $this->t('Last Name'),
      $this->t('First Name'),
    ];
    $rows = [
      [
        'Mayer',
        'Anastasia',
      ],
      [
        'Miles',
        'Samuel',
      ],
      [
        'Randolph',
        'Brooklyn',
      ],
      [
        'Savage',
        'Cody',
      ],
    ];
    $build['roster'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
    return $build;
  }

  public function rosterTitle(string $class_id) {
    return $this->t('Class Roster: @class', ['@class' => $class_id]);
  }

}
