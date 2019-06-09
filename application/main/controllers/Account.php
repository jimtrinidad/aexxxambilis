<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

    public function test() {
        // $item = (array) $this->appdb->getRowObject('StoreItems', get_post('c'), 'Code');
        // print_data(profit_distribution($item['Price'], $item['CommissionValue'], $item['CommissionType']));
        
        // $data = array(
        //     'from'  => array('info@mgov.ph', 'Ambilis.com'),
        //     'to'    => array('jimtrinidad002@gmail.com'),
        //     'subject'   => 'This another is a test ' . date('Y-m-d H:i:s'),
        //     'message'   => 'This is a new <b>test</b> message from info smtp'
        // );
        // print_r($data);
        // send_email($data, false);
        // exit;

        // $data = $this->ecpay->get_ecash_providers();
        // $data = $this->ecpay->ecash_transact(array(
        //     'ServiceType'    => 'EASYTRIP',
        //     'AccountNo'      => '520005121468',
        //     'Identifier'     => 'JIM',
        //     'Amount'         => 50,
        //     'ClientReference'   => microsecID(true)
        // ));
        // $data = $this->ecpay->bills_payment_transact(array(
        //     'BillerTag'     => 'GHPHONE',
        //     'AccountNo'    => '1026746256',
        //     'Identifier'     => 'Jim Alvin Trinidad',
        //     'Amount'        => 10,
        //     'ClientReference'   => microsecID(true)
        // ));
        // $data = $this->ecpay->ecpay_check_balance();
        // print_data($data);
        // echo "before\n";
        // $data = $this->ecpay->gate_check_balance();
        // print_data($data);
        // echo "transact\n";
        // $no = '99668612848';
        // $amount = '10';
        // $data = $this->ecpay->telco_transact(array(
        //     'Telco'        => 'GLOBE',
        //     'CellphoneNo'  => $no,
        //     'ExtTag'       => 'LD',
        //     'Amount'       => $amount,
        //     'Token'        => md5($this->ecpay->branch_id . $no . $amount . date('mdy'))
        // ));
        // print_data($data);
        // // echo "after\n";
        // $data = $this->ecpay->gate_check_balance();
        // var_dump($data);
        // exit;

        // BranchID + CellphoneNo+ Amount + Date (Mmddyy)

        // $lines = file('/home/jim/Desktop/ecpay/ambilispartneroutlet.csv');
        // $data  = array();
        // foreach ($lines as $line) {
        //     $item = str_getcsv($line);
        //     if (count($item) == 2) {
        //         $saveData = array(
        //             'Code'  => microsecID(true),
        //             'Name'  => $item[0],
        //             'Address'   => $item[1],
        //             'LastUpdate'    => datetime()
        //         );

        //         print_r($item);
        //         var_dump($this->appdb->saveData('PartnerOutlets', $saveData));
        //     }

        // }

        // $stores = $this->appdb->getRecords('StoreDetails', array());
        // foreach ($stores as $s) {
        //     $saveData = array(
        //         'id'    => $s['id'],
        //         'Slug'  => slugit($s['Name'])
        //     );
        //     echo $s['Name'] . PHP_EOL;
        //     var_dump($this->appdb->saveData('StoreDetails', $saveData));
        // }

        // response_json(get_user_connections(current_user()));
        // response_json(straight_connections(get_user_connections(current_user())));

    }


    /*
    * Logged in user functions
    */


    public function index()
    {
        if (isGuest()) {
            redirect();
        }

        $userData = user_account_details();

        // print_data($userData);

        $viewData = array(
            'pageTitle'     => 'Account',
            'pageSubTitle'  => 'My Account',
            'accountInfo'   => $userData,
            'jsModules'     => array(
                'account',
                'general'
            )
        );

        $transaction = get_transactions(current_user());
        $viewData['transactions'] = $transaction['transactions'];
        $viewData['summary']      = $transaction['summary'];

        $userData = (array)$userData;

        $viewData['profile'] = array(
            'account_firstname' => $userData['Firstname'],
            'account_lastname'  => $userData['Lastname'],
            'account_mobile'    => $userData['Mobile'],
            'account_email'     => $userData['EmailAddress'],
            'account_bank_name' => $userData['BankName'],
            'account_bank_no'   => $userData['BankAccountNo'],
            'account_bank_account_name' => $userData['BankAccountName'],
            'photo'             => public_url('assets/profile') . photo_filename($userData['Photo']),
            'user_id'           => $userData['RegistrationID']
        );

        $address = $this->appdb->getRowObjectWhere('UserAddress', array('UserID' => current_user(), 'Status' => 1));

        if ($address) {
            $address->data = lookup_address($address);
        }

        $viewData['address'] = $address;

        view('account/index', $viewData, 'templates/main');
    }

    public function save_profile()
    {

        if (isGuest()) {
            redirect();
        }
        
        if (validate('account_update') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $user = $this->appdb->getRowObject('Users', get_post('user_id'), 'RegistrationID');

            if ($user) {

                $randomLogoName = md5(microsecID());

                // validate file upload
                $this->load->library('upload', array(
                    'upload_path'   => PHOTO_DIRECTORY,
                    'allowed_types' => 'gif|jpg|png',
                    // 'max_size'      => '1000', // 1mb
                    // 'max_width'     => '1024',
                    // 'max_height'    => '768',
                    'overwrite'     => true,
                    'file_name'     => $randomLogoName
                ));

                if (!empty($_FILES['account_photo']['name']) && $this->upload->do_upload('account_photo') == false) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Uploading image failed.',
                        'fields'    => array('account_photo' => $this->upload->display_errors('',''))
                    );
                } else {

                    // do save
                    $uploadData     = $this->upload->data();

                    $saveData     = array(
                        'id'                => $user->id,
                        'Firstname'         => get_post('account_firstname'),
                        'Lastname'          => get_post('account_lastname'),
                        'EmailAddress'      => get_post('account_email'),
                        'Mobile'            => get_post('account_mobile'),
                        'BankName'          => get_post('account_bank_name'),
                        'BankAccountNo'     => get_post('account_bank_no'),
                        'BankAccountName'   => get_post('account_bank_account_name'),
                        'LastUpdate'        => datetime()
                    );

                    if (!empty($_FILES['account_photo']['name'])) {
                        $saveData['Photo'] = $uploadData['file_name'];
                    }

                    if ($this->appdb->saveData('Users', $saveData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Profile has been updated successfully.',
                        );

                        // delete old photo if edited
                        if (isset($saveData['Photo']) && !empty($user->Photo)) {
                            @unlink(PHOTO_DIRECTORY . $user->Photo);
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Updating profile failed. Please try again later.'
                        );
                        @unlink($uploadData['file_name']);
                    }

                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid user.'
                );
            }
        }
        response_json($return_data);
    }

    public function save_address()
    {
        if (isGuest()) {
            redirect();
        }

        if (validate('user_address') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $saveData     = array(
                'Street'            => get_post('AddressStreet'),
                'Barangay'          => get_post('AddressBarangay'),
                'City'              => get_post('AddressCity'),
                'Province'          => get_post('AddressProvince'),
                'LastUpdate'        => datetime(),
            );

            $addressData = $this->appdb->getRowObject('UserAddress', get_post('AddressID'));
            if ($addressData) {
                $saveData['id'] = $addressData->id;
            } else {

                $user_addresses = $this->appdb->getRecords('UserAddress', array('UserID' => current_user(), 'Status' => 1));

                // if no active address. set this new one as active 
                if (count($user_addresses) == 0) {
                    $status = 1;
                } else {
                    $status = 0;
                }

                $saveData['UserID'] = current_user();
                $saveData['Status'] = $status;
            }

            if (($ID = $this->appdb->saveData('UserAddress', $saveData))) {
                $return_data = array(
                    'status'    => true,
                    'message'   => 'Account address has been added successfully.',
                    'id'        => $ID
                );
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Adding address failed. Please try again later.'
                );
            }
        }
        response_json($return_data);
    }

    public function delivery_agent_application()
    {
        if (isGuest()) {
            redirect();
        }

        $user = $this->appdb->getRowObject('Users', get_post('user_id'), 'RegistrationID');

        if ($user) {

            $this->load->library('upload');
            $upload_status = true;
            $uploadData    = array();
            $upload_errors = array();

            foreach ($_FILES['file']['name'] as $i => $name) {

                $randomname = md5(random_letters());

                // validate file upload
                $config = array(
                    'upload_path'   => UPLOADS_DIRECTORY,
                    'allowed_types' => 'gif|jpg|png',
                    // 'max_size'      => '1000', // 1mb
                    // 'max_width'     => '1024',
                    // 'max_height'    => '768',
                    'overwrite'     => true,
                    'file_name'     => $randomname
                );
                $this->upload->initialize($config);

                $_FILES['userFile']['name']         = $_FILES['file']['name'][$i];
                $_FILES['userFile']['type']         = $_FILES['file']['type'][$i];
                $_FILES['userFile']['tmp_name']     = $_FILES['file']['tmp_name'][$i];
                $_FILES['userFile']['error']        = $_FILES['file']['error'][$i];
                $_FILES['userFile']['size']         = $_FILES['file']['size'][$i];

                if ($this->upload->do_upload('userFile') == false) {
                    $upload_errors[$i] = $this->upload->display_errors('','');
                    $upload_status = false;
                } else {
                    $fileData = $this->upload->data();
                    $uploadData[$i] = $fileData['file_name'];
                }

            }

            if ($upload_status) {

                $saveData  = array(
                    'Status'        => 0,
                    'Requirements'  => json_encode($uploadData),
                    'DateApplied'   => datetime(),
                    'LastUpdate'    => datetime()
                );
                $agentData = $this->appdb->getRowObject('DeliveryAgents', $user->id, 'UserID');
                if ($agentData) {
                    $saveData['id'] = $StoreData->id;
                } else {
                    $saveData['Code']     = microsecID(true);
                    $saveData['UserID']   = $user->id;
                }

                if (($ID = $this->appdb->saveData('DeliveryAgents', $saveData))) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Delivery Agent application has been submitted.',
                        'id'        => $ID
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving application failed. Please try again later.'
                    );
                }

            } else {
                $return_data = array(
                            'status'    => false,
                            'message'   => 'Uploading image failed.',
                            'fields'    => $upload_errors
                        );
            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid user.'
            );
        }
        
        response_json($return_data);
    }



    /**
    * GUEST
    */

    /**
     * Open login page
     */
    public function signin()
    {
        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle' => 'Sign in',
            'jsModules' => array(
                'account'
            )
        );

        // view('account/login', $viewData, 'templates/account');
        view('account/login', $viewData);
    }

    /**
     * Attempt authentication
     */
    public function login()
    {
        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        // do not allow direct access
        if (!is_ajax()) {
            redirect();
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if ($username == '') {
            $return_data = array(
                'status'  => false,
                'message' => 'Username is required.',
            );
        } else if ($password == '') {
            $return_data = array(
                'status'  => false,
                'message' => 'Password is required.',
            );
        } else {

            if ($this->authentication->login($username, $password)) {
                $return_data = array(
                    'status'   => true,
                    'message'  => 'Authentication successful.',
                    'redirect' => ($this->session->userdata('referrer') ? $this->session->userdata('referrer') : site_url())
                );
                if ($this->session->userdata('referrer')) {
                    $this->session->unset_userdata('referrer');
                }
            } else {
                $return_data = array(
                    'status'  => false,
                    'message' => 'Authentication failed.',
                );
            }

        }

        response_json($return_data);

    }

    public function signup()
    {
        redirect('account/register');
    }

    /**
     * Open registration page
     */
    public function register()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle' => 'Sign up',
            'RegistrationID' => microsecID(),
            'jsModules' => array(
                'account'
            )
        );

        // view('account/registration', $viewData, 'templates/account');
        view('account/registration', $viewData);
    }

    /**
     * attempt and save registration
     */
    public function registration()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

    	if (validate('account_registration') == FALSE) {
            $return_data = array(
            	'status'	=> false,
            	'message'	=> 'Some fields have errors.',
            	'fields'	=> validation_error_array()
            );
        } else {

            $referrer = $this->appdb->getRowObject('Users', get_post('Referrer'), 'PublicID');

            if ($referrer) {

                $registrationID = get_post('RegistrationID');
                $PublicID       = generate_public_id(get_post('Lastname'));

                $insertData     = array(
                    'Referrer'          => $referrer->id,
                    'PublicID'          => $PublicID,
                    'RegistrationID'    => $registrationID,
                    'Firstname'         => get_post('Firstname'),
                    'Lastname'          => get_post('Lastname'),
                    'EmailAddress'      => get_post('EmailAddress'),
                    'Mobile'            => get_post('Mobile'),
                    'Password'          => $this->authentication->hash_password(get_post('Password')),
                    'AccountLevel'      => 1, // default, regular user,
                    'Status'            => 1, // active
                    'DateAdded'         => datetime(),
                    'LastUpdate'        => datetime()
                );

                if ($this->appdb->getRowObject('Users', $registrationID, 'RegistrationID') === false) {

                    if (($ID = $this->appdb->saveData('Users', $insertData))) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Account registration successful. You will can now login your account.',
                            'id'        => $ID
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Registration failed. Please try again later.'
                        );
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Account already exists.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid referrer id.'
                );
            }
        }
    	response_json($return_data);
    }

    /**
     * destroy session, redirect to homepage/login
     */
    public function logout()
    {
        $this->authentication->logout();
        redirect();
    }

    public function unique_account_email($value)
    {
        $this->db->where_not_in('RegistrationID', $this->input->post('user_id'));
        $this->db->where('EmailAddress', $value);
        if($this->db->count_all_results('Users') > 0){
            return false;
        }else{
            return true;
        }
    }

    public function unique_account_mobile($value)
    {
        $this->db->where_not_in('RegistrationID', $this->input->post('user_id'));
        $this->db->where('Mobile', $value);
        if($this->db->count_all_results('Users') > 0){
            return false;
        }else{
            return true;
        }
    }

    public function forgot()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle' => 'Forgot Password',
            'jsModules' => array(
                'account'
            )
        );

        view('account/forgot', $viewData);
    }

    public function forgot_password()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        if (validate('forgot_password') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                // Google reCAPTCHA API secret key 
                $secretKey = '6LfemqAUAAAAAC2NS4k9XOaNbZG-4ITv8lhIF5vH'; 
                 
                // Verify the reCAPTCHA response 
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']); 
                 
                // Decode json data 
                $responseData  = json_decode($verifyResponse); 
                 
                // If reCAPTCHA response is valid 
                if ($responseData->success) {

                    $user = $this->appdb->getRowObject('Users', get_post('account_email'), 'EmailAddress');

                    if ($user) {

                        $saveData = array(
                            'AccountID'     => $user->id,
                            'Code'          => sha1(microsecID(true)),
                            'Expiration'    => strtotime('+12 hours')
                        );

                        if ($this->appdb->saveData('ResetPassword', $saveData)) {

                            $emailData = array(
                                'from'      => array('info@mgov.ph', 'Ambilis.com'),
                                'to'        => array($user->EmailAddress),
                                'subject'   => 'Reset Password',
                                'message'   => view('email_templates/forgot_password', array(
                                    'firstname' => $user->Firstname,
                                    'code'      => $saveData['Code']
                                ), null, true)
                            );
                            send_email($emailData, true);

                            $return_data = array(
                                'status'    => true,
                                'message'   => 'Instruction will be send to your email addres on how to reset your password.'
                            );

                        } else {
                            $return_data = array(
                                'status'    => false,
                                'message'   => 'Request failed. Please try again later.'
                            );
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Account email address does not exists.'
                        );
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Robot verification failed, please try again.'
                    );
                } 
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Please check on the reCAPTCHA box.'
                );
            }

            
        }

        response_json($return_data);
    }

    public function reset($code = '')
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }

        $resetCode = $this->appdb->getRowObject('ResetPassword', $code, 'Code');
        if ($code && $resetCode && time() <= $resetCode->Expiration) {

            $user = $this->appdb->getRowObject('Users', $resetCode->AccountID);

            $viewData = array(
                'pageTitle' => 'Reset Password',
                'jsModules' => array(
                    'account'
                )
            );

            $viewData['reset_code'] = $resetCode->Code;

            view('account/reset', $viewData);
        } else {
            show_404();
        }
    }

    public function reset_password()
    {

        // if already logged in, redirect to home page
        if (!isGuest()) {
            redirect();
        }
        
        if (validate('reset_password') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $resetCode = $this->appdb->getRowObject('ResetPassword', get_post('reset_code'), 'Code');
            if ($resetCode) {

                $user = $this->appdb->getRowObject('Users', $resetCode->AccountID);

                if ($user) {

                    $saveData = array(
                        'id'            => $user->id,
                        'Password'      => $this->authentication->hash_password(get_post('Password')),
                        'LastUpdate'    => datetime()
                    );

                    if ($this->appdb->saveData('Users', $saveData)) {

                        $this->appdb->deleteData('ResetPassword', $resetCode->id);

                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Password has been changed successfully.'
                        );

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Changing password failed. Please try again later.'
                        );
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid account password reset.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid password reset request.s'
                );
            }
            
        }

        response_json($return_data);
    }

}
