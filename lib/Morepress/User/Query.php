<?php

namespace Morepress\User;

class Query extends \WP_User_Query {

    protected function parse_orderby($orderby) {
        $_orderby = parent::parse_orderby($orderby);
        if ('rand' == $orderby) {
            $_orderby = 'RAND()';
        }
        return $_orderby;
    }

	public function get_results() {
        $results = array();
        foreach($this->results as $key=>$value) {
            $results[$key] = \Morepress\User::forge($value);
        }
		return $results;
	}

}
