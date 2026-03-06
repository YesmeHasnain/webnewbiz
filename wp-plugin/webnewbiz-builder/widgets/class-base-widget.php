<?php
namespace WebnewBiz\Builder\Widgets;

use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit;

abstract class Base_Widget extends Widget_Base {

    public function get_categories(): array {
        return ['webnewbiz-widgets'];
    }

    /**
     * Helper: Elementor-compatible padding/margin array.
     */
    protected function make_dimensions(int $top = 0, int $right = 0, int $bottom = 0, int $left = 0, string $unit = 'px'): array {
        return [
            'unit'     => $unit,
            'top'      => (string) $top,
            'right'    => (string) $right,
            'bottom'   => (string) $bottom,
            'left'     => (string) $left,
            'isLinked' => ($top === $right && $right === $bottom && $bottom === $left),
        ];
    }
}
