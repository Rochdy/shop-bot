<?php

namespace AppBundle\Constant;

final class BotMessageType
{
    const WELCOME = 1;
    const WELCOME_INCLUDE = 'start';
    const WELCOME_TEXT = 'Welcome To Shop Bot!';

    const SHOW_PRODUCTS = 2;
    const SHOW_PRODUCTS_INCLUDE = 'products';
    const SHOW_PRODUCTS_TEXT = 'See All Products';
    const SHOW_PRODUCTS_PAYLOAD = 'show_products';

    const SHOW_CART = 3;
    const SHOW_CART_INCLUDE = 'cart';
    const SHOW_CART_TEXT = 'Show My Cart';
    const SHOW_CART_PAYLOAD= 'show_cart';

    const ADD_TO_CART = 4;
    const ADD_TO_CART_INCLUDE = 'add';
    const ADD_TO_CART_TEXT = 'Add To Cart';
    const ADD_TO_CART_PAYLOAD = 'Add|{customer}|{product}';

    const REMOVE_FROM_CART = 5;
    const REMOVE_FROM_CART_INCLUDE = 'remove';
    const REMOVE_FROM_CART_TEXT = 'Remove From Cart';
    const REMOVE_FROM_CART_PAYLOAD = 'remove|{item}';

    public static function convertPayload($payloadText, $params = [])
    {
        $placeholders = array_keys($params);
        foreach ($placeholders as &$placeholder) {
            $placeholder = "{{$placeholder}}";
        }
        return str_replace($placeholders, array_values($params), $payloadText);
    }


}