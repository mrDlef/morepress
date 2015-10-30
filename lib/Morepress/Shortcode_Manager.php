<?php

namespace Morepress;

class Shortcode_Manager {

    /**
     * Instanciates class : just hook to the admin_head hook
     */
    public static function forge() {
        add_action('admin_head', array(get_called_class(), 'action_admin_init'));
        if (is_admin()) {
            add_action('admin_head', array(get_called_class(), 'add_head_shortcode'));
        }
    }

    /**
     * Register to add stuff on second line of WYSIWYG
     * Register to load external TinyMCE plugins
     */
    public static function action_admin_init() {
        // Styles
        add_filter('mce_buttons_2', array(get_called_class(), 'filter_mce_button'));
        add_filter('mce_external_plugins', array(get_called_class(), 'filter_mce_plugin'));
    }

    public static function add_head_shortcode() {
        $forges = Shortcode::get_shortcodes();
        foreach ($forges as $forge) {
            $list_shortcodes[] = $forge->get_title();
            // Check if some treatment is necessary for filepicker fields
            $forge->check_fields_files();
            $res[$forge->get_title()] = array(
                'name' => $forge->get_name(),
                'is_immediat' => $forge->is_immediat(),
                'shortcode' => $forge->get_shortcode(),
                'fields' => $forge->get_fields()
            );
        }
        $res = json_encode($res);
        // Delete string for function() onclick on filepicker
        $res = str_replace(array('"__', '__"'), '', $res);
        $list = json_encode($list_shortcodes);
        ?>
        <script type="text/javascript">
            var list_morepress_shortcodes = <?php echo $list; ?>;
            var morepress_shortcodes = <?php echo $res; ?>;
        </script>
        <?php
    }

    /**
     * Add ou bouton to the TinyMCE editor
     * @param array $buttons the TinyMCE  button collection
     * @return array the buttons array updated
     */
    public static function filter_mce_button($buttons) {
        array_push($buttons, 'morepress_shortcode');
        return $buttons;
    }

    /**
     * Physically load the external plugin js file
     * @param array $plugins all external plugins
     * @return array the external plugin array updated with our plugin
     */
    public static function filter_mce_plugin($plugins) {
        $plugins['morepress_shortcode'] = plugin_dir_url(MOREPRESS_PLUGIN_FILE) . 'js/shortcode.js';
        return $plugins;
    }

}
