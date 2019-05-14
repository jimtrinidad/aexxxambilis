<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounts extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function index()
    {
        // redirect();
        $viewData = array(
            'pageTitle'         => 'Accounts',
            'pageDescription'   => '',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'accounts'
            )
        );

        $page_limit = 50;
        $page_start = (int) $this->uri->segment(3);

        $where = array(
            'deletedAt' => NULL,
        );
        $order = 'Status, Firstname';

        // SET SEARCH FILTER
        $filters = array(
            'search_mid',
            'search_name',
            'search_account_status',
            'search_account_level',
        );
        foreach ($filters as $filter) {

            $$filter = get_post($filter);

            if ($filter == 'search_name' && $$filter != false) {
                $where['CONCAT(Firstname, " ", Lastname) LIKE ']  = "%{$search_name}%";
            } else if ($filter == 'search_mid' && $$filter != false) {
                $where['PublicID']  = $search_mid;
            } else if ($filter == 'search_account_level' && $$filter != false) {
                $where['AccountLevel']  = $search_account_level;
            } else if ($filter == 'search_account_status' && $$filter != '') {
                $where['Status']  = $search_account_status;
            }


            // search params
            $viewData[$filter] = $$filter;

        }

        $paginatationData = $this->appdb->getPaginationData('Users', $page_limit, $page_start, $where, $order);

        // prepare account data
        $accounts = array();
        foreach ($paginatationData['data'] as $item) {
            $item = (array) $item;
            unset($item['Password']);
            unset($item['deletedAt']);
            $item['referrer_data'] = $this->appdb->getRowObject('Users', $item['Referrer']);
            $accounts[] = $item;
        }

        // echo '<pre>';print_r($accounts);exit;

        $paginationConfig = array(
            'base_url'      => base_url('accounts/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        $viewData['accounts']   = $accounts;
        $viewData['pagination'] = paginate($paginationConfig);

        view('pages/accounts/index', $viewData, 'templates/main');
    }

    public function update_status($code)
    {
        if ($code) {
            $user = $this->appdb->getRowObject('Users', $code, 'RegistrationID');
            if ($user) {
                $status = get_post('status');
                if ($status == 'true' || $status == 'false') {
                    $updateData = array(
                        'id'          => $user->id,
                        'Status'      => ($status == 'true' ? 1 : 2),
                        'LastUpdate'  => date('Y-m-d H:i:s')
                    );
                    if ($this->appdb->saveData('Users', $updateData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Account status has been updated.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Updating account status failed.'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid account status.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid account code.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid account code.'
            );
        }

        response_json($return_data);
    }

}
