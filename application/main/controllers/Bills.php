<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bills extends CI_Controller
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
            'pageTitle'     => 'Bills Payment',
            'pageSubTitle'  => 'AMBILIS TO PAY BILLS',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
                'wallet'
            ),
        );

        $page_limit = 20;
        $page_start = (int) $this->uri->segment(3);

        $order = 'BillerTag';
        $where = array();

        // SET SEARCH FILTER
        if (get_post('search')) {
            $where['CONCAT(BillerTag, " ", Description) LIKE ']  = '%' . get_post('search') . '%';
        }

        $paginatationData = $this->appdb->getPaginationData('Billers', $page_limit, $page_start, $where, $order);

        $billers = array();
        foreach ($paginatationData['data'] as $i) {
            $i = (array) $i;
            $i['Image']  = logo_filename($i['Image']);
            $billers[] = $i;
        }

        $paginationConfig = array(
            'base_url'      => base_url('bills/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        $viewData['billers']   = $billers;
        $viewData['pagination'] = paginate($paginationConfig);

        view('main/bills/index', $viewData, 'templates/main');
    }


    public function payment()
    {
        if (validate('add_payment') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $latest_balance = get_latest_wallet_balance();

            $amount = get_post('Amount');
            $biller = $this->appdb->getRowObject('Billers', get_post('Biller'), 'Code');
            if ($biller) {
                if ($amount > 0) {
                    if ($latest_balance >= $amount) {

                        $desc = 'Bills Payment - ' . $biller->BillerTag . ' - ' . $biller->Description;

                        $saveData = array(
                            'Code'          => microsecID(),
                            'AccountID'     => current_user(),
                            'ReferenceNo'   => get_post('AccountNo'),
                            'Description'   => $desc,
                            'Date'          => date('Y-m-d H:i:s'),
                            'Amount'        => $amount,
                            'Type'          => 'Debit',
                            'EndingBalance' => ($latest_balance - $amount)
                        );

                        if ($this->appdb->saveData('WalletTransactions', $saveData)) {
                            $return_data = array(
                                'status'    => true,
                                'message'   => 'Payment transaction has been made.'
                            );
                        } else {
                            $return_data = array(
                                'status'    => false,
                                'message'   => 'Saving transaction failed.'
                            );
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Insufficient balance.'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid amount.'
                    );
                }
            } else {
                $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid biller/merchant.'
                    );
            }

        }

        response_json($return_data);
    }

}
