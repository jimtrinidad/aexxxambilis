<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* account registration form
*/

$config['account_registration'] = array(

	array('Firstname', 'First name', 'trim|required|regex_match[/^[a-zA-Z ]+$/]',
		array(
	        'regex_match' => '%s has invalid characters. Letters and space only.'
	    )),
	array('Lastname', 'Last name', 'trim|required|regex_match[/^[a-zA-Z. ]+$/]',
		array(
	        'regex_match' => '%s has invalid characters. Letters and space only.'
	    )),
	array('EmailAddress', 'Email address', 'trim|required|valid_email|min_length[5]|is_unique[Users.EmailAddress]',
	    array(
	        'is_unique' => 'Email address already exists.'
	    )),
	array('Mobile', 'Mobile number', 'trim|required|numeric|exact_length[10]|is_unique[Users.Mobile]',
		array(
	        'is_unique' => 'Mobile number already exists.'
	    )),
	array('Password', 'Password', 'required|min_length[8]|max_length[16]'),
	array('ConfirmPassword', 'Password confirmation', 'required|matches[Password]'),
	array('Referrer', 'Referrer', 'trim|required')
);


$config['account_update'] = array(

	array('account_firstname', 'First name', 'trim|required|regex_match[/^[a-zA-Z ]+$/]',
		array(
	        'regex_match' => '%s has invalid characters. Letters and space only.'
	    )),
	array('account_lastname', 'Last name', 'trim|required|regex_match[/^[a-zA-Z. ]+$/]',
		array(
	        'regex_match' => '%s has invalid characters. Letters and space only.'
	    )),
	array('account_email', 'Email address', 'trim|required|valid_email|min_length[5]|callback_unique_account_email',
	    array(
	        'unique_account_email' => 'Email address already exists.'
	    )),
	array('account_mobile', 'Mobile number', 'trim|required|numeric|exact_length[10]|callback_unique_account_mobile',
		array(
	        'unique_account_mobile' => 'Mobile number already exists.'
	    ))
);

$config['forgot_password'] = array(
	array('account_email', 'Email address', 'trim|required|valid_email|min_length[5]')
);


$config['user_address'] = array(
	array('AddressProvince', 'Province', 'trim|required'),
	array('AddressCity', 'City/Municipal', 'trim|required'),
	array('AddressBarangay', 'Barangay', 'trim|required'),
	array('AddressStreet', 'Home number & street', 'trim|required'),
);


$config['save_store_item'] = array(
	array('Name', 'Product name', 'trim|required'),
	array('Description', 'Product description', 'trim'),
	array('Price', 'Price', 'trim|required|numeric'),
	array('CommissionType', 'Commission type', 'trim|required|numeric'),
	array('CommissionValue', 'Commission value', 'trim|required|numeric'),
	array('MinimumQuantity', 'Minimum Quantity', 'trim|numeric'),
	array('Stock', 'Stock', 'trim|numeric'),
);


$config['add_deposit'] = array(
	array('Bank', 'Bank', 'trim|required'),
	array('Branch', 'Branch', 'trim|required'),
	array('ReferenceNo', 'Reference Number', 'trim|required'),
	array('Date', 'Deposit Date', 'trim|required'),
	array('Amount', 'Deposit Amount', 'trim|required|numeric'),
);

$config['encash_request'] = array(
	array('Amount', 'Amount', 'trim|required|numeric'),
);

$config['money_padala_request'] = array(
	array('ServiceType', 'Service Type', 'trim|required'),
	array('AccountNo', 'Account Number', 'trim|required'),
	array('Identifier', 'Identifier', 'trim|required'),
	array('Amount', 'Amount', 'trim|required|numeric'),
);

$config['add_payment'] = array(
	array('Biller', 'Biller', 'trim|required'),
	array('AccountNo', 'Account Number', 'trim|required'),
	array('Identifier', 'Identifier', 'trim|required'),
	array('Amount', 'Amount', 'trim|required|numeric'),
);

$config['send_eload'] = array(
	array('ServiceProvider', 'Service provider', 'trim|required'),
	array('Number', 'Mobile number', 'trim|required|numeric|min_length[11]|max_length[11]'),
	array('Amount', 'Load amount', 'trim|required|numeric'),
);


// ADMIN 

$config['save_product_category'] = array(
	array('Name', 'Category name', 'trim|required'),
);

$config['save_product_sub_category'] = array(
	array('Name', 'Sub category name', 'trim|required'),
	array('CategoryID', 'Parent category', 'required'),
);