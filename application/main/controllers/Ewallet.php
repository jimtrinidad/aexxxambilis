<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ewallet extends CI_Controller
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
            'pageSubTitle'  => 'AMBILIS NANG E-WALLET',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
                'wallet'
            )
        );

        $transaction = get_transactions(current_user());
        $viewData['transactions'] = $transaction['transactions'];
        $viewData['summary']      = $transaction['summary'];

        view('main/ewallet/index', $viewData, 'templates/main');
    }


    public function add_deposit()
    {
        if (validate('add_deposit') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $saveData = array(
                'Code'              => microsecID(),
                'Bank'              => get_post('Bank'),
                'Branch'            => get_post('Branch'),
                'AccountID'         => current_user(),
                'ReferenceNo'       => get_post('ReferenceNo'),
                'TransactionDate'   => get_post('Date'),
                'Amount'            => get_post('Amount'),
                'Status'            => 0, // pending
                'DateAdded'         => date('Y-m-d H:i:s')  
            );

            if (($ID = $this->appdb->saveData('WalletDeposits', $saveData))) {

                $return_data = array(
                    'status'    => true,
                    'message'   => ucfirst(number_to_words(get_post('Amount'))) . ' pesos deposit has been requested. It will be credited to your wallet upon verification.',
                    'id'        => $ID
                );

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Adding deposit slip failed. Please try again later.'
                );
            }

        }

        response_json($return_data);
    }

    public function encash()
    {
        if (validate('encash_request') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $latest_balance = get_latest_wallet_balance();

            $amount = get_post('Amount');
            $desc   = 'Encash - ' . get_post('ServiceType');

            if ($amount > 0) {

                if ($latest_balance >= $amount) {

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
                            'message'   => 'Encash transaction has been requested successfully.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Transaction failed.'
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
        }

        response_json($return_data);
    }

    public function add_payment()
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
            $biller = $this->appdb->getRowObjectWhere('Service_Services', array('Code' => get_post('Biller'), 'SubDepartmentID' => DBP_ORG_ID));
            if ($biller) {
                if ($amount > 0) {
                    if ($latest_balance >= $amount) {

                        $desc = 'Bills Payment - ' . $biller->Name . ' (' . $biller->Code . ')';

                        $saveData = array(
                            'Code'          => microsecID(),
                            'AccountID'     => current_user(),
                            'ReferenceNo'   => get_post('ReferenceNo'),
                            'Description'   => $desc,
                            'Date'          => date('Y-m-d H:i:s'),
                            'Amount'        => $amount,
                            'Type'          => 'Debit',
                            'EndingBalance' => ($latest_balance - $amount)
                        );

                        if ($this->appdb->saveData('WalletTransactions', $saveData)) {
                            $return_data = array(
                                'status'    => true,
                                'message'   => 'Payment transaction has been added.'
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

    public function eload()
    {
        if (validate('send_eload') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $latest_balance = get_latest_wallet_balance();

            $amount = get_post('Amount');
            if ($amount > 0) {
                if ($latest_balance >= $amount) {
                    $saveData = array(
                        'Code'          => microsecID(),
                        'AccountID'     => current_user(),
                        'ReferenceNo'   => get_post('Number'),
                        'Description'   => 'eLoad (' . get_post('Number') . ')',
                        'Date'          => date('Y-m-d H:i:s'),
                        'Amount'        => $amount,
                        'Type'          => 'Debit',
                        'EndingBalance' => ($latest_balance - $amount)
                    );

                    if ($this->appdb->saveData('WalletTransactions', $saveData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Mobile loading transaction has been successful.'
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

        }

        response_json($return_data);
    }
}
