<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('rupiah_display')) {

    function rupiah_display($var, $null = TRUE, $fractional = FALSE) {
        $rupiah = _format_number($var, $null, $fractional);
        return $rupiah != "" && $rupiah != "N/A" ? "Rp. " . $rupiah : $rupiah;
    }

}

if (!function_exists('_format_number')) {

    function _format_number($var, $null = TRUE, $fractional = FALSE) {
        if ($null === TRUE && $var == 0)
            return "N/A";

        if ($null === FALSE && ($var == 0 || $var == "")) {
            return "";
        }

        if ($fractional) {
            $var = sprintf('%.2f', $var);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1.$2', $var);
            if ($replaced != $var) {
                $var = $replaced;
            } else {
                break;
            }
        }
        return $var;
    }

}