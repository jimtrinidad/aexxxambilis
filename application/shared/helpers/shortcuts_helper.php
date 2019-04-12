<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* render view
*/
function view($view, $data = array(), $template = null, $return = false)
{
	$ci =& get_instance();
	if ($template !== null) {
		$content = $ci->load->view($view, $data, true);
		$data['templateContent'] = $content;
		$rendered = $ci->load->view($template, $data, $return);
	} else {
		$rendered = $ci->load->view($view, $data, $return);
	}

	if ($return) {
		return $rendered;
	}
}

/**
* return request input
*/
function get_post($key)
{	
	$ci =& get_instance();
	return $ci->input->get_post($key);
}

/**
* is ajax request
*/
function is_ajax()
{
	$ci =& get_instance();
    return $ci->input->is_ajax_request();
}

/**
* public url for both front and admin
*/
function public_url($segment = '')
{
	$base_url = base_url();
	$base_url = rtrim(str_replace('/admin', '', $base_url), '/') . '/';
	if ($segment != '') {
		$base_url = $base_url . trim($segment, '/') . '/';
	}
	return $base_url;
}


/**
* run validation
* get rules from config
*/
function validate($rules, $data = null)
{	
	$ci =& get_instance();
		
	// if validation rules key is provided, get from validation config
	if (is_string($rules)) {
    	$rules = $ci->config->item($rules);
    }

    if ($data !== null) {
		// reset validation and set new data to validate
		$ci->form_validation->reset_validation();
		$ci->form_validation->set_data($data);
	}

    if (is_array($rules)) {
		foreach ($rules as $rule) {
			call_user_func_array(array($ci->form_validation, 'set_rules'), $rule);
		}
		return $ci->form_validation->run();
	}

	// invalid rules
	return false;
}

/**
* return validation error in array
*/
function validation_error_array()
{
	$ci =& get_instance();
	return array_map('ucfirst', array_map('strtolower', $ci->form_validation->error_array()));
}


/**
* create pagination links
*/
function paginate($config)
{	
	$ci =& get_instance();
	$ci->load->library('pagination');

	// required config input
	// base_url
	// total_rows
	// per_page

	$default = array(
		'num_links'		=> 3,
		'uri_segment' 	=> 3
	);

	$config['attributes'] 			= array('class' => 'page-link');
	$default['full_tag_open']   = '<ul class="pagination">';
	$default['full_tag_close']  = '</ul>';
	$default['first_link']      = '<< First';
	$default['last_link']       = 'Last >>';
	$default['first_tag_open']  = '<li class="page-item">';
	$default['first_tag_close'] = '</li>';
	$default['prev_link']       = '< Previous';
	$default['prev_tag_open']   = '<li class="page-item prev">';
	$default['prev_tag_close']  = '</li>';
	$default['next_link']       = 'Next >';
	$default['next_tag_open']   = '<li class="page-item">';
	$default['next_tag_close']  = '</li>';
	$default['last_tag_open']   = '<li>';
	$default['last_tag_close']  = '</li>';
	$default['cur_tag_open']    = '<li class="page-item active"><a class="page-link" href="#">';
	$default['cur_tag_close']   = '</a></li>';
	$default['num_tag_open']    = '<li>';
	$default['num_tag_close']   = '</li>';

	$config = array_merge($default, $config);

	if (count($_GET) > 0) {
		$config['suffix'] 	 = '?' . http_build_query($_GET, '', "&");
		$config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
	}

	$ci->pagination->initialize($config);

	return $ci->pagination->create_links();

}



/**
* @param email options
* @param send now or on background
* @param smtp config to use
*
*/
function send_email($emailParams, $background = true, $smtpConfig = 'info')
{
	$ci =& get_instance();

	$ci->load->config('email');
    $config = $ci->config->item('email');

    if (isset($config[$smtpConfig])) {

		$ci->load->library('email', $config[$smtpConfig]);	    
		$ci->email->set_newline("\r\n");

		foreach ($emailParams as $method => $params)
		{
			if (!is_array($params)) {
				$params = (array) $params;
			}
			call_user_func_array(array($ci->email, $method), $params);
		}

		// set config to use on background sending
		$ci->email->configKey = $smtpConfig;

		$ci->email->send(!$background);

		// var_dump($ci->email->print_debugger());
	}
}




/**
* is authenticated?
* if not
* redirect to loggin page
* if ajax return 
*/
function check_authentication()
{
	$ci =& get_instance();
	if (!$ci->authentication->is_loggedin()) {
        if (!$ci->input->is_ajax_request()) {
		   	redirect('account/signin');
		} else {
			header("HTTP/1.1 401 Unauthorized");
			header('Content-Type: application/json');
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Unauthorized access.'
			));
			exit;
		}
    }
}

/**
* authtentication is_loggedin shortcut
*/
function isGuest()
{
	$ci =& get_instance();
	if (!$ci->authentication->is_loggedin()) {
		return true;
	} else {
		return false;
	}
}


function photo_filename($filename)
{
	// replace by default avatar if not exists
	$filepath = PUBLIC_DIRECTORY . 'assets/profile/' . $filename;
	return (!empty($filename) && file_exists($filepath) ? $filename . '?' . filemtime($filepath) : 'default.jpg');
}

function logo_filename($filename)
{
	// replace by default logo if not exists
	// recache using last file change
	$filepath = PUBLIC_DIRECTORY . 'assets/logo/' . $filename;
	return (!empty($filename) && file_exists($filepath) ? $filename . '?' . filemtime($filepath)  : 'default.png');
}

function upload_filename($filename)
{
	$filepath = PUBLIC_DIRECTORY . 'assets/uploads/' . $filename;
	return (!empty($filename) && file_exists($filepath) ? $filename . '?' . filemtime($filepath)  : 'default.png');
}

function product_filename($filename)
{
	$filepath = PUBLIC_DIRECTORY . 'assets/products/' . $filename;
	return (!empty($filename) && file_exists($filepath) ? $filename . '?' . filemtime($filepath)  : 'default.png');
}


/**
* current user
*/
function current_user($view = 'id')
{
	$ci =& get_instance();
	$id = $ci->session->userdata('identifier');

	if ($view == 'full') {
		return user_account_details($id);
	} else if ($view == 'name') {
		$data = user_account_details($id);
		return user_full_name($data, 0);
	}

	return $id;
}

/**
* get user raw data
*/
function get_user($userID)
{
	$ci =& get_instance();
	$user = $ci->appdb->getRowObject('UserAccountInformation', $userID, 'id');
	if ($user) {
		return $user;
	}

	return false;
}


function user_account_details($id = false, $field = 'id')
{
	$ci =& get_instance();

	if ($id === false) {
		$id = $ci->session->userdata('identifier');
	}

	$user = lookup_row('Users', $id, $field);

	if ($user) {
		$user->fullname = user_full_name($user, 0);
	}

	return $user;

}



/**
* generate full name
* return string
*/
function user_full_name($data, $m = 1)
{	
	$data = (array) $data;
	$middle = ' '; // default
	if (!empty($data['Middlename'])) {
		if ($m == 1) {
			$middle = ' ' . $data['Middlename'] . ' ';
		} else if ($m == 2) {
			$middle = ' ' . substr($data['Middlename'], 0, 1) . ' ';
		}
	}
	return ucwords($data['Firstname'] . $middle . $data['Lastname']);
}

// wallet latest balance
function get_latest_wallet_balance($userID = false)
{
	if (!$userID) {
		$userID = current_user();
	}

	$ci =& get_instance();

	$sql = "SELECT EndingBalance
            FROM WalletTransactions
            WHERE AccountID = {$userID}
            ORDER BY id DESC
            LIMIT 1";

    $latest = $ci->db->query($sql)->row();
    if ($latest) {
    	return $latest->EndingBalance;
    } else {
    	return 0;
    }
}


function get_transactions($userID)
{	
	if (!$userID) {
		$userID = current_user();
	}

	$ci =& get_instance();

	$transactions = $ci->appdb->getRecords('WalletTransactions', array('AccountID' => current_user()), 'Date DESC');
  $summary      = array(
    'balance'   => 0,
    'debit'     => 0,
    'credit'    => 0,
    'transactions'  => 0
  );

  foreach ($transactions as &$i) {
    if ($i['Type'] == 'Credit') {
      $i['credit'] = $i['Amount'];
      $i['debit']  = false;
      $summary['credit'] += $i['Amount'];
      $summary['balance'] += $i['Amount'];
      $summary['transactions']++;
    } else {
      $i['debit']  = $i['Amount'];
      $i['credit'] = false;
      $summary['debit'] += $i['Amount'];
      $summary['balance'] -= $i['Amount'];
      $summary['transactions']++;
    }
  }

  $data['transactions'] = $transactions;
  $data['summary']      = $summary;

  return array(
  	'transactions'	=> $transactions,
  	'summary'				=> $summary
  );
}