<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marketplace extends CI_Controller
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
            'pageTitle'     => 'Marketplace',
            'pageSubTitle'  => 'Ambilis Mag Shopping!',
            'accountInfo'   => user_account_details(),
            'jsModules'         => array(
                'marketplace',
            ),
        );

        $page_limit = 20;
        $page_start = (int) $this->uri->segment(3);

        $order = 'LastUpdate Desc';
        $where = array();

        // SET SEARCH FILTER
        if (get_post('search')) {
            $where['CONCAT(Name, " ", Description) LIKE ']  = '%' . get_post('search') . '%';
        }

        if (get_post('c')) {
            $where['Category'] = get_post('c');
        }

        $paginatationData = $this->appdb->getPaginationData('StoreItems', $page_limit, $page_start, $where, $order);

        $products = array();
        foreach ($paginatationData['data'] as $product) {
            $product = (array) $product;
            if (!isset($sellers[$product['StoreID']])) {
                $sellers[$product['StoreID']] = (array) $this->appdb->getRowObject('StoreDetails', $product['StoreID']);
            }
            $product['Image']  = product_filename($product['Image']);
            $product['seller'] = $sellers[$product['StoreID']];

            $products[] = $product;
        }

        $paginationConfig = array(
            'base_url'      => base_url('marketplace/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        $viewData['products']   = $products;
        $viewData['pagination'] = paginate($paginationConfig);

        $viewData['category']   = $this->appdb->getRowObject('ProductCategories', get_post('c'));
        // print_data($products, true);

        view('main/marketplace/index', $viewData, 'templates/main');
    }

}
