
<?php

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertToRomawi')) {
    function convertToRomawi($number)
    {
        switch ($number) {
            case ('01'):
                $output = 'I';
                break;
            case ('02'):
                $output = 'II';
                break;
            case ('03'):
                $output = 'III';
                break;
            case ('04'):
                $output = 'IV';
                break;
            case ('05'):
                $output = 'V';
                break;
            case ('06'):
                $output = 'VI';
                break;
            case ('07'):
                $output = 'VII';
                break;
            case ('08'):
                $output = 'VIII';
                break;
            case ('09'):
                $output = 'IX';
                break;
            case ('10'):
                $output = 'X';
                break;
            case ('11'):
                $output = 'XI';
                break;
            case ('12'):
                $output = 'XII';
                break;
        }
        return $output;
    }
}
if (!function_exists('convertToBulan')) {
    function convertToBulan($number)
    {
        switch ($number) {
            case ('January'):
                $output = 'Januari';
                break;
            case ('February'):
                $output = 'Februari';
                break;
            case ('March'):
                $output = 'Maret';
                break;
            case ('April'):
                $output = 'April';
                break;
            case ('May'):
                $output = 'Mei';
                break;
            case ('June'):
                $output = 'Juni';
                break;
            case ('July'):
                $output = 'Juli';
                break;
            case ('August'):
                $output = 'Agustus';
                break;
            case ('September'):
                $output = 'September';
                break;
            case ('October'):
                $output = 'Oktober';
                break;
            case ('November'):
                $output = 'November';
                break;
            case ('December'):
                $output = 'Desember';
                break;
        }
        return $output;
    }
}
