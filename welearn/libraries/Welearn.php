<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 02:14
 *
 * Description:
 * Loader Welearn para as classes da biblioteca WeLearn
 */

define('LIBSPATH', APPPATH . 'libraries/');

require_once LIBSPATH.'/WeLearn/Base/AutoLoader.php';

class WL_Welearn {

    public function __construct()
    {
        try {
            WeLearn_Base_AutoLoader::init(LIBSPATH);
            log_message('debug', 'WeLearn autoloader iniciado com sucesso!');
        } catch ( Exception $e ) {
            log_message('error', $e->getMessage());
        }
    }
}