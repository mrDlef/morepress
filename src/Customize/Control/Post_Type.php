<?php

namespace Morepress\Customize\Control;

class Post_Type extends \WP_Customize_Control {

    public $type = 'select';

    public function __construct($manager, $id, $args = array()) {
        parent::__construct($manager, $id, $args);
        $this->choices = array();

	$post_types = \Morepress\Post_Type::find(array(), 'object');

        foreach($post_types as $key => $item)
        {
            $this->choices[$key] = $item->labels->name;
        }
    }

}
