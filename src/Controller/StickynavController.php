<?php

/**
 * @file
 * Contains \Drupal\stickynav\Controller\StickynavController.
 */

namespace Drupal\stickynav\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Returns responses for stickynav module routes.
 */
class StickynavController extends ControllerBase {
  /**
   * Lists links to configuration for stickynav per theme.
   *
   * @return string
   *   Table of all enabled themes where you can set the stickynav settings.
   */
  public function listThemes() {
    $build = [];
    $themes = \Drupal::service('theme_handler')->listInfo();
    $rows = [];
    foreach ($themes as $name => $theme) {
      $row = [$theme->info['name']];
      $links['edit'] = [
        'title' => $this->t('Edit'),
        'url' => Url::fromRoute('stickynav.set_theme', ['theme' => $name]),
      ];
      $row[] = [
        'data' => [
          '#type' => 'operations',
          '#links' => $links,
        ],
      ];
      $rows[] = $row;
    }
    $header = array(
      $this->t('Theme'),
      $this->t('Action'),
    );

    $build['stickynav_themes'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
