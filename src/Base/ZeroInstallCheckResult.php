<?php

namespace Drupal\zero_install\Base;

class ZeroInstallCheckResult {

  private array $missing = [];

  public function missingComponent(array $component, array $context = []) {
    $context['type'] = 'component';
    $this->missing[] = [
      'context' => $context,
      'component' => $component,
    ];
    return $this;
  }

  public function missingComponentFile(array $component, string $file, array $context = []): self {
    $context['type'] = 'component.file';
    $context['component'] = $component;
    $this->missing[] = [
      'context' => $context,
      'file' => $file,
    ];
    return $this;
  }

  public function missingConfig(string $config, array $context = []): self {
    $context['type'] = 'config';
    $context['config'] = $config;
    $this->missing[] = [
      'config' => $config,
      'context' => $context,
    ];
    return $this;
  }

  public function toResponse(): array {
    return [
      'valid' => count($this->missing) === 0,
      'missing' => $this->missing,
    ];
  }

}
