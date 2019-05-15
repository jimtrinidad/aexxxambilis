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

            $user = $this->appdb->getRowObject('Users', current_user());

            if ($user) {

                if (empty($user->BankName)) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Your bank info is not set.'
                    );
                } else if (empty($user->BankAccountNo)) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Your bank account number is not set.'
                    );
                } else if (empty($user->BankAccountName)) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Your bank account name is not set.'
                    );
                } else {

                    $latest_balance = get_latest_wallet_balance();

                    $amount = get_post('Amount');
                    $desc   = 'Encash to debit card.';

                    if ($amount > 0) {

                        if ($latest_balance >= $amount) {

                            $saveData = array(
                                'Code'          => microsecID(),
                                'AccountID'     => current_user(),
                                'ReferenceNo'   => microsecID(true),
                                'Description'   => $desc,
                                'Date'          => date('Y-m-d H:i:s'),
                                'Amount'        => $amount,
                                'Type'          => 'Debit',
                                'EndingBalance' => ($latest_balance - $amount)
                            );

                            if ($this->appdb->saveData('WalletTransactions', $saveData)) {
                                $return_data = array(
                                    'status'    => true,
                                    'message'   => 'Wallet encash transaction has been requested successfully.'
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

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid request.'
                );
            }
        }

        response_json($return_data);
    }

    public function money_padala()
    {
        if (validate('money_padala_request') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $latest_balance = get_latest_wallet_balance();
            $amount = get_post('Amount');

            $service = $this->appdb->getRowObject('EncashServices', get_post('ServiceType'), 'Code');
            if ($service) {

                $desc   = 'Money Padala - ' . $service->Name . ' - ' . $service->Description;

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
                                'message'   => $service->Name . ' transaction has been requested successfully.'
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

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid encash service.'
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
                        'Description'   => lookup('mobile_service_provider', get_post('ServiceProvider')) . ' e-load (' . get_post('Number') . ')',
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
