<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billers extends CI_Controller
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
            'pageTitle'         => 'Billers',
            'pageDescription'   => '',
            'content_header'    => false,
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'general'
            )
        );

        $viewData['billers'] = $this->appdb->getRecords('Billers', array('Status' => 1), 'BillerTag');

        view('pages/billers/index', $viewData, 'templates/main');

    }

    public function update_biller()
    {
        $billers = $this->ecpay->get_billers();
        if ($billers) {
            $savedBillers   = $this->appdb->getRecords('Billers');
            $active_billers = array();
            foreach ($billers as $biller) {
                $billerData = $this->appdb->getRowObject('Billers', md5($biller['BillerTag']), 'Code');

                $saveData = array(
                        'LastUpdate'    => datetime()
                    );

                $saveData = array_merge($biller, $saveData);

                if (!$billerData) {
                    $saveData['Code'] = md5($biller['BillerTag']);
                } else {
                    $saveData['id']   = $billerData->id;
                    $active_billers[] = $billerData->Code;
                }

                $this->appdb->saveData('Billers', $saveData);
            }

            // print_data($active_billers);
            // print_data($savedBillers);

            foreach ($savedBillers as $b) {
                if (in_array($b['Code'], $active_billers)) {
                    $status = 1;
                } else {
                    $status = 0;
                    logger('Biller -> ' . $b['BillerTag'] . ' is no longer active.');
                }

                $this->appdb->saveData('Billers', array(
                    'Status' => $status,
                    'id'     => $b['id']
                ));
            }
        }
    }

    public function save_biller_logo()
    {

        $billerData = $this->appdb->getRowObject('Billers', get_post('Code'), 'Code');
        if ($billerData) {
            $randomLogoName = md5(microsecID());

            // validate file upload
            $this->load->library('upload', array(
                'upload_path'   => LOGO_DIRECTORY,
                'allowed_types' => 'gif|jpg|png',
                // 'max_size'      => '1000', // 1mb
                // 'max_width'     => '1024',
                // 'max_height'    => '768',
                'overwrite'     => true,
                'file_name'     => $randomLogoName
            ));

            if (!empty($_FILES['Logo']['name']) && $this->upload->do_upload('Logo') == false) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Uploading image failed.',
                    'fields'    => array('Logo' => $this->upload->display_errors('',''))
                );
            } else {

                // do save
                $uploadData     = $this->upload->data();

                $saveData = array(
                    'id'            => $billerData->id,
                    'LastUpdate'    => date('Y-m-d H:i:s')
                );

                if (!empty($_FILES['Logo']['name'])) {
                    $saveData['Image'] = $uploadData['file_name'];
                }

                if (($ID = $this->appdb->saveData('Billers', $saveData))) {

                    // delete old logo if edited
                    if (isset($saveData['Image']) && !empty($billerData->Image)) {
                        @unlink(LOGO_DIRECTORY . $billerData->Image);
                    }

                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Biller logo has been saved.',
                        'id'        => $ID
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving logo failed. Please try again later.'
                    );
                }

            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid biller data.'
            );
        }

        response_json($return_data);
    }



    public function encash_services()
    {
        $viewData = array(
            'pageTitle'         => 'Encash Services',
            'pageDescription'   => '',
            'content_header'    => false,
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'general'
            )
        );

        $viewData['services'] = $this->appdb->getRecords('EncashServices', array('Status' => 1), 'Name');

        view('pages/billers/encash_services', $viewData, 'templates/main');

    }

    public function update_encash_services()
    {
        $data = $this->ecpay->get_encash_providers();
        if ($data) {
            $savedItems   = $this->appdb->getRecords('EncashServices');
            $active_data = array();
            foreach ($data as $item) {
                $itemData = $this->appdb->getRowObject('EncashServices', md5($item['Services']), 'Code');

                $saveData = array(
                        'LastUpdate'    => datetime()
                    );

                $saveData = array_merge($item, $saveData);

                if (!$itemData) {
                    $saveData['Name'] = $item['Services'];
                    $saveData['Code'] = md5($item['Services']);
                } else {
                    $saveData['id']   = $itemData->id;
                    $active_data[]    = $itemData->Code;
                }

                $this->appdb->saveData('EncashServices', $saveData);
            }

            foreach ($savedItems as $b) {
                print_data($b);
                if (in_array($b['Code'], $active_data)) {
                    $status = 1;
                } else {
                    $status = 0;
                    logger('Encash Services -> ' . $b['Name'] . ' is no longer active.');
                }

                $this->appdb->saveData('EncashServices', array(
                    'Status' => $status,
                    'id'     => $b['id']
                ));
            }
        }
    }


    public function save_encash_service()
    {

        $data = $this->appdb->getRowObject('EncashServices', get_post('Code'), 'Code');
        if ($data) {
            $randomLogoName = md5(microsecID());

            // validate file upload
            $this->load->library('upload', array(
                'upload_path'   => LOGO_DIRECTORY,
                'allowed_types' => 'gif|jpg|png',
                // 'max_size'      => '1000', // 1mb
                // 'max_width'     => '1024',
                // 'max_height'    => '768',
                'overwrite'     => true,
                'file_name'     => $randomLogoName
            ));

            if (!empty($_FILES['Logo']['name']) && $this->upload->do_upload('Logo') == false) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Uploading image failed.',
                    'fields'    => array('Logo' => $this->upload->display_errors('',''))
                );
            } else {

                // do save
                $uploadData     = $this->upload->data();

                $saveData = array(
                    'id'            => $data->id,
                    'Name'          => get_post('service_name'),
                    'Description'   => get_post('service_description'),
                    'LastUpdate'    => date('Y-m-d H:i:s')
                );

                if (!empty($_FILES['Logo']['name'])) {
                    $saveData['Image'] = $uploadData['file_name'];
                }

                if (($ID = $this->appdb->saveData('EncashServices', $saveData))) {

                    // delete old logo if edited
                    if (isset($saveData['Image']) && !empty($data->Image)) {
                        @unlink(LOGO_DIRECTORY . $data->Image);
                    }

                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Encash service has been saved.',
                        'id'        => $ID
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving failed. Please try again later.'
                    );
                }

            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid data.'
            );
        }

        response_json($return_data);
    }

}
