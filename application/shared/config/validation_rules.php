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
	array('ConfirmPassword', 'Password confirmation', 'required|matches[Password]')


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




// ADMIN 

$config['save_product_category'] = array(
	array('Name', 'Category name', 'trim|required'),
);

$config['save_product_sub_category'] = array(
	array('Name', 'Sub category name', 'trim|required'),
	array('CategoryID', 'Parent category', 'required'),
);