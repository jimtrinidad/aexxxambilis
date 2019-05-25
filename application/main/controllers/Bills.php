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
            ),
        );

        $page_limit = 100;
        $page_start = (int) $this->uri->segment(3);

        $order = 'BillerTag';
        $where = array(
            'Status'    => 1,
            'Type'      => 1
        );

        // SET SEARCH FILTER
        if (get_post('search')) {
            $where['CONCAT(Name, Description) LIKE ']  = '%' . get_post('search') . '%';
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

    public function ticket()
    {
        $viewData = array(
            'pageTitle'     => 'Ticket Payment',
            'pageSubTitle'  => 'AMBILIS TO PAY TICKETS',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
            ),
        );

        $page_limit = 100;
        $page_start = (int) $this->uri->segment(3);

        $order = 'BillerTag';
        $where = array(
            'Status'    => 1,
            'Type'      => 2
        );

        // SET SEARCH FILTER
        if (get_post('search')) {
            $where['Name LIKE ']  = '%' . get_post('search') . '%';
        }

        $paginatationData = $this->appdb->getPaginationData('Billers', $page_limit, $page_start, $where, $order);

        $billers = array();
        foreach ($paginatationData['data'] as $i) {
            $i = (array) $i;
            $i['Image']  = logo_filename($i['Image']);
            $billers[] = $i;
        }

        $paginationConfig = array(
            'base_url'      => base_url('bills/ticket'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        $viewData['billers']   = $billers;
        $viewData['pagination'] = paginate($paginationConfig);

        view('main/bills/index', $viewData, 'templates/main');
    }


    // bills payment
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

                        $ecparams   = array(
                            'BillerTag'       => $biller->BillerTag,
                            'AccountNo'       => get_post('AccountNo'),
                            'Identifier'      => get_post('Identifier'),
                            'Amount'          => $amount,
                            'ClientReference' => microsecID(true)
                        );

                        $ecresponse = $this->ecpay->bills_payment_transact($ecparams);

                        if (isset($ecresponse['Status']) && $ecresponse['Status'] == 0) {

                            if ($biller->Type == 1) {
                                $desc = 'Bills';
                            } else if ($biller->Type == 2) {
                                $desc = 'Ticket';
                            }

                            $desc = $desc . ' Payment - ' . $biller->Description . ' - ' . ($ecresponse['Message'] ?? ' Success');


                            $saveData = array(
                                'Code'          => microsecID(true),
                                'AccountID'     => current_user(),
                                'ReferenceNo'   => $ecparams['ClientReference'],
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
                                'message'   => $ecresponse['Message'] ?? 'Payment transaction failed.'
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


    // show padala option page
    // routed on /padala
    public function padala()
    {
        $viewData = array(
            'pageTitle'     => 'Money Padala',
            'pageSubTitle'  => 'AMBILIS Mag Padala!',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
            ),
        );

        $order = 'Name';
        $where = array(
            'Status'    => 1
        );

        // SET SEARCH FILTER
        if (get_post('search')) {
            $where['CONCAT(Name, " ", Description) LIKE ']  = '%' . get_post('search') . '%';
        }

        $results = $this->appdb->getRecords('EcashServices', $where, $order);

        $items = array();
        foreach ($results as $i) {
            $i = (array) $i;
            $i['Image']  = logo_filename($i['Image']);
            $items[] = $i;
        }

        $viewData['items']   = $items;

        // print_data($viewData, true);

        view('main/bills/ecash_services', $viewData, 'templates/main');
    }

    // show telco loading option page
    // routed on /eload
    public function eload()
    {
        $viewData = array(
            'pageTitle'     => 'Telco eLoading',
            'pageSubTitle'  => 'AMBILIS Mag Load!',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
            ),
        );

        $order = 'TelcoName, TelcoTag, (Denomination * 1)';
        $where = array(
            'Status'    => 1
        );

        $results = $this->appdb->getRecords('TelcoTopUps', $where, $order);

        $items = array();
        foreach ($results as $i) {
            $i = (array) $i;
            $items[$i['TelcoName']][] = $i;
        }

        $viewData['items']   = $items;

        // print_data($viewData, true);

        view('main/bills/telco_topups', $viewData, 'templates/main');
    }
}
