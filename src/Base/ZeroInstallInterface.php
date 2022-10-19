<?php

namespace Drupal\zero_install\Base;

use Drupal\Component\Plugin\PluginInspectionInterface;

interface ZeroInstallInterface extends PluginInspectionInterface {

  public function getInfo(): ZeroInstallInfo;

  public function doInstall(bool $overwrite = FALSE);

  public function doCheck(): ZeroInstallCheckResult;

}
