<?php

namespace Drupal\zero_install\Plugin\Zero\Ajax;

use Drupal;
use Drupal\zero_ajax_api\Annotation\ZeroAjax;
use Drupal\zero_ajax_api\ZeroAjaxBase;
use Drupal\zero_ajax_api\ZeroAjaxRequest;

/**
 * @ZeroAjax(
 *   id = "zero.comp_manager",
 *   params = {
 *     "action" = "+string",
 *     "search" = "string",
 *   },
 * )
 */
class CompManagerAjax extends ZeroAjaxBase {

  public function response(ZeroAjaxRequest $request) {
    switch ($request->getParams()['action']) {
      case 'list':
        return $this->list($request);
    }
    return [
      'ok' => 'ok',
    ];
  }

  public function list(ZeroAjaxRequest $request): array {
    /** @var \Drupal\Core\File\FileSystemInterface $fs */
    $fs = Drupal::service('file_system');
    $params = $request->getParams();
    $theme = Drupal::theme()->getActiveTheme();
    $path = $fs->realpath($theme->getPath()) . '/src/comps';

    $files = [];
    $comps = [];

    $this->scanFiles($path, function($root, $path, $is_dir) use (&$comps, &$files) {
      if ($is_dir) return;
      $name = basename(dirname($path));
      $files[$name][] = $path;
      if (isset($comps[$name])) {
        $comps[$name][] = $path;
      }
      if (str_ends_with($path, '.yml')) {
        $comps[$name] = $files[$name] ?? [];
      }
    });
    $a = 0;

    return [
      'params' => $params,
      'list' => [],
    ];
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

}
