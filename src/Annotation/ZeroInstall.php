<?php

namespace Drupal\zero_install\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Task plugin item annotation object.
 *
 * @see \Drupal\zero_ajax_api\ZeroAjaxPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class ZeroInstall extends Plugin {

  /** @var string */
  public $id;

  /** @var string */
  public $label;

}
