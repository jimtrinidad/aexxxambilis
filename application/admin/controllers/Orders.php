<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function index()
    {
        $viewData = array(
            'pageTitle'         => 'Orders',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'wallet'
            )
        );

        $records = $this->appdb->getRecords('Orders', array(), 'Status, id DESC');
        foreach ($records as &$r) {
            
        }
        $viewData['records']   = $records;

        // print_data($viewData);

        view('pages/orders/index', $viewData, 'templates/main');
    }

}
