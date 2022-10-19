<?php

namespace Drupal\zero_install\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\zero_install\Base\ZeroInstallInfo;

class ZeroInstallController extends ControllerBase {

  public function serve() {
    /** @var \Drupal\zero_install\Manager\ZeroInstallPluginManager $installs */
    $installs = \Drupal::service('plugin.manager.zero.install');

    $definitions = $installs->getDefinitions();

    /** @var \Drupal\zero_preprocess\Service\ZeroThemeBuilderPluginManager $themes */
    $themes = \Drupal::service('plugin.manager.theme_builder');

    /** @var \Drupal\zero_install\Plugin\Zero\ThemeBuilder\ZeroInstallPage $page */
    $page = $themes->getBuilder('zero_install_page');

    foreach ($definitions as $definition) {
      $info = new ZeroInstallInfo();
      /** @var \Drupal\zero_install\Base\ZeroInstallInterface $instance */
      $instance = $installs->createInstance($definition['id']);
      $instance->info($info);
      $page->addPlugin($definition['id'], $definition['label'], ['comps' => $info->getComps()]);
    }

    return $page->toRenderable();
  }

}
