<?php

namespace Drupal\zero_install\Base;

use Drupal;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Plugin\PluginBase;
use Exception;

abstract class ZeroInstallBase extends PluginBase implements ZeroInstallInterface {

  private ?ZeroInstallInfo $info = NULL;
  private array $paths = [];

  public function getPath(string $type, string $path = ''): string {
    if (empty($this->paths[$type])) {
      switch ($type) {
        case 'module':
          /** @var \Drupal\Core\File\FileSystemInterface $fs */
          $fs = Drupal::service('file_system');
          /** @var \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler */
          $moduleHandler = Drupal::service('module_handler');

          $this->paths[$type] = $fs->realpath($moduleHandler->getModule('zero_install')->getPath() . '/zero');
          break;
        case 'theme':
          /** @var \Drupal\Core\File\FileSystemInterface $fs */
          $fs = Drupal::service('file_system');

          $theme = Drupal::theme()->getActiveTheme();
          $this->paths[$type] = $fs->realpath($theme->getPath());
          break;
        default:
          throw new Exception('Unknown path type');
      }
    }
    return $this->paths[$type] . (str_starts_with($path, '/') ? $path : '/' . $path);
  }

  public function scanFiles(string $from, callable $callback, string $start = NULL) {
    if ($start === NULL) $start = $from;
    if (!file_exists($from)) return;
    $iterator = dir($from);
    while (FALSE !== ($file = $iterator->read())) {
      if ($file === '.' || $file === '..') continue;
      $path = $from . '/' . $file;
      if (is_dir($path)) {
        $callback($start, substr($path, strlen($start)), TRUE);
        $this->scanFiles($path, $callback, $start);
      } else {
        $callback($start, substr($path, strlen($start)), FALSE);
      }
    }
  }

  public function copyFiles(string $from, string $to, bool $overwrite = FALSE) {
    if (!file_exists($to)) {
      mkdir($to);
    }
    $this->scanFiles($from, function($from, $file, $is_dir) use ($to, $overwrite) {
      $path = $to . $file;
      if (file_exists($path) && !$overwrite) return;
      if ($is_dir) {
        mkdir($path);
      } else {
        copy($from . $file, $path);
      }
    });
  }

  public function listFiles(string $path) {
    $files = [];
    $this->scanFiles($path, function($from, $file, $is_dir) use (&$files) {
      if (!$is_dir) {
        $files[] = $file;
      }
    });
    return $files;
  }

  public function getInfo(): ZeroInstallInfo {
    if ($this->info === NULL) {
      $this->info = new ZeroInstallInfo();
      $this->info($this->info);
    }
    return $this->info;
  }

  public function doInstall(bool $overwrite = FALSE) {
    $info = $this->getInfo();
    if ($info->isCustom()) {
      $this->install($info, $overwrite);
    } else {
      foreach ($info->getComps() as $component) {
        $this->installComponent($component, $overwrite);
      }
      foreach ($info->getConfigs() as $config) {
        $this->installConfig($config, $overwrite);
      }
    }
  }

  /**
   * @param $component = [
   *     "category" => "ui",
   *     "component" => "blende",
   * ]
   */
  public function installComponent(array $component, bool $overwrite = FALSE) {
    $categoryPath = $this->getPath('theme', '/src/comps/' . $component['category']);
    if (!file_exists($categoryPath)) {
      mkdir($categoryPath);
    }
    $this->copyFiles($this->getPath('module', '/components/' . $component['category'] . '/' . $component['component']), $categoryPath . '/' . $component['component'], $overwrite);
  }

  public function installConfig(string $config, bool $overwrite = FALSE) {
    /** @var \Drupal\Core\Config\CachedStorage $storage */
    $storage = Drupal::service('config.storage');

    if ($overwrite || !$storage->exists($config)) {
      $storage->write($config, Yaml::decode(file_get_contents($this->getPath('module', '/configs/' . $config . '.yml'))));
    }
  }

  public function doCheck(): ZeroInstallCheckResult {
    $info = $this->getInfo();
    $result = new ZeroInstallCheckResult();
    if ($info->isCustom()) {
      $this->check($info, $result);
    } else {
      foreach ($info->getComps() as $component) {
        $this->checkComponent($result, $component);
      }
      foreach ($info->getConfigs() as $config) {
        $this->checkConfig($result, $config);
      }
    }
    return $result;
  }

  /**
   * @param $component = [
   *     "category" => "ui",
   *     "component" => "blende",
   * ]
   */
  public function checkComponent(ZeroInstallCheckResult $result, array $component) {
    $componentPath = $this->getPath('theme', '/src/comps/' . $component['category'] . '/' . $component['component']);
    if (!file_exists($componentPath)) {
      $result->missingComponent($component);
    } else {
      $baseFiles = $this->listFiles($this->getPath('module', '/components/' . $component['category'] . '/' . $component['component']));
      $testFiles = $this->listFiles($componentPath);
      foreach ($baseFiles as $file) {
        if (!in_array($file, $testFiles)) {
          $result->missingComponentFile($component, $file);
        }
      }
    }
  }

  public function checkConfig(ZeroInstallCheckResult $result, string $config) {
    /** @var \Drupal\Core\Config\CachedStorage $storage */
    $storage = Drupal::service('config.storage');

    if (!$storage->exists($config)) {
      $result->missingConfig($config);
    }
  }

  public function info(ZeroInstallInfo $info) {
    $info->setCustom();
  }

  public function install(ZeroInstallInfo $info, bool $overwrite = FALSE) { }

  public function check(ZeroInstallInfo $info, ZeroInstallCheckResult $result) { }

}
