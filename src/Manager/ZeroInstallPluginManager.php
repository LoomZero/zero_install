<?php

namespace Drupal\zero_install\Manager;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\zero_install\Base\ZeroInstallCheckResult;
use Drupal\zero_install\Base\ZeroInstallInfo;

class ZeroInstallPluginManager extends DefaultPluginManager {

  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Zero/Install', $namespaces, $module_handler,
      'Drupal\zero_install\Base\ZeroInstallInterface',
      'Drupal\zero_install\Annotation\ZeroInstall');

    $this->alterInfo('zero_install_info');
    $this->setCacheBackend($cache_backend, 'zero_install_info');
  }

  public function doInstall(string $plugin_id, bool $overwrite = FALSE) {
    /** @var \Drupal\zero_install\Base\ZeroInstallInterface $plugin */
    $plugin = $this->createInstance($plugin_id);

    $plugin->doInstall($overwrite);
  }

  public function doCheck(string $plugin_id): ZeroInstallCheckResult {
    /** @var \Drupal\zero_install\Base\ZeroInstallInterface $plugin */
    $plugin = $this->createInstance($plugin_id);

    return $plugin->doCheck();
  }

}
