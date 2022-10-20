<?php

namespace Drupal\zero_install\Plugin\Zero\ThemeBuilder;

use Drupal;
use Drupal\zero_entitywrapper\Base\ViewWrapperInterface;
use Drupal\zero_entitywrapper\View\ViewWrapper;
use Drupal\zero_preprocess\Annotation\ZeroThemeBuilder;
use Drupal\zero_preprocess\Base\ZeroThemeBuilderBase;

/**
 * @ZeroThemeBuilder(
 *   id = "zero_install_page",
 *   component = {
 *     "dependencies" = {
 *       "zero_preprocess/comp",
 *       "zero_ajax_api/ajax",
 *     },
 *   },
 *   theme = {
 *     "variables" = {
 *       "plugins" = {},
 *     },
 *   },
 * )
 */
class ZeroInstallPage extends ZeroThemeBuilderBase {

  public function preprocess(&$vars) {
    /** @var \Drupal\zero_preprocess\Service\ZeroThemeBuilderPluginManager $themes */
    $themes = \Drupal::service('plugin.manager.theme_builder');

    /** @var \Drupal\zero_install\Plugin\Zero\ThemeBuilder\ZeroInstallButton $button */
    $button = $themes->getBuilder('zero_install_button');

    $button
      ->addMode('progress', [
        'text' => 'PROGRESS',
        'state' => 'progress',
      ])
      ->addMode('install', [
        'text' => 'INSTALL',
        'theme' => 'action',
      ])
      ->addMode('complete', [
        'text' => 'COMPLETE INSTALLATION',
        'theme' => 'action',
      ])
      ->addMode('overwrite', [
        'text' => 'OVERWRITE',
        'theme' => 'danger',
      ]);

    $vars['button'] = $button;

    /** @var \Drupal\zero_install\Plugin\Zero\ThemeBuilder\ZeroInstallState $state */
    $state = $themes->getBuilder('zero_install_state');

    $vars['state'] = $state;
  }

  public function addPlugin(string $id, string $label, array $summary = []): self {
    $this->theme['plugins'][$id] = [
      'id' => $id,
      'label' => $label,
      'summary' => $summary,
    ];
    return $this;
  }

}
