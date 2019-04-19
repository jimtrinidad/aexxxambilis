<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function invoice($code = false)
    {
        $viewData = array(
            'pageTitle'     => 'Orders',
            'pageSubTitle'  => 'ORDER SUMMARY',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
                'wallet'
            ),
        );

        $orderData = $this->appdb->getRowObject('Orders', $code, 'Code');

        if ($orderData) {

            $userData       = $this->appdb->getRowObject('Users', $orderData->OrderBy);
            $addressData    = $this->appdb->getRowObject('UserAddress', $orderData->AddressID);
            $orderItems     = $this->appdb->getRecords('OrderItems', array('OrderID' => $orderData->id));

            $addressData->data = lookup_address($addressData);

            $orderData->Distribution = json_decode($orderData->Distribution);

            $viewData['orderData']  = $orderData;
            $viewData['userData']   = $userData;
            $viewData['address']    = $addressData;
            $viewData['items']      = $orderItems;

            // print_data($viewData);

            view('main/order/invoice', $viewData, 'templates/main');
        } else {
            redirect();
        }

    }

}
