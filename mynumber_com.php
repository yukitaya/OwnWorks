<?php
function verifyCooporateNumber($mynum) {
        // 必ず13桁
        if (strlen($mynum) !== 13 || strspn($mynum, '1234567890') !== 13 ) {
                return FALSE;
        }
        $sum = 0;
        // 最初の一文字がチェックディジット。小さい桁から計算。
        for ($i=1; $i <= 12; $i++) {
                $m = substr($mynum, 12-$i, 1);
                $n = ($i % 2) ? 1 : 2;
                $sum += $m * $n;
        }
        $mod = 9 - $sum % 9;
        return (substr($mynum, 12, 1) == $mod);
}

$str = $_POST['number'];
$mynum = strval($str);
for ($i = 0; $i < 10; $i++) {
        var_dump($i.$mynum, verifyCooporateNumber($mynum.$i));
        echo "<br>";
}

