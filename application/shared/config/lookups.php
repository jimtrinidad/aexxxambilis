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

$config['payment_method'] = array(
    1 => 'eWallet'
);

$config['order_status'] = array(
    1 => 'Processing',
    2 => 'For Delivery',
    3 => 'Delivered',
    4 => 'Completed',
    5 => 'Canceled'
);

$config['wallet_rewards_type'] = array(
    'discount'  => 'Discounts',
    'cashback'  => 'Cashback',
    'referrer'  => 'Referrer Points',
    'shared'    => 'Shared Rewards'
);


$config['mobile_service_provider'] = array(
    1 => 'Globe',
    2 => 'Smart',
    3 => 'Touch Mobile',
    4 => 'Sun Cellular',
    5 => 'ABS-CBN Mobile'
);

$config['biller_type'] = array(
    1   => 'Bills',
    2   => 'Ticketing'
);


$config['delivery_agent_status'] = array(
    0 => 'Pending Application',
    1 => 'Active',
    2 => 'Disable'
);

$config['delivery_agent_man_type'] = array(
    1   => 'Manpower Only',
    2   => 'With Motorcycle',
    3   => 'With Car',
    4   => 'With Van',
    5   => 'With Truck'
);


$config['store_status'] = array(
    0 => 'Pending Application',
    1 => 'Active',
    2 => 'Disable'
);


$config['weight_units'] = array(
    1 => 'Grams',
    2 => 'Kilograms'
);