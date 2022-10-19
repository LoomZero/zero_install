<?php

namespace Drupal\zero_install\Plugin\Zero\Install;

use Drupal\zero_install\Annotation\ZeroInstall;
use Drupal\zero_install\Base\ZeroInstallBase;
use Drupal\zero_install\Base\ZeroInstallInfo;

/**
 * @ZeroInstall(
 *   id = "zero.install.base",
 *   label = "Base",
 * )
 */
class ZeroInstallerPlugin extends ZeroInstallBase {

  public function info(ZeroInstallInfo $info) {
    $info->addComp('test', 'test');
    $info->addConfig('coffee.configuration');
  }

}
