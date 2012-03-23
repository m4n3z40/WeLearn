<?php


function datetime_ptbr_to_en($datetime, $unix = false)
{
    $datetimeArray = explode(' ', $datetime);
    $parts = count($datetimeArray);

    if ( $parts == 2 ) {
        list($date, $time) = $datetimeArray;
    } elseif ( $parts == 1 ) {
        $date = $datetimeArray[0];
        $time = '23:59:59';
    } else {
        return $datetime;
    }

    $dateArray = explode('/', $date);

    list($d, $m, $y) = ( $dateArray[0] != $date ) && ( count($dateArray) == 3 ) ? $dateArray : explode('-', $date);

    $date = implode( '/', array($m, $d, $y) );

    $datetime = $date . ' ' . $time;

    if ($unix) {
        return strtotime( $datetime );
    }

    return $datetime;
}