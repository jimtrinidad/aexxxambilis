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


$config['delivery_methods'] = array(
	1 => 'Personal Delivery',
	2 => 'Ambilis Logistics'
);