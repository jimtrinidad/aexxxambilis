<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Howitworks extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index($code = false)
    {
        $viewData = array(
            'pageTitle'     => 'How it works',
            'pageSubTitle'  => '&nbsp;',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
            ),
        );

        view('main/misc/how', $viewData, 'templates/main');

    }

}
