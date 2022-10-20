<?php

namespace Drupal\zero_install\Controller;

use Drupal\Core\Controller\ControllerBase;

class CompManagerPageController extends ControllerBase {

  public function serve() {
    /** @var \Drupal\zero_preprocess\Service\ZeroThemeBuilderPluginManager $themes */
    $themes = \Drupal::service('plugin.manager.theme_builder');

    /** @var \Drupal\zero_install\Plugin\Zero\ThemeBuilder\ZeroInstallPage $page */
    $page = $themes->getBuilder('comp_manager_page');

    return $page->toRenderable();
  }

}
