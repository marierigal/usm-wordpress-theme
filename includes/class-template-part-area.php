<?php

namespace USM;

defined('ABSPATH') || exit;

readonly class Template_Part_Area
{
  public function __construct(
    public string $area,
    public string $area_tag,
    public string $label,
    public string $description,
    public string $icon,
  )
  {
  }

  /**
   * Get area properties
   */
  public function get_properties(): array
  {
    return [
      'area'        => $this->area,
      'area_tag'    => $this->area_tag,
      'label'       => $this->label,
      'description' => $this->description,
      'icon'        => $this->icon,
    ];
  }
}
