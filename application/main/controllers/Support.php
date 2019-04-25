<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Support extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index($code = false)
    {
        $viewData = array(
            'pageTitle'     => 'Support',
            'pageSubTitle'  => 'Ambilis Support',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
            ),
        );

        view('main/misc/support', $viewData, 'templates/main');

    }

}
