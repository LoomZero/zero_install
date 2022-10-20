<?php

namespace Drupal\zero_install\Plugin\Zero\ThemeBuilder;

use Drupal\zero_preprocess\Annotation\ZeroThemeBuilder;
use Drupal\zero_preprocess\Base\ZeroThemeBuilderBase;

/**
 * @ZeroThemeBuilder(
 *   id = "comp_manager_page",
 *   component = {
 *     "dependencies" = {
 *       "zero_preprocess/comp",
 *       "zero_ajax_api/ajax",
 *     },
 *   },
 *   theme = {
 *     "variables" = {},
 *   },
 * )
 */
class CompManagerPageBuilder extends ZeroThemeBuilderBase {

  public function preprocess(&$vars) {
    $vars['search'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => 'Search',
        'class' => [
          'comp-manager-page__search',
        ],
      ],
    ];
  }

}
