<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends WL_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso');
    }

    public function index($idCurso)
    {

    }
}