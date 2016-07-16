<?php
function verifyPersonalMyNumber($mynum) {
        // 必ず数字12文字
        if (strlen($mynum) !== 12 || strspn($mynum, '1234567890') !== 12 ) {
                return FALSE;
        }
        $sum = 0;
        // 最後の一文字がチェックディジット。小さい桁から計算。
        for ($i=1; $i <= 11; $i++) {
                $m = substr($mynum, 11-$i, 1);
                $n = ($i <= 6) ? $i+1 : $i-5;
                $sum += $m * $n;
        }
        $mod = $sum % 11;
        // 必ず数字一桁
        if ($mod <= 1) {
                return (substr($mynum, 11, 1) == '0');
        } else {
                return ((int)substr($mynum, 11, 1) === 11 - $mod);
        }
}

$str = $_POST['number'];
$mynum = strval($str);
for ($i = 0; $i < 10; $i++) {
        var_dump($mynum.$i, verifyPersonalMyNumber($mynum.$i));
        echo "<br>";
}
