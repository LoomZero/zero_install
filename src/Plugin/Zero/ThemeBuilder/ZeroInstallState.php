<?php

namespace Drupal\zero_install\Plugin\Zero\ThemeBuilder;

use Drupal;
use Drupal\zero_entitywrapper\Base\ViewWrapperInterface;
use Drupal\zero_entitywrapper\View\ViewWrapper;
use Drupal\zero_preprocess\Annotation\ZeroThemeBuilder;
use Drupal\zero_preprocess\Base\ZeroThemeBuilderBase;

/**
 * @ZeroThemeBuilder(
 *   id = "zero_install_state",
 *   component = TRUE,
 *   theme = {
 *     "variables" = {
 *       "mode" = "progress",
 *       "tooltip" = "In Progress",
 *     },
 *   },
 * )
 */
class ZeroInstallState extends ZeroThemeBuilderBase {

  public function setMode(string $mode, string $tooltip = ''): self {
    $this->theme[$this->getKey('mode')] = $mode;
    $this->theme[$this->getKey('tooltip')] = $tooltip;
    return $this;
  }

}
