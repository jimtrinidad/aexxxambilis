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
    1 => 'Discount',
    2 => 'Referral Points',
    3 => 'Shared Reward',
    4 => 'Cashback'
);