<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Store extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function index($code = null)
    {
        $viewData = array(
            'pageTitle'     => 'Store',
            'accountInfo'   => user_account_details(),
            'jsModules'         => array(
                'store'
            ),
        );

        $storeData  = $this->appdb->getRowObject('StoreDetails', current_user(), 'OwnerID');
        $storeItems = array();

        if ($storeData) {
            $storeItems = $this->appdb->getRecords('StoreItems', array('StoreID' => $storeData->id));
        }

        $viewData['items']     = $storeItems;
        $viewData['StoreData'] = $storeData;

        // print_data($viewData);
        view('main/store/index', $viewData, 'templates/main');
    }

    public function update()
    {

        $updateData = array(
            'Name'      => get_post('Name'),
            'Address'   => get_post('Address'),
            'Contact'   => get_post('Contact'),
            'Email'     => get_post('Email'),
            'LastUpdate'=> datetime()
        );

        $StoreData = $this->appdb->getRowObject('StoreDetails', current_user(), 'OwnerID');
        if ($StoreData) {
            $updateData['id'] = $StoreData->id;
        } else {
            $updateData['Code']     = microsecID();
            $updateData['OwnerID']  = current_user();
        }

        if (($ID = $this->appdb->saveData('StoreDetails', $updateData))) {
            $return_data = array(
                'status'    => true,
                'message'   => 'Store profile has been updated.',
                'id'        => $ID
            );
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Saving profile failed. Please try again later.'
            );
        }

        response_json($return_data);
    }

    public function saveitem()
    {
        if (validate('save_store_item') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $Store = $this->appdb->getRowObject('StoreDetails', current_user(), 'OwnerID');
            if ($Store) {

                $randomLogoName = md5(microsecID());

                // validate file upload
                $this->load->library('upload', array(
                    'upload_path'   => PRODUCTS_DIRECTORY,
                    'allowed_types' => 'gif|jpg|png',
                    'max_size'      => '1000', // 1mb
                    'max_width'     => '1024',
                    'max_height'    => '768',
                    'overwrite'     => true,
                    'file_name'     => $randomLogoName
                ));

                if (!empty($_FILES['Logo']['name']) && $this->upload->do_upload('Logo') == false) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Uploading logo failed.',
                        'fields'    => array('Logo' => $this->upload->display_errors('',''))
                    );
                } else {

                    // do save
                    $uploadData     = $this->upload->data();

                    $saveData = array(
                        'Category'          => get_post('Category'),
                        'Name'              => get_post('Name'),
                        'Description'       => get_post('Description'),
                        'Measurement'       => get_post('Measurement'),
                        'Price'             => get_post('Price'),
                        'Commission'        => get_post('Commission'),
                        'DeliveryMethod'    => get_post('DeliveryMethod'),
                        'Warranty'          => get_post('Warranty'),
                        'LastUpdate'        => date('Y-m-d H:i:s')
                    );

                    if (!empty($_FILES['Logo']['name'])) {
                        $saveData['Image'] = $uploadData['file_name'];
                    }

                    $itemData = $this->appdb->getRowObject('StoreItems', get_post('Code'), 'Code');
                    if ($itemData) {
                        $saveData['id'] = $itemData->id;
                    } else {
                        $saveData['Code']        = microsecID();
                        $saveData['StoreID']     = $Store->id;
                    }

                    if (($ID = $this->appdb->saveData('StoreItems', $saveData))) {

                        // delete old logo if edited
                        if ($itemData !== false && isset($saveData['Image'])) {
                            @unlink(PRODUCTS_DIRECTORY . $itemData->Image);
                        }

                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Store product has been saved.',
                            'id'        => $ID
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving product failed. Please try again later.'
                        );
                    }

                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Store data not found.'
                );
            }
        }

        response_json($return_data);
    }

    public function deleteitem($id = null)
    {
        $itemData = $this->appdb->getRowObject('StoreItems', $id, 'Code');
        if ($itemData) {

            if ($this->appdb->deleteData('StoreItems', $itemData->id)) {

                @unlink(PRODUCTS_DIRECTORY . $itemData->Image);
                
                response_json(array(
                    'status'    => true,
                    'message'   => 'Item has been deleted.'
                ));

            } else {
                response_json(array(
                    'status'    => false,
                    'message'   => 'Deleting item failed.'
                ));
            }
        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'Invalid item.'
            ));
        }

    }

}
