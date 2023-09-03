<?php 

namespace app\components;

use Yii;

class Helper {

    public static function generateNomorTransaksi()
    {
        $dateTime = date('Y-m-d H:i:s');

        $y = date('y', strtotime($dateTime));
        $m = date('m', strtotime($dateTime));
        $m = intval($m) + 65;
        $m = chr($m);
        $d = date('d', strtotime($dateTime));

        $h = date('H', strtotime($dateTime));
        $h = intval($h) + 65;
        $h = chr($h);

        $i = date('i', strtotime($dateTime));
        $s = date('s', strtotime($dateTime));

        return "TRX$y$m$d$h$i$s";
    }

}


?>