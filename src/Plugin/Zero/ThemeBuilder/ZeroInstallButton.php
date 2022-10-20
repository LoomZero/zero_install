<?php

namespace Drupal\zero_install\Plugin\Zero\ThemeBuilder;

use Drupal;
use Drupal\zero_entitywrapper\Base\ViewWrapperInterface;
use Drupal\zero_entitywrapper\View\ViewWrapper;
use Drupal\zero_preprocess\Annotation\ZeroThemeBuilder;
use Drupal\zero_preprocess\Base\ZeroThemeBuilderBase;

/**
 * @ZeroThemeBuilder(
 *   id = "zero_install_button",
 *   component = TRUE,
 *   theme = {
 *     "variables" = {
 *       "mode" = NULL,
 *       "modes" = {},
 *     },
 *   },
 * )
 */
class ZeroInstallButton extends ZeroThemeBuilderBase {

  public function preprocess(&$vars) {
    $this->addSettings([
      'mode' => $vars['mode'],
      'modes' => $vars['modes'],
    ]);
    $vars['uuid'] = $this->getUUID();
  }

  public function setMode(string $key): self {
    $this->theme['mode'] = $key;
    return $this;
  }

  /**
   * @param string $key
   * @param array $options = [
   *     'text' => 'Button text',
   *     'state' => 'progress',
   *     'theme' => 'normal|action|danger',
   * ]
   * @returns self
   */
  public function addMode(string $key, array $options = []): self {
    if (!isset($this->theme['mode'])) {
      $this->theme['mode'] = $key;
    }
    $this->theme['modes'][$key] = array_merge([
      'text' => '',
      'icon' => NULL,
      'theme' => 'normal',
    ], $options);
    return $this;
  }

}
