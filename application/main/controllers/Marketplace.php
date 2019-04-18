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

        view('main/marketplace/index', $viewData, 'templates/main');
    }

    public function view($code = false)
    {
        $product = $this->appdb->getRowObject('StoreItems', $code, 'Code');
        if ($product) {

            $viewData = array(
                'pageTitle'     => $product->Name,
                'pageSubTitle'  => 'Ambilis Mag Shopping!',
                'accountInfo'   => user_account_details(),
                'jsModules'         => array(
                    'marketplace',
                ),
            );

            $viewData['productData'] = $product;
            $viewData['distribution'] = profit_distribution($product->Price, $product->CommissionValue, $product->CommissionType);

            view('main/marketplace/view', $viewData, 'templates/main');

        } else {
            redirect(site_url('marketplace'));
        }
    }

    public function cart()
    {

        $viewData = array(
            'pageTitle'     => 'Shopping Cart',
            'pageSubTitle'  => 'Ambilis Mag Shopping!',
            'accountInfo'   => user_account_details(),
            'jsModules'         => array(
                'marketplace',
            ),
        );

        $cart_items = $this->cart->contents();
        foreach ($cart_items as &$item) {
            $product = $this->appdb->getRowObject('StoreItems', $item['id']);
            $seller  = (array) $this->appdb->getRowObject('StoreDetails', $product->StoreID);
            $item['seller'] = $seller['Name'];
        }

        $viewData['items'] = $cart_items;

        // print_data($viewData['items']);

        view('main/marketplace/cart', $viewData, 'templates/main');
    }

    public function add_to_cart()
    { 
        $product = $this->appdb->getRowObject('StoreItems', get_post('code'), 'Code');
        if ($product) {
            $data = array(
                'id'    => $product->id, 
                'name'  => $product->Name, 
                'price' => $product->Price, 
                'qty'   => get_post('quantity'), 
                'img'   => product_filename($product->Image)
            );
            if ($this->cart->insert($data)) {

                $seller  = (array) $this->appdb->getRowObject('StoreDetails', $product->StoreID);
                $p = array(
                    'Image' => product_filename($product->Image),
                    'Name'  => $product->Name,
                    'Price' => peso($product->Price),
                    'Seller'=> $seller['Name']
                );

                $return_data = array(
                    'status'     => true,
                    'message'    => 'Item has been added to cart',
                    'item_count' => $this->cart->total_items(),
                    'data'       => $p
                );

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Failed to add on cart'
                );
            }
        } else {
            $return_data = array(
                'status'    => true,
                'message'   => 'Invalid product'
            );
        }

        response_json($return_data);
    }

    public function update_cart_item()
    {

        $data = array(
            'rowid' => get_post('rowid'),
            'qty'   => get_post('quantity')
        );

        $this->cart->update($data);

        $item = $this->cart->get_item(get_post('rowid'));
        $return_data = array(
                'status'    => true,
                'message'   => 'Invalid product',
                'item_count'=> $this->cart->total_items(),
                'subtotal'  => peso($item['subtotal']),
                'total'     => peso($this->cart->total()),
            );

        response_json($return_data);
    }

    public function remove_cart_item()
    {
        if ($this->cart->remove(get_post('rowid'))) {
            $return_data = array(
                    'status'    => true,
                    'message'   => 'Invalid product',
                    'item_count'=> $this->cart->total_items(),
                    'total'     => peso($this->cart->total()),
                );
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Cannot remove item on cart.'
            );
        }

        response_json($return_data);
    }

}
