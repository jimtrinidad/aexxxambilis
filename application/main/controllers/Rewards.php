<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rewards extends CI_Controller
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
            'pageTitle'     => 'Rewards',
            'pageSubTitle'  => 'Ambilis ng Rewards',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
                'wallet'
            ),
        );

        $transaction = get_transactions(current_user());
        $viewData['transactions'] = $transaction['transactions'];
        $viewData['summary']      = $transaction['summary'];

        $viewData['transactions'] = array();

        view('main/rewards/index', $viewData, 'templates/main');
    }

}
