<?php

namespace Morepress;

class Shortcode {

    protected $_name;
    private $_shortcode;
    private $_fields;
    private $_title;
    private $_callback;
    public $output;
    protected static $_shortcodes = array();

    public static function get_shortcodes() {
        return static::$_shortcodes;
    }

    public static function forge($name, $title = null, $callback = null, $fields = null) {
        if (isset(static::$_shortcodes[$name])) {
            return static::$_shortcodes[$name];
        }
        static::$_shortcodes[$name] = new static($name, $title, $callback, $fields);
        return static::$_shortcodes[$name];
    }

    public function __construct($name, $title, $callback = null, $fields = null) {
        $this->_name = $name;
        $this->_title = $title;
        $this->_callback = $callback;
        $this->_fields = $fields;
        if (empty($fields)) {
            $this->_shortcode = "[" . $name . "][/" . $name . "]";
        } else {
            $this->_shortcode = null;
        }
        add_shortcode($name, $callback);
    }

    public function is_immediat() {
        if ($this->_fields == null) {
            return true;
        }
        return false;
    }

    public function get_name() {
        return $this->_name;
    }

    public function get_title() {
        return $this->_title;
    }

    public function get_shortcode() {
        return $this->_shortcode;
    }

    public function get_fields() {
        return $this->_fields;
    }

    public function check_fields_files() {
        foreach ((array) $this->_fields as $key => $field) {
            if ($field['type'] == 'filepicker') {
                $field['type'] = 'textbox';
                $field['id'] = $field['name'];
                $field['style'] = '';
                $field['disabled'] = 'disabled';
                $this->_fields[$key] = $field;
                $filepicker = array(
                    'type' => 'button',
                    'text' => 'Choisir un fichier',
                    'onclick' => "__ function() { window.mb = window.mb || {}; window.mb.frame = wp.media({ frame: 'post', state: 'insert', library : { type : 'image' }, multiple: false }); window.mb.frame.on('insert', function() { var json = window.mb.frame.state().get('selection').first().toJSON(); if (0 > json.url.length) { return; } document.getElementById('" . $field['name'] . "').value = json.id; }); window.mb.frame.open(); } __"
                );

                // Add filepicker field after
                array_splice($this->_fields, $key + 1, 0, array($filepicker));
            }
        }
    }

}
