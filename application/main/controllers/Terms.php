<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Terms extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index($code = false)
    {
        $viewData = array(
            'pageTitle'     => 'Terms & Conditions',
            'pageSubTitle'  => 'Ambilis Terms & Conditions',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
            ),
        );

        view('main/misc/terms', $viewData, 'templates/main');

    }

}