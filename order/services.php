<?php
// price in rupee

$allServices = array(
    9220 => array(
        "name" => "Instagram Views - ServiceID 9220",
        "apiname" => "peakerr",
        "priceUponQuantity" => array(
            100 => 1.02,
            200 => 2.04,
            500 => 5.1,
            1000 => 10.2,
            2000 => 20.4,
            5000 => 51,
        ),
    ),
    6587 => array(
        "name" => "Instagram Reel Views - ServiceID 6587",
        "apiname" => "peakerr",
        "priceUponQuantity" => array(
            100 => 1.02,
            200 => 2.04,
            500 => 5.1,
            1000 => 10.2,
            2000 => 20.4,
            5000 => 51,
        ),
    ),

);

// providers
$smart_api_providers = array(
    "peakerr" => array("api_url" => "https://peakerr.com/api/v2", "api_key" => "2c5f3767f19ede9ab05f5c084c9f65d8"),
    "smmworldpanel" => array("api_url" => "https://smmworldpanel.com/api/v2", "api_key" => "e387113e3788c63fd3b783a76aa067d8"),
);










// api

class SMARTApi
{
    public $api_url;
    public $api_key;
    public $api_details;
    public function __construct()
    {
        $this->api_details = func_get_args();
        if (!empty($this->api_details)) {
            $this->api_url = $this->api_details[0]['api_url'];
            $this->api_key = $this->api_details[0]['api_key'];
        }
    }

    public function order($data)
    { // add order
        $post = array_merge(array('key' => $this->api_key, 'action' => 'add'), $data);
        return json_decode($this->connect($post));
    }

    public function status($order_id)
    { // get order status
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'status',
            'order' => $order_id,
        )));
    }

    public function multiStatus($order_ids)
    { // get order status
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'status',
            'orders' => implode(",", (array) $order_ids),
        )));
    }

    public function services()
    { // get services
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'services',
        )));
    }

    public function balance()
    { // get balance
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'balance',
        )));
    }

    private function connect($post)
    {
        $_post = array();
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name . '=' . urlencode($value);
            }
        }

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (is_array($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return $result;
    }
}
