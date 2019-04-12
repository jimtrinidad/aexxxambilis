<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Connections extends CI_Controller
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
            'pageTitle'     => 'My Wallet',
            'pageSubTitle'  => 'Ambilis ng Referrals!',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
                'wallet'
            )
        );

        $transaction = get_transactions(current_user());
        $viewData['transactions'] = $transaction['transactions'];
        $viewData['summary']      = $transaction['summary'];

        $connections = $this->appdb->getRecords('Users', array('Referrer' => current_user()));
        $viewData['connections'] = $connections;

        view('main/connections/index', $viewData, 'templates/main');
    }

}
