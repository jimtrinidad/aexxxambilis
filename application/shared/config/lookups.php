<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


$config['account_status'] = array(
    1 => 'Active',
    2 => 'Blocked',
    3 => 'Deleted'
);

$config['account_level'] = array(
    1 => 'Regular',
    2 => 'Admin',
    3 => 'Super Admin'
);

$config['commission_type'] = array(
	1 => 'Transaction Fee',
	2 => 'Commission Percent'
);

$config['delivery_methods'] = array(
	3 => 'Not Applicable',
    1 => 'Company Delivery',
    2 => 'Ambilis Delivery',
);