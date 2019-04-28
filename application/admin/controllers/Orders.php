<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function index()
    {
        $viewData = array(
            'pageTitle'         => 'Orders',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'general'
            )
        );

        $records = $this->appdb->getRecords('Orders', array(), 'Status, id DESC');
        foreach ($records as &$r) {
            $r['user'] = $this->appdb->getRowObject('Users', $r['OrderBy']);
        }
        $viewData['records']   = $records;

        // print_data($viewData);

        view('pages/orders/index', $viewData, 'templates/main');
    }

    // view invoice on modal
    public function invoice($code = '')
    {
        $orderData = $this->appdb->getRowObject('Orders', $code, 'Code');

        if ($orderData) {

            $viewData       = array();
            $userData       = $this->appdb->getRowObject('Users', $orderData->OrderBy);
            $addressData    = $this->appdb->getRowObject('UserAddress', $orderData->AddressID);
            $orderItems     = $this->appdb->getRecords('OrderItems', array('OrderID' => $orderData->id));

            $addressData->data = lookup_address($addressData);

            $orderData->Distribution = json_decode($orderData->Distribution);

            $viewData['orderData']  = $orderData;
            $viewData['userData']   = $userData;
            $viewData['address']    = $addressData;
            $viewData['items']      = $orderItems;


            echo view('pages/orders/invoice', $viewData, null, true);

        }

        return '';

    }

}
