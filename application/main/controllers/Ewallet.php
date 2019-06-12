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

    public function transactions()
    {
        $viewData = array(
            'pageTitle'     => 'My Transactions',
            'pageSubTitle'  => 'AMBILIS NANG Transactions',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
            )
        );

        $transaction = get_transactions(current_user());
        $viewData['transactions'] = $transaction['transactions'];
        $viewData['summary']      = $transaction['summary'];

        $page_limit = 500;
        $page_start = (int) $this->uri->segment(3);

        $where = array(
            'UserID'    => current_user()
        );
        $order = 'TransactionDate DESC';

        // SET SEARCH FILTER
        $filters = array(
            'search_user',
            'search_name',
        );
        foreach ($filters as $filter) {

            $$filter = get_post($filter);

            if ($filter == 'search_user' && $$filter != false) {
                $where['CONCAT(Firstname, " ", Lastname) LIKE ']  = "%{$search_user}%";
            } else if ($filter == 'search_name' && $$filter != false) {
                $where['MerchantName LIKE ']  = "%{$search_name}%";
            }

            // search params
            $viewData[$filter] = $$filter;

        }

        $paginatationData = $this->appdb->getECpayTransactions($page_limit, $page_start, $where, $order);

        // prepare account data
        $items = array();
        foreach ($paginatationData['data'] as $item) {
            $item    = (array) $item;
            $rewards = $this->appdb->getRewardsData(array(
                            'OrderID'       => $item['id'],
                            'TransactType'  => $item['MerchantType']
                        ), 'Type');

            foreach ($rewards as &$reward) {
                $reward['Type']   = lookup('wallet_rewards_type', $reward['Type']);
                $reward['Amount'] = peso($reward['Amount'], true, 4);
            }
            $item['Rewards'] = $rewards;
            $items[] = $item;
        }

        $paginationConfig = array(
            'base_url'      => base_url('ewallet/transactions'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin">'
        );

        $viewData['records']    = $items;
        $viewData['pagination'] = paginate($paginationConfig);

        // print_data($items);

        view('main/ewallet/transactions', $viewData, 'templates/main');
    }


    public function add_deposit()
    {
        if (empty($_FILES['Photo']['name']))
        {
            $this->form_validation->set_rules('Photo', 'Screenshot', 'required');
        }

        if (validate('add_deposit') == FALSE) {
            $return_data = array(
                'status'    => false,
                // 'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $exists = $this->appdb->getRowObjectWhere('WalletDeposits', array(
                                                        'ReferenceNo'   => get_post('ReferenceNo'),
                                                        'Amount'        => get_post('Amount'),
                                                        'AccountID'     => current_user()
                                                    ));

            if ($exists) {

                $return_data = array(
                    'status'    => false,
                    'message'   => 'Duplicate transaction.',
                );

            } else {

                $randomName = md5(microsecID());

                // validate file upload
                $this->load->library('upload', array(
                    'upload_path'   => UPLOADS_DIRECTORY,
                    'allowed_types' => 'gif|jpg|png',
                    // 'max_size'      => '1000', // 1mb
                    // 'max_width'     => '1024',
                    // 'max_height'    => '768',
                    'overwrite'     => true,
                    'file_name'     => $randomName
                ));

                if (empty($_FILES['Photo']['name'])) {
                    $return_data = array(
                        'status'    => false,
                        // 'message'   => 'Sceenshot/Slip  is required.',
                        'fields'    => array('Photo' => 'Sceenshot/Slip  is required.')
                    );
                } else if ($this->upload->do_upload('Photo') == false) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Uploading slip failed.',
                        'fields'    => array('Photo' => $this->upload->display_errors('',''))
                    );
                } else {

                    // do save
                    $uploadData     = $this->upload->data();

                    $saveData = array(
                        'Code'              => microsecID(),
                        'Bank'              => get_post('Bank'),
                        'Branch'            => get_post('Branch'),
                        'AccountID'         => current_user(),
                        'ReferenceNo'       => get_post('ReferenceNo'),
                        'TransactionDate'   => get_post('Date'),
                        'Amount'            => get_post('Amount'),
                        'Photo'             => $uploadData['file_name'],
                        'Status'            => 0, // pending
                        'DateAdded'         => date('Y-m-d H:i:s')  
                    );

                    if (($ID = $this->appdb->saveData('WalletDeposits', $saveData))) {

                        $return_data = array(
                            'status'    => true,
                            'message'   => ucfirst(number_to_words(get_post('Amount'))) . ' pesos fund has been requested. It will be credited to your wallet upon verification.',
                            'id'        => $ID
                        );

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Adding wallet fund failed. Please try again later.'
                        );
                    }

                }

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

                    if (have_deposit(current_user())) {

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

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Initial wallet fund is required to make a transaction.'
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

            if (have_deposit(current_user())) {

                $latest_balance = get_latest_wallet_balance();

                $amount = get_post('Amount');
                $biller = $this->appdb->getRowObject('Billers', get_post('Biller'), 'Code');
                if ($biller) {
                    if ($amount > 0) {
                        if ($latest_balance >= ($amount + $biller->ServiceCharge)) {

                            $current_balance = $this->ecpay->ecpay_check_balance();

                            if ($current_balance !== false) {

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

                                    $new_balance = $this->ecpay->ecpay_check_balance();

                                    $this->db->trans_start();

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
                                        $invoice_data = array(
                                            'Merchant'          => $biller->Description,
                                            clean_text($biller->FirstField)    => get_post('AccountNo'),
                                            clean_text($biller->SecondField)   => get_post('Identifier'),
                                            'Reference Number'  => $saveData['ReferenceNo'],
                                            'Amount'            => peso($amount),
                                            'Transaction Fee'   => peso($ecresponse['ServiceCharge']),
                                            'Transaction Date'  => datetime(),
                                        );

                                        if ($ecresponse['ServiceCharge'] == 0) {
                                            unset($invoice_data['Transaction Fee']);
                                        }

                                        $return_data = array(
                                            'status'    => true,
                                            'message'   => 'Payment transaction has been made.',
                                            'image'     => public_url('assets/logo/' ) . logo_filename($biller->Image),
                                            'data'      => $invoice_data
                                        );

                                        // save transaction and distribute reward
                                        ecpay_save_transaction(array(
                                            'code'          => $saveData['Code'],
                                            'merch_type'    => ($biller->Type == 2 ? 3 : 2), // biller type 2 = ticket,
                                            'merch_id'      => $biller->id,
                                            'merch_name'    => $biller->Description,
                                            'amount'        => $amount,
                                            'prev_bal'      => $current_balance,
                                            'new_bal'       => $new_balance,
                                            'fee'           => $ecresponse['ServiceCharge'],
                                            'user'          => current_user(),
                                            'refno'         => $saveData['ReferenceNo'],
                                            'trans_date'    => $saveData['Date'],
                                            'ecrequest'     => $ecparams,
                                            'ecresponse'    => $ecresponse,
                                            'invoice'       => $invoice_data
                                        ));

                                    } else {
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Saving transaction failed.'
                                        );
                                    }

                                    $this->db->trans_complete();

                                } else {
                                    $return_data = array(
                                        'status'    => false,
                                        'message'   => isset($ecresponse['Message']) ? ($ecresponse['Message'] . ' ( ' . ($ecresponse['Status'] ?? 'x') . ' )') : 'Transaction failed. Please try again later'
                                    );
                                }

                            } else {
                                $return_data = array(
                                    'status'    => false,
                                    'message'   => 'Cannot transact at this time. Please try again later'
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

            } else {
                $return_data = array(
                        'status'    => false,
                        'message'   => 'Initial wallet fund is required to make a transaction.'
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

            if (have_deposit(current_user())) {

                $latest_balance = get_latest_wallet_balance();
                $amount         = get_post('Amount');

                $service = $this->appdb->getRowObject('EcashServices', get_post('ServiceType'), 'Code');
                if ($service) {                

                    if ($amount > 0) {

                        if ($latest_balance >= $amount) {

                            $current_balance = $this->ecpay->gate_check_balance();

                            if ($current_balance !== false) {

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

                                    $new_balance = $this->ecpay->gate_check_balance();

                                    $this->db->trans_start();

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
                                        $invoice_data = array(
                                            'Merchant'          => $service->Description,
                                            clean_text($service->FirstField)    => get_post('AccountNo'),
                                            clean_text($service->SecondField)   => get_post('Identifier'),
                                            'Reference Number'  => $saveData['ReferenceNo'],
                                            'Amount'            => peso($amount),
                                            'Transaction Fee'   => peso($ecresponse['servicecharge']),
                                            'Transaction Date'  => datetime(),
                                        );

                                        if ($ecresponse['servicecharge'] == 0) {
                                            unset($invoice_data['Transaction Fee']);
                                        }
                                        
                                        $return_data = array(
                                            'status'    => true,
                                            'message'   => $service->Name . ' transaction has been made.',
                                            'image'     => public_url('assets/logo/' ) . logo_filename($service->Image),
                                            'data'      => $invoice_data
                                        );

                                        // save transaction and distribute reward
                                        ecpay_save_transaction(array(
                                            'code'          => $saveData['Code'],
                                            'merch_type'    => 5,
                                            'merch_id'      => $service->id,
                                            'merch_name'    => $service->Description,
                                            'amount'        => $amount,
                                            'prev_bal'      => $current_balance,
                                            'new_bal'       => $new_balance,
                                            'fee'           => $ecresponse['servicecharge'],
                                            'user'          => current_user(),
                                            'refno'         => $saveData['ReferenceNo'],
                                            'trans_date'    => $saveData['Date'],
                                            'ecrequest'     => $ecparams,
                                            'ecresponse'    => $ecresponse,
                                            'invoice'       => $invoice_data
                                        ));

                                    } else {
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Transaction failed.'
                                        );
                                    }

                                    $this->db->trans_complete();

                                } else {
                                    $return_data = array(
                                        'status'    => false,
                                        'message'   => isset($ecresponse['description']) ? ($ecresponse['description'] . ' ( ' . ($ecresponse['statusid'] ?? 'x') . ' )') : 'Transaction failed. Please try again later'
                                    );
                                }

                            } else {
                                $return_data = array(
                                    'status'    => false,
                                    'message'   => 'Cannot transact at this time. Please try again later'
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

            } else {
                $return_data = array(
                        'status'    => false,
                        'message'   => 'Initial wallet fund is required to make a transaction.'
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

            if (have_deposit(current_user())) {

                $latest_balance = get_latest_wallet_balance();
                $service = $this->appdb->getRowObject('TelcoTopUps', get_post('LoadTag'), 'Code');
                if ($service) {

                    $amount = $service->Denomination;

                    if ($amount > 0) {
                        if ($latest_balance >= $amount) {

                            $current_balance = $this->ecpay->gate_check_balance();

                            if ($current_balance !== false) {

                                $ecparams   = array(
                                    'Telco'        => $service->TelcoName,
                                    'CellphoneNo'  => get_post('Number'),
                                    'ExtTag'       => $service->ExtTag,
                                    'Amount'       => $amount,
                                    'Token'        => md5($this->ecpay->branch_id . get_post('Number') . $amount . date('mdy'))
                                );
                                $ecresponse = $this->ecpay->telco_transact($ecparams);
                                // $ecresponse = array(
                                //     'StatusCode'    => 0,
                                //     'StatusMessage' => 'Success!',
                                //     'TraceNo'       => 123123123,
                                // );


                                if (isset($ecresponse['StatusCode']) && $ecresponse['StatusCode'] == 0) {

                                    $new_balance = $this->ecpay->gate_check_balance();

                                    $this->db->trans_start();

                                    $desc   = 'eLoad: ' . $service->TelcoName . ' - ' . $service->TelcoTag . ' ('. get_post('Number') .') ' . ($ecresponse['StatusMessage'] ?? '');
                                    $saveData = array(
                                        'Code'          => microsecID(true),
                                        'AccountID'     => current_user(),
                                        'ReferenceNo'   => $ecresponse['TraceNo'] ?? $ecparams['Token'],
                                        'Description'   => $desc,
                                        'Date'          => datetime(),
                                        'Amount'        => $amount,
                                        'Type'          => 'Debit',
                                        'EndingBalance' => ($latest_balance - $amount),
                                        'Details'       => json_encode($ecresponse),
                                    );

                                    if ($this->appdb->saveData('WalletTransactions', $saveData)) {

                                        $invoice_data = array(
                                            'Telco'             => $service->TelcoName,
                                            'Type'              => $service->TelcoTag,
                                            'Number'            => get_post('Number'),
                                            'Amount'            => peso($amount),
                                            'Trace Number'      => $ecresponse['TraceNo'],
                                            'Transaction Date'  => datetime(),
                                        );

                                        $return_data = array(
                                            'status'    => true,
                                            'message'   => 'Mobile loading transaction has been made.',
                                            'image'     => public_url('resources/images/telco/' ) . strtolower($service->TelcoName) . '.jpg',
                                            'data'      => $invoice_data
                                        );

                                        // save transaction and distribute reward
                                        ecpay_save_transaction(array(
                                            'code'          => $saveData['Code'],
                                            'merch_type'    => 4,
                                            'merch_id'      => $service->id,
                                            'merch_name'    => ($service->TelcoName . ' - ' . $service->TelcoTag),
                                            'amount'        => $amount,
                                            'prev_bal'      => $current_balance,
                                            'new_bal'       => $new_balance,
                                            'fee'           => 0,
                                            'user'          => current_user(),
                                            'refno'         => $saveData['ReferenceNo'],
                                            'trans_date'    => $saveData['Date'],
                                            'ecrequest'     => $ecparams,
                                            'ecresponse'    => $ecresponse,
                                            'invoice'       => $invoice_data
                                        ));

                                    } else {
                                        $return_data = array(
                                            'status'    => false,
                                            'message'   => 'Saving transaction failed.'
                                        );
                                    }

                                    $this->db->trans_complete();

                                } else {
                                    $return_data = array(
                                        'status'    => false,
                                        'message'   => isset($ecresponse['StatusMessage']) ? ($ecresponse['StatusMessage'] . ' ( ' . ($ecresponse['StatusCode'] ?? 'x') . ' )') : 'Transaction failed. Please try again later',
                                    );
                                }

                            } else {
                                $return_data = array(
                                    'status'    => false,
                                    'message'   => 'Cannot transact at this time. Please try again later'
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

            } else {
                $return_data = array(
                        'status'    => false,
                        'message'   => 'Initial wallet fund is required to make a transaction.'
                    );
            }

        }

        response_json($return_data);
    }
}
