<?php

namespace Morepress\Customize\Control;

class Term extends \WP_Customize_Control {

    protected function render_content() {
        empty($this->tax_name) and $this->tax_name = 'category';
        $dropdown = wp_dropdown_categories(
            array(
                'tax_name' => $this->tax_name,
                'name' => '_customize-dropdown-terms-' . $this->id,
                'echo' => 0,
                'show_option_none' => __('&mdash; Select &mdash;'),
                'option_none_value' => '0',
                'selected' => $this->value(),
                'hide_empty' => 0,
                'orderby' => 'name',
                'hierarchical' => 1,
            )
        );

        // Hackily add in the data link parameter.
        $dropdown = str_replace('<select', '<select ' . $this->get_link(), $dropdown);

        printf(
            '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>', $this->label, $dropdown
        );
    }

}
