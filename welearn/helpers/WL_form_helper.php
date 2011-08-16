<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 12/08/11
 * Time: 09:51
 * To change this template use File | Settings | File Templates.
 */
 
function validation_errors_array()
{
    $ci =& get_instance();

    if (isset($ci->form_validation)) {
        $error_array = $ci->form_validation->_error_array;
        $real_error_array = array();

        if (!empty($error_array)) {
            foreach ($error_array as $field_name => $error_msg) {
                $real_error_array[] = array(
                    'field_name' => $field_name,
                    'error_msg' => $error_msg
                );
            }
            return $real_error_array;
        }
    }

    return FALSE;
}

function validation_errors_json()
{
    $error_array = validation_errors_array();

    if (is_array($error_array)) {
        return Zend_Json::encode($error_array);
    }

    return FALSE;
}