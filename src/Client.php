<?php

namespace Sukvojte\Coinbase;

class Client
{

    private Api $api;

    public const ORDER_OPEN = 'OPEN';
    public const ORDER_CANCELLED = 'CANCELLED';
    public const ORDER_EXPIRED = 'EXPIRED';

    public static function load($client_id, $client_key, $use_sandbox = false): Client
    {
        $instance = new Client();
        $instance->api = new Api($client_id, $client_key, $use_sandbox);
        return $instance;
    }

    public function createOrder()
    {
        //https://api.coinbase.com/api/v3/brokerage/orders

    }

    /**
     * https://api.coinbase.com/api/v3/brokerage/orders/historical/batch
     */
    public function getOrders($order_status = [])
    {
        $parameters = [];

        $order_status = (array)$order_status;

        if (count($order_status) > 0) {
            $parameters['order_status'] = join(',', $order_status);
        }

        return $this->api->get('brokerage/orders/historical/batch', $parameters);
    }
}
