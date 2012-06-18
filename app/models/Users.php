<?php

namespace app\models;

use lithium\security\Password;
use lithium\util\Validator;
use lithium\util\collection\Filters;

class Users extends \lithium\data\Model {

    public $validates = array(
        'username' => array(
            array('notEmpty', 'message' => 'You must include a username.')
        ),
        'password' => array(
            array('notEmpty', 'message' => 'You must include a password.')
        ),
//TODO: This breaks adding user when there is no email
            /* 		'email' => array(
              array('notEmpty', 'message' => 'An Email Address for the user is required'),
              array('email', 'message' => 'A valid Email Address is required')
              ) */
    );

    public static function __init(array $options = array()) {

        parent::__init($options);

        Validator::add('isUniqueUser', function ($value, $format, $options) {

            $conditions = array('username' => $value);

            if (isset($options["values"]["id"])) {
                $conditions[] = "id != " . $options["values"]["id"];
            }

            return !Users::find('first', array('conditions' => $conditions));
            
        });
    }

    public function fullName($record) {
        if (!empty($record->full_name)) {
            return $record->full_name;
        }
        return $record->full_name = "{$record->first_name} {$record->last_name}";
    }

    public $hasMany = array(
        'Posts' => array(
            'key' => array('id' => 'post_id')
        )
    );

}

/* Lazy loading password filter */
Filters::apply('app\models\Users', 'save', function($self, $params, $chain) {

    $salt = Password::salt('bf', 6);

    if ($params['data']) {
        $params['entity']->set($params['data']);
        $params['data'] = array();
    }

    if (!empty($params['entity']->password)) {
        $params['entity']->password = Password::hash($params['entity']->password, $salt);
    }

    return $chain->next($self, $params, $chain);
    
});

/* Lazy loading full name filter */
//Filters::apply('app\models\Users', 'read', function($self, $params, $chain) {
//
//    if ($params['data']) {
//        $params['entity']->set($params['data']);
//        $params['data'] = array();
//    }
//
//    if (!empty($params['entity']->fullname)) {
//        $params['entity']->fullname = Users::fullName($self);
//    }
//
//    return $chain->next($self, $params, $chain);
//    
//});

?>