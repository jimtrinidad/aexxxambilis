<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ECPay Class
 *
 * ECPay SOAP API Wrapper.
 *
 * @package        ECPay
 * @version        1.0
 * @author         Jim Trinidad <jimtrinidad002@gmail.com>
 *
 */
class Ecpay
{

    /**
     * CodeIgniter
     *
     * @access    private
     */
    private $ci;

    /**
     * Config items
     *
     * @access    private
     */
    private $branch_id;
    private $account_id;
    private $username;
    private $password;

    private $default_host = 'ecpay.ph';

    /**
     * Constructor
     */
    public function __construct()
    {

        // Assign CodeIgniter object to $this->ci
        $this->ci = &get_instance();

        // Load config
        $this->ci->config->load('ecpay');
        $authentication_config = $this->ci->config->item('authentication');

        // Set config items
        $this->branch_id    = $authentication_config['branch_id'];
        $this->account_id   = $authentication_config['account_id'];
        $this->username     = $authentication_config['username'];
        $this->password     = $authentication_config['password'];

    }

    /**
     * get billers
     */
    public function get_billers()
    {
        $params = array(
            'post_url'  => 'https://myecpay.ph/UAT/billspayment/service1.asmx',
            'action'    => 'http://tempuri.org/ECPNBillsPayment/Service1/GetBillerList'
        );
        $body   = '<GetBillerList xmlns="http://tempuri.org/ECPNBillsPayment/Service1">' .
                    $this->default_body_params() .
                  '</GetBillerList>';

        $response = $this->request($params, $body);

        if (isset($response->GetBillerListResponse)) {
            $items    = json_decode(json_encode($response->GetBillerListResponse->GetBillerListResult), true);

            if ($items['BStruct'][0]['BillerTag'] != 'ERROR') {

                $billers = array();
                foreach ($items['BStruct'] as $item) {
                    $billers[] = $item;
                }

                return $billers;

            } else {
                logger('[get_billers] : ' . $items['BStruct'][0]['Description']);
            }

        } else {
            logger('[get_billers] : Cannot connect to host.');
        }

        return false;
    }


    /**
    * get encash services
    */
    public function get_encash_providers()
    {
        $params = array(
            'post_url'  => 'https://myecpay.ph/uat/wsecash/service1.asmx',
            'action'    => 'http://ecpay.ph/WKECash/GetServices'
        );
        $body   = '<GetServices xmlns="http://ecpay.ph/WKECash">' .
                    $this->default_body_params() .
                  '</GetServices>';

        $response = $this->request($params, $body);

        if (isset($response->GetServicesResponse)) {
            $items    = json_decode(json_encode($response->GetServicesResponse->GetServicesResult), true);

            if (isset($items[0])) {
                $data = json_decode($items[0], true);
                if (isset($data['Description'])) {
                    logger('[get_encash_providers] : ' . $data['Description']);
                } else {
                    return $data;
                }
            } else {
                logger('[get_encash_providers] : Invalid response.');
            }

        } else {
            logger('[get_encash_providers] : Cannot connect to host.');
        }

        // print_data($response);
    }








    private function default_body_params()
    {
        return '<AccountID>' . $this->account_id . '</AccountID>
                <Username>' . $this->username . '</Username>
                <Password>' . $this->password . '</Password>';
    }

    /**
    *
    * @param $params[post_url, host, action]
    */
    private function request($params, $body)
    {

        if (!isset($params['post_url']) || !isset($params['action'])) {
            die('Invalid soap request params');
        }

        $soapUrl      = $params['post_url'];
        $soapAction   = $params['action'];
        $soapHost     = (isset($params['host']) ? $params['host'] : 'ecpay.ph');

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                            xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                                            xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                                                <soap:Body>
                                                    ' . $body . '
                                                </soap:Body>
                                            </soap:Envelope>';

        $headers = array(
            "Host: " . $soapHost,
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: " . $soapAction, 
            "Content-length: ". strlen($xml_post_string),
        );

        // print_r($headers);
        // echo $xml_post_string . PHP_EOL;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch); 
        curl_close($ch);

        // echo $response . PHP_EOL;

        // // converting
        $response1 = str_replace("<soap:Body>","",$response);
        $response2 = str_replace("</soap:Body>","",$response1);

        // // convertingc to XML
        return simplexml_load_string($response2);
    }

}

/* End of file ECPay.php */
/* Location: ./application/libraries/ECPay.php */
