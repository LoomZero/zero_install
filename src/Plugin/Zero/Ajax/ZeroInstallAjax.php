<?php

namespace Drupal\zero_install\Plugin\Zero\Ajax;

use Drupal;
use Drupal\zero_ajax_api\Annotation\ZeroAjax;
use Drupal\zero_ajax_api\ZeroAjaxBase;
use Drupal\zero_ajax_api\ZeroAjaxRequest;

/**
 * @ZeroAjax(
 *   id = "zero.install",
 *   params = {
 *     "plugin_id" = "+string",
 *     "action" = "+string",
 *     "mode" = "string",
 *   },
 * )
 */
class ZeroInstallAjax extends ZeroAjaxBase {

  public function response(ZeroAjaxRequest $request) {
    $params = $request->getParams();

    if ($params['action'] === 'install') {
      return $this->install($request);
    } else if ($params['action'] === 'check') {
      return $this->check($request);
    }
  }

  public function install(ZeroAjaxRequest $request) {
    $params = $request->getParams();

    /** @var \Drupal\zero_install\Manager\ZeroInstallPluginManager $installer */
    $installer = Drupal::service('plugin.manager.zero.install');

    $installer->doInstall($params['plugin_id'], $params['mode'] === 'overwrite');

    return [
      'state' => 'ok',
    ];
  }

  public function check(ZeroAjaxRequest $request) {
    $params = $request->getParams();

    /** @var \Drupal\zero_install\Manager\ZeroInstallPluginManager $installer */
    $installer = Drupal::service('plugin.manager.zero.install');

    $result = $installer->doCheck($params['plugin_id']);

    return $result->toResponse();
  }

}
