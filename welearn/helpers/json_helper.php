<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 14/08/11
 * Time: 13:06
 * To change this template use File | Settings | File Templates.
 */
 
function set_json_header()
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache");
	header("Content-type: application/json; charset=utf-8");
}

function create_json_feedback($success = false, $errors = '')
{
    if (!empty($errors) && is_array($errors)) {
        if(!isset($errors['field_name']) || !isset($errors['error_msg'])) {
            foreach ($errors as $key => $val) {
                if (is_array($val) && (isset($val['field_name']) && isset($val['error_msg']))) {
                   $errors[$key] = Zend_Json::encode($val);
                }
            }
            $errors = implode(',', $errors);
        } else {
            $errors = Zend_Json::encode($errors);
        }
    }

    $errors = '[' . trim($errors, '[]') . ']';
    
    if ($success) {
        return '{"success":true, "errors":' . $errors . '}';
    } else {
        return '{"success":false, "errors":' . $errors . '}';
    }
}

function create_json_feedback_error_array($error_msg, $field = 'noField')
{
    return array(
        'field_name' => (string)$field,
        'error_msg' => (string)$error_msg
    );
}

function create_json_feedback_error_json($error_msg, $field = 'noField')
{
    return Zend_Json::encode(create_json_feedback_error_array($error_msg, $field));
}