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

    // bills payment
    public function bill()
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
                        // $ecresponse = array(
                        //     'Status'    => 0,
                        //     'Message'       => 'Success!',
                        //     'ServiceCharge' => '15.00'
                        // );

                        if (isset($ecresponse['Status']) && $ecresponse['Status'] == 0) {

                            $desc = lookup('biller_type', $biller->Type);
                            $desc = $desc . ' Payment - ' . $biller->Description . ' - ' . ($ecresponse['Message'] ?? ' Success');

                            $total_amount     = $amount + ((int) $ecresponse['ServiceCharge']);

                            $saveData = array(
                                'Code'          => microsecID(true),
                                'AccountID'     => current_user(),
                                'ReferenceNo'   => $ecparams['ClientReference'],
                                'Description'   => $desc,
                                'Date'          => date('Y-m-d H:i:s'),
                                'Amount'        => $total_amount,
                                'Type'          => 'Debit',
                                'EndingBalance' => ($latest_balance - $total_amount),
                                'Details'       => json_encode($ecresponse),
                            );

                            if ($this->appdb->saveData('WalletTransactions', $saveData)) {
                                $return_data = array(
                                    'status'    => true,
                                    'message'   => 'Payment transaction has been made.',
                                    'image'     => public_url('assets/logo/' ) . logo_filename($biller->Image),
                                    'data'      => array(
                                        'Merchant'          => $biller->Description,
                                        clean_text($biller->FirstField)    => get_post('AccountNo'),
                                        clean_text($biller->SecondField)   => get_post('Identifier'),
                                        'Reference Number'  => $saveData['ReferenceNo'],
                                        'Amount'            => peso($amount),
                                        'Transaction Fee'   => peso($ecresponse['ServiceCharge']),
                                        'Transaction Date'  => datetime(),
                                    )
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
                                'message'   => isset($ecresponse['Message']) ? ($ecresponse['Message'] . ' ( ' . ($ecresponse['Status'] ?? 'x') . ' )') : 'Payment transaction failed.'
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

    // eccash
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
            $amount         = get_post('Amount');

            $service = $this->appdb->getRowObject('EcashServices', get_post('ServiceType'), 'Code');
            if ($service) {                

                if ($amount > 0) {

                    if ($latest_balance >= $amount) {

                        $ecparams   = array(
                            'ServiceType'     => $service->Services,
                            'AccountNo'       => get_post('AccountNo'),
                            'Identifier'      => get_post('Identifier'),
                            'Amount'          => $amount,
                            'ClientReference' => microsecID(true)
                        );
                        $ecresponse = $this->ecpay->ecash_transact($ecparams);
                        // $ecresponse = json_decode('{"statusid":"0","description":"Success","refno":"DD57A6E897B1","servicecharge":10.00,"serviceref":"R0000004148"}', true);

                        if (isset($ecresponse['statusid']) && $ecresponse['statusid'] == 0) {

                            $desc   = 'Money Padala - ' . $service->Name . ' - ' . ($ecresponse['description'] ?? '');

                            $total_amount     = $amount + ((int) $ecresponse['servicecharge']);

                            $saveData = array(
                                'Code'          => microsecID(true),
                                'AccountID'     => current_user(),
                                'ReferenceNo'   => $ecresponse['serviceref'] ?? $ecparams['ClientReference'],
                                'Description'   => $desc,
                                'Date'          => date('Y-m-d H:i:s'),
                                'Amount'        => $total_amount,
                                'Type'          => 'Debit',
                                'EndingBalance' => ($latest_balance - $total_amount),
                                'Details'       => json_encode($ecresponse),
                            );

                            if ($this->appdb->saveData('WalletTransactions', $saveData)) {
                                $return_data = array(
                                    'status'    => true,
                                    'message'   => $service->Name . ' transaction has been made.',
                                    'image'     => public_url('assets/logo/' ) . logo_filename($service->Image),
                                    'data'      => array(
                                        'Merchant'          => $service->Description,
                                        clean_text($service->FirstField)    => get_post('AccountNo'),
                                        clean_text($service->SecondField)   => get_post('Identifier'),
                                        'Reference Number'  => $saveData['ReferenceNo'],
                                        'Amount'            => peso($amount),
                                        'Transaction Fee'   => peso($ecresponse['servicecharge']),
                                        'Transaction Date'  => datetime(),
                                    )
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
                                'message'   => isset($ecresponse['description']) ? ($ecresponse['description'] . ' ( ' . ($ecresponse['statusid'] ?? 'x') . ' )') : 'Ecash transaction failed.'
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
                    'message'   => 'Invalid ecash service.'
                );
            }
        }

        response_json($return_data);
    }

    // telco load
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
            $service = $this->appdb->getRowObject('TelcoTopUps', get_post('LoadTag'), 'Code');
            if ($service) {

                $amount = $service->Denomination;
                $desc   = 'eLoad: ' . $service->TelcoName . ' - ' . $service->TelcoTag . ' ('. get_post('Number') .') ' . ($ecresponse['StatusMessage'] ?? '');

                if ($amount > 0) {
                    if ($latest_balance >= $amount) {

                        $ecresponse = $this->ecpay->telco_transact(array(
                            'Telco'        => $service->TelcoName,
                            'CellphoneNo'  => get_post('Number'),
                            'ExtTag'       => $service->ExtTag,
                            'Amount'       => $amount,
                            'Token'        => md5($this->ecpay->branch_id . get_post('Number') . $amount . date('mdy'))
                        ));
                        // $ecresponse = array(
                        //     'StatusCode'    => 0,
                        //     'StatusMessage' => 'Success!',
                        //     'TraceNo'       => 123123123,
                        // );

                        if (isset($ecresponse['StatusCode']) && $ecresponse['StatusCode'] == 0) {

                            $saveData = array(
                                'Code'          => microsecID(true),
                                'AccountID'     => current_user(),
                                'ReferenceNo'   => get_post('Number'),
                                'Description'   => $desc,
                                'Date'          => date('Y-m-d H:i:s'),
                                'Amount'        => $amount,
                                'Type'          => 'Debit',
                                'EndingBalance' => ($latest_balance - $amount),
                                'Details'       => json_encode($ecresponse),
                            );

                            if ($this->appdb->saveData('WalletTransactions', $saveData)) {
                                $return_data = array(
                                    'status'    => true,
                                    'message'   => 'Mobile loading transaction has been made.',
                                    'image'     => public_url('resources/images/telco/' ) . strtolower($service->TelcoName) . '.jpg',
                                    'data'      => array(
                                        'Telco'             => $service->TelcoName,
                                        'Type'              => $service->TelcoTag,
                                        'Number'            => get_post('Number'),
                                        'Amount'            => peso($amount),
                                        'Trace Number'      => $ecresponse['TraceNo'],
                                        'Transaction Date'  => datetime(),
                                    )
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
                                'message'   => isset($ecresponse['StatusMessage']) ? ($ecresponse['StatusMessage'] . ' ( ' . ($ecresponse['StatusCode'] ?? 'x') . ' )') : 'eLoading transaction failed.'
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
                    'message'   => 'Invalid load request.'
                );
            }

        }

        response_json($return_data);
    }
}
