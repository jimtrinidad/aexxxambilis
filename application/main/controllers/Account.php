<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

    public function test() {
    }

    public function index()
    {
        if (isGuest()) {
            redirect();
        }

        $viewData = array(
            'pageTitle'     => 'Account',
            'pageSubTitle'  => 'My Account',
            'accountInfo'   => user_account_details()
        );

        view('account/index', $viewData, 'templates/main');
    }

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
                    'status'  => true,
                    'message' => 'Authentication successful.',
                );
            } else {
                $return_data = array(
                    'status'  => false,
                    'message' => 'Authentication failed.',
                );
            }

        }

        response_json($return_data);

    }

    /**
     * Open registration page
     */
    public function signup()
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
    public function register()
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
                        @unlink($this->upload->data('full_name'));
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

}
