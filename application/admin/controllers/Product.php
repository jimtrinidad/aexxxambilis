<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function index()
    {
        redirect();
    }

    public function categories()
    {
        $viewData = array(
            'pageTitle'         => 'Product Categories',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'products'
            )
        );

        $where   = array();
        $results = $this->appdb->getRecords('ProductCategories', $where, 'Name');
        $categories = array();
        foreach ($results as $c) {
            $sub = $this->appdb->getRecords('ProductSubCategories', array('CategoryID' => $c['id']), 'Name');
            $c['subCategories'] = array();
            foreach ($sub as $s) {
                $c['subCategories'][$s['id']] = $s;
            }
            $categories[$c['id']] = $c;
        }
        $viewData['categories'] = $categories;

        view('pages/product/categories', $viewData, 'templates/main');
    }


    public function save_category()
    {
        if (validate('save_product_category') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $randomLogoName = md5(microsecID());

            // validate file upload
            $this->load->library('upload', array(
                'upload_path'   => UPLOADS_DIRECTORY,
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
                    'Name'              => get_post('Name'),
                    'LastUpdate'        => date('Y-m-d H:i:s')
                );

                if (!empty($_FILES['Logo']['name'])) {
                    $saveData['Image'] = $uploadData['file_name'];
                }

                $itemData = $this->appdb->getRowObject('ProductCategories', get_post('Code'), 'Code');
                if ($itemData) {
                    $saveData['id'] = $itemData->id;
                } else {
                    $saveData['Code']        = microsecID();
                }

                if (($ID = $this->appdb->saveData('ProductCategories', $saveData))) {

                    // delete old logo if edited
                    if ($itemData !== false && isset($saveData['Image'])) {
                        @unlink(UPLOADS_DIRECTORY . $itemData->Image);
                    }

                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Product category has been saved.',
                        'id'        => $ID
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving product category failed. Please try again later.'
                    );
                }

            }
        }

        response_json($return_data);
    }

    public function delete_category($code = null)
    {
        $itemData = $this->appdb->getRowObject('ProductCategories', $code, 'Code');
        if ($itemData) {

            $this->db->trans_begin();
            $todelete      = array();
            $subCategories = $this->appdb->getRecords('ProductSubCategories', array('CategoryID' => $itemData->id));
            foreach ($subCategories as $s) {
                $this->appdb->deleteData('ProductSubCategories', $s['id']);
                $todelete[] = UPLOADS_DIRECTORY . $s['Image'];
            }
            $this->appdb->deleteData('ProductCategories', $itemData->id);
            $todelete[] = UPLOADS_DIRECTORY . $itemData->Image;

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();
                response_json(array(
                    'status'    => false,
                    'message'   => 'Deleting category failed.'
                ));

            } else {

                $this->db->trans_commit();
                foreach ($todelete as $file) {
                    @unlink($file);
                }
                
                response_json(array(
                    'status'    => true,
                    'message'   => 'Category has been deleted.'
                ));
            }
       
        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'Invalid category.'
            ));
        }

    }


    public function save_sub_category()
    {
        if (validate('save_product_sub_category') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $randomLogoName = md5(microsecID());

            // validate file upload
            $this->load->library('upload', array(
                'upload_path'   => UPLOADS_DIRECTORY,
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
                    'message'   => 'Uploading image failed.',
                    'fields'    => array('Logo' => $this->upload->display_errors('',''))
                );
            } else {

                // do save
                $uploadData     = $this->upload->data();

                $saveData = array(
                    'Name'              => get_post('Name'),
                    'LastUpdate'        => date('Y-m-d H:i:s')
                );

                if (!empty($_FILES['Logo']['name'])) {
                    $saveData['Image'] = $uploadData['file_name'];
                }

                $itemData = $this->appdb->getRowObject('ProductSubCategories', get_post('Code'), 'Code');
                if ($itemData) {
                    $saveData['id'] = $itemData->id;
                } else {
                    $saveData['CategoryID']  = get_post('CategoryID');
                    $saveData['Code']        = microsecID();
                }

                if (($ID = $this->appdb->saveData('ProductSubCategories', $saveData))) {

                    // delete old logo if edited
                    if ($itemData !== false && isset($saveData['Image'])) {
                        @unlink(UPLOADS_DIRECTORY . $itemData->Image);
                    }

                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Product sub category has been saved.',
                        'id'        => $ID
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving product sub category failed. Please try again later.'
                    );
                }

            }
        }

        response_json($return_data);
    }

    public function delete_sub_category($code = null)
    {
        $itemData = $this->appdb->getRowObject('ProductSubCategories', $code, 'Code');
        if ($itemData) {

            if ($this->appdb->deleteData('ProductSubCategories', $itemData->id)) {

                @unlink(UPLOADS_DIRECTORY . $itemData->Image);
                
                response_json(array(
                    'status'    => true,
                    'message'   => 'Sub category has been deleted.'
                ));

            } else {
                response_json(array(
                    'status'    => false,
                    'message'   => 'Deleting sub category failed.'
                ));
            }
        } else {
            response_json(array(
                'status'    => false,
                'message'   => 'Invalid category.'
            ));
        }

    }


    public function manufacturers()
    {
        $viewData = array(
            'pageTitle'         => 'Manfacturers',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'products'
            )
        );

        $where   = array();
        $results = $this->db->query('SELECT DISTINCT(UPPER(TRIM(Manufacturer))) AS `Name`, PartnerImage
                                    FROM StoreItems 
                                    WHERE Manufacturer IS NOT NULL AND Manufacturer != ""
                                    ORDER BY Name')->result_array();
        $viewData['records'] = $results;

        view('pages/product/manufacturers', $viewData, 'templates/main');
    }

}
