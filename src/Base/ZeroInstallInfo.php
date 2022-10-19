<?php

namespace Drupal\zero_install\Base;

class ZeroInstallInfo {

  private bool $custom = FALSE;
  private array $comps = [];
  private array $configs = [];

  public function setCustom($custom = TRUE): self {
    $this->custom = $custom;
    return $this;
  }

  public function isCustom(): bool {
    return $this->custom;
  }

  public function addComp(string $category, string $component): self {
    $this->comps[] = [
      'category' => $category,
      'component' => $component,
    ];
    return $this;
  }

  public function getComps(): array {
    return $this->comps;
  }

  public function addConfig(string $name): self {
    $this->configs[] = $name;
    return $this;
  }

  public function getConfigs(): array {
    return $this->configs;
  }

}
