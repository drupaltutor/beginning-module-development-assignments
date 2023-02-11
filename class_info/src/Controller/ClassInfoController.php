<?php

namespace Drupal\class_info\Controller;

use Drupal\class_info\ClassInfoManagerInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClassInfoController extends ControllerBase {

  /**
   * Static cache for infomation about the classes.
   *
   * @var array
   */
  protected array $classInfo = [];

  /**
   * @var ClassInfoManagerInterface
   */
  protected ClassInfoManagerInterface $classInfoManager;

  public function __construct(ClassInfoManagerInterface $class_info_manager) {
    $this->classInfoManager = $class_info_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('class_info.manager')
    );
  }

  public function overview(string $class_id) {
    $class_info = $this->getClassInfo($class_id);
    $build = [];
    $build['class'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Course: @course', ['@course' => $class_info['id']]),
    ];
    $build['teacher'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Teacher: @first_name @last_name', [
        '@first_name' => $class_info['teacher']['first_name'],
        '@last_name' => $class_info['teacher']['last_name'],
      ]),
    ];
    return $build;
  }

  public function overviewTitle(string $class_id) {
    $class_info = $this->getClassInfo($class_id);
    return $class_info['name'];
  }

  public function overviewAccess(string $class_id) {
    $allowed_logins = [];
    $class_info = $this->getClassInfo($class_id);
    if (!empty($class_info)) {
      $allowed_logins[] = $class_info['teacher']['login_id'];
      foreach ($class_info['roster'] as $student) {
        $allowed_logins[] = $student['login_id'];
      }
    }
    return AccessResult::allowedIf(!empty($class_info))
      ->andIf(
        AccessResult::allowedIfHasPermission($this->currentUser(), 'view all class information')
          ->orIf(
            AccessResult::allowedIf(
              in_array($this->currentUser()->getAccountName(), $allowed_logins)
            )
          )
      );
  }

  public function roster(string $class_id) {
    $class_info = $this->getClassInfo($class_id);
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
    $rows = [];
    foreach ($class_info['roster'] as $student) {
      $rows[] = [
        $student['last_name'],
        $student['first_name'],
      ];
    }

    $build['roster'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
    return $build;
  }

  public function rosterTitle(string $class_id) {
    $class_info = $this->getClassInfo($class_id);
    return $this->t('Class Roster: @class', ['@class' => $class_info['id']]);
  }

  public function rosterAccess(string $class_id) {
    $class_info = $this->getClassInfo($class_id);
    return AccessResult::allowedIf(!empty($class_info))
      ->andIf(
        AccessResult::allowedIfHasPermission($this->currentUser(), 'view all class information')
          ->orIf(
            AccessResult::allowedIf(
              $class_info['teacher']['login_id']  === $this->currentUser()->getAccountName()
            )
          )
      );
  }

  /**
   * Gets the class info either from the static cache or from the API
   *
   * @param string $class_id
   * @return array|bool
   */
  protected function getClassInfo(string $class_id) {
    if (!isset($this->classInfo[$class_id])) {
      $class_info = $this->classInfoManager->getClassInfo($class_id);
      if ($class_info === NULL) {
        $class_info = FALSE;
      }
      $this->classInfo[$class_id] = $class_info;
    }
    return $this->classInfo[$class_id];
  }

}
