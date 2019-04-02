<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Get extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function token()
    {
        die($this->security->get_csrf_hash());
    }

}
