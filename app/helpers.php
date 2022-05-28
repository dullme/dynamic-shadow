<?php

function getCurrencyIcon($currency){
    switch ($currency){
        case 'USD': $currency = '$ ';break;
        case 'CNY': $currency = '¥ ';break;
        default : $currency = ' ';
    }

    return $currency;
}
