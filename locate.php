<?php
if (gettype($parent->topcss) == "string" && $parent->topcss != "") {
$document->write('<link href="' + $parent->topcss + '.css" rel="STYLESHEET" media="screen" title="main" type="text\/css">');
}

function Amper($desc) {
    $r = $desc;
    $x = array_search("&", $r);
    if ($x != -1) {
        $r = substr($r, 0, $x);
    }
    return $r;
}

function getStandardTimezoneOffset() {
    $dte = new Date();
    $msec = $dte->getTime();
    $offset = -999999;
    for ($j = 0;
        $j < 4;++$j) {$dte->setTime($msec + $j * 7884000000);
        $offset = max($offset, $dte->getTimezoneOffset());
    }
    return $offset;
}

function intr($num) {
    $n = floor(abs($num));
    if ($num < 0) {
    $n = $n * -1;
    }return $n;
}
function NumFloat($num) {
    $temp = Math::parseFloat($num);
    if (!$temp > 0 || $temp < 0 || $temp == 0) {
    $temp = 0;
    }return $temp;
}
function rnd($num, $num2) {
    $num = round($num * pow(10, $num2)) / pow(10, $num2);
    return $num;
}
function sgn($num) {
    if ($num < 0) {
    return -1;
    }if ($num > 0) {
    return 1;
    }return 0;
}
function cbrt($x) {
    if ($x > 0) {
    return exp(log($x) / 3);
    }if ($x < 0) {
    return -cbrt(-$x);
    }return 0.0;
}
function dtor($num) {
    return $num / 57.29577951;
}
function rtod($num) {
    return $num * 57.29577951;
}
function dsin($num) {
    return sin(dtor($num));
}
function dcos($num) {
    return cos(dtor($num));
}
function dtan($num) {
    return tan(dtor($num));
}
function dasn($num) {
    $y = null;
    if ($num == 1) {
    $y = 1.570796327;
    } else {$y = atan($num / sqrt(-1 * $num * $num + 1));
    }return rtod($y);
}
function dacs($num) {
    $y = null;
    if ($num == 1) {
    $y = 0;
    } else {$y = 1.570796327 - atan($num / sqrt(-1 * $num * $num + 1));
    }return rtod($y);
}
function datan($num) {
    return rtod(atan($num));
}
function datan2($y, $x) {
    return rtod(atan2($y, $x));
}
function deg($a) {
    $a1 = null;
    $a2 = null;
    $a3 = null;
    $mm = null;
    $signe = null;
    $ss = null;
    $signe = 1;
    if ($a < 0) {
    $a = -1 * $a;
    $signe = -1;
    }$a1 = intr($a);
    $mm = $a - $a1 * 100;
    $mm = rnd($mm, 6);
    $a2 = intr($mm);
    $ss = $mm - $a2 * 100;
    $ss = rnd($ss, 6);
    $a3 = $ss;
    return $signe * $a1 + $a2 / 60 + $a3 / 3600;
}
function dms($a) {
    $a1 = null;
    $a2 = null;
    $a3 = null;
    $mm = null;
    $signe = null;
    $ss = null;
    $signe = 1;
    if ($a < 0) {
    $a = -1 * $a;
    $signe = -1;
    }$a1 = intr($a);
    $mm = $a - $a1 * 60;
    $mm = rnd($mm, 6);
    $a2 = intr($mm);
    $ss = $mm - $a2 * 60;
    $ss = rnd($ss, 6);
    $a3 = intr($ss);
    return $signe * $a1 + $a2 / 100 + $a3 / 10000;
}
function HMS($x, $sf) {
    $temp = dms(abs($x));
    $hr = intr($temp);
    $temp = $temp - $hr * 100;
    $temp = rnd($temp, 6);
    $mn = intr($temp);
    $temp = $temp - $mn * 100;
    $temp = rnd($temp, 6);
    if ($mn < 10) {
    $mn = "0" + $mn;
    }$sc = intr($temp);
    if ($sc < 10) {
    $sc = "0" + $sc;
    }$tmp = (sgn($x) == -1) ? "-" : "";
    $tmp2 = ($sf == true) ? "&#034;" : '"';
    return $tmp + $hr + "h " + $mn + "min " + $sc + "s ";
}
function DMS($x, $sf) {
    $temp = dms(abs($x));
    $hr = intr($temp);
    $temp = $temp - $hr * 100;
    $temp = rnd($temp, 6);
    $mn = intr($temp);
    $temp = $temp - $mn * 100;
    $temp = rnd($temp, 6);
    if ($mn < 10) {
    $mn = "0" + $mn;
    }$sc = intr($temp);
    if ($sc < 10) {
    $sc = "0" + $sc;
    }$tmp = (sgn($x) == -1) ? "-" : "";
    $tmp2 = ($sf == true) ? "&#034;" : '"';
    return $tmp + $hr + "°" + $mn + "'" + $sc + $tmp2;
}
function DMST($x) {
    $temp = dms(abs($x));
    $hr = intr($temp);
    $temp = $temp - $hr * 100;
    $temp = rnd($temp, 6);
    $mn = intr($temp);
    $temp = $temp - $mn * 100;
    $temp = rnd($temp, 6);
    if ($mn < 10) {
    $mn = "0" + $mn;
    }$sc = intr($temp);
    if ($sc < 10) {
    $sc = "0" + $sc;
    }$tmp = (sgn($x) == -1) ? "-" : "";
    return $tmp + $hr + ":" + $mn + ":" + $sc;
    }
    function log10($x) {
    return M_LOG10E * log($x);
}
function rev($x) {
return $x - floor($x / 360.0) * 360.0;
}
function julian($dte, $zone) {
    $ut = $dte->getHours() + $dte->getMinutes() / 100 + $dte->getSeconds() / 10000;
    $ut = deg($ut) + $zone;
    $y = NumFloat($dte->getYear()) + 1900;
    if ($y >= 3900) {
    $y = $y - 1900;
    }$m = NumFloat($dte->getMonth()) + 1;
    $d = NumFloat($dte->getDate()) + $ut / 24;
    if ($m <= 2) {
    $m = $m + 12;
    $y = $y - 1;
    }$A = intr($y / 100);
    $B = 2 - $A + intr($A / 4);
    if ($y < 1582) {
    $B = 0;
    }if ($y < 0) {
    $C = intr(365.25 * $y - .75);
    } else {$C = intr(365.25 * $y);
    }$D = intr(30.6001 * $m + 1);
    return rnd($B + $C + $D + $d + 1720994.5, 6);
}
function newDate($obj) {
    $x = 0;
    while (substr($obj, $x, 1) == " ") {$x++;
    }$r = substr($obj, $x);
    $y = parseFloat($r);
    $x = array_search(" ", $r);
    if ($x == -1) {
    return null;
    }$r = substr($r, $x + 1);
    $m = 0;
    $month = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    for ($i = 1;
    $i <= 12;$i++) {if (substr(strtolower($r), 0, 3) == strtolower($month[$i])) {
    $m = $i;
    break;}}$x = array_search(" ", $r);
    if ($x == -1) {
    return null;
    }$r = substr($r, $x + 1);
    $d = intr(parseFloat($r));
    $x = array_search(".", $r);
    if ($x == -1) {
    return null;
    }$r = "0" + substr($r, $x);
    $r = dms(parseFloat($r) * 24);
    $hr = intr($r);
    $r = $r - $hr * 100;
    $r = rnd($r, 6);
    $mn = intr($r);
    $r = $r - $mn * 100;
    $r = rnd($r, 6);
    $sc = intr($r);
    return new Date($y, $m - 1, $d, $hr, $mn, $sc);
}
function object($desc) {
    $prec = 3.82394E-5 * 365.2422 * $udoNe - 2000 - $d;
    $N = $udoN - $prec;
    $w = $udow;
    $i = $udoi;
    $e = $udoe;
    $q = $udoq;
    $k = 0.01720209895;
    $D = julian($udoT, $zone + $dst) - 2451543.5;
    if ($e < 0.9999 || $e > 1.0001) {
    $a = $q / 1 - $e;
    $M = 180 / M_PI * $d - $D * $k / sqrt($a * $a * $a);
    if (!isNaN($M)) {
    planet($desc);
    return;
    }}$a = 1.5 * $d - $D * $k / sqrt(2 * $q * $q * $q);
    $b = sqrt(1 + $a * $a);
    $w0 = cbrt($b + $a) - cbrt($b - $a);
    $v = 2 * datan($w0);
    $r = $q * 1 + $w0 * $w0;
    planet($desc, $v, $r);
    return;
}
function perturbations($desc) {
    $x = $px;
    $y = $py;
    $z = $pz;
    $lonecl = rev(datan2($y, $x));
    $latecl = datan2($z, sqrt($x * $x + $y * $y));
    $r = sqrt($x * $x + $y * $y + $z * $z);
    if ($desc == "Moon") {
    $Mm = rev($M);
    $Lm = rev($N + $w + $M);
    $D = $Lm - $Ls;
    $F = $Lm - $N;
    $lonecl = $lonecl - 1.274 * dsin($Mm - 2 * $D);
    $lonecl = $lonecl + 0.658 * dsin(2 * $D);
    $lonecl = $lonecl - 0.186 * dsin($Ms);
    $lonecl = $lonecl - 0.059 * dsin(2 * $Mm - 2 * $D);
    $lonecl = $lonecl - 0.057 * dsin($Mm - 2 * $D + $Ms);
    $lonecl = $lonecl + 0.053 * dsin($Mm + 2 * $D);
    $lonecl = $lonecl + 0.046 * dsin(2 * $D - $Ms);
    $lonecl = $lonecl + 0.041 * dsin($Mm - $Ms);
    $lonecl = $lonecl - 0.035 * dsin($D);
    $lonecl = $lonecl - 0.031 * dsin($Mm + $Ms);
    $lonecl = $lonecl - 0.015 * dsin(2 * $F - 2 * $D);
    $lonecl = $lonecl + 0.011 * dsin($Mm - 4 * $D);
    $latecl = $latecl - 0.173 * dsin($F - 2 * $D);
    $latecl = $latecl - 0.055 * dsin($Mm - $F - 2 * $D);
    $latecl = $latecl - 0.046 * dsin($Mm + $F - 2 * $D);
    $latecl = $latecl + 0.033 * dsin($F + 2 * $D);
    $latecl = $latecl + 0.017 * dsin(2 * $Mm + $F);
    $r = $r - 0.58 * dcos($Mm - 2 * $D);
    $r = $r - 0.46 * dcos(2 * $D);
    }if ($desc == "Jupiter") {
    $Mjp = rev(19.8950 + 0.0830853001 * $d);
    $Mst = rev(316.9670 + 0.0334442282 * $d);
    $Mun = rev(142.5905 + 0.011725806 * $d);
    $lonecl = $lonecl - 0.332 * dsin(2 * $Mjp - 5 * $Mst - 67.6);
    $lonecl = $lonecl - 0.056 * dsin(2 * $Mjp - 2 * $Mst + 21);
    $lonecl = $lonecl + 0.042 * dsin(3 * $Mjp - 5 * $Mst + 21);
    $lonecl = $lonecl - 0.036 * dsin($Mjp - 2 * $Mst);
    $lonecl = $lonecl + 0.022 * dcos($Mjp - $Mst);
    $lonecl = $lonecl + 0.023 * dsin(2 * $Mjp - 3 * $Mst + 52);
    $lonecl = $lonecl - 0.016 * dsin($Mjp - 5 * $Mst - 69);
    }if ($desc == "Saturn") {
    $Mjp = rev(19.8950 + 0.0830853001 * $d);
    $Mst = rev(316.9670 + 0.0334442282 * $d);
    $Mun = rev(142.5905 + 0.011725806 * $d);
    $lonecl = $lonecl + 0.812 * dsin(2 * $Mjp - 5 * $Mst - 67.6);
    $lonecl = $lonecl - 0.229 * dcos(2 * $Mjp - 4 * $Mst - 2);
    $lonecl = $lonecl + 0.119 * dsin($Mjp - 2 * $Mst - 3);
    $lonecl = $lonecl + 0.046 * dsin(2 * $Mjp - 6 * $Mst - 69);
    $lonecl = $lonecl + 0.014 * dsin($Mjp - 3 * $Mst + 32);
    $latecl = $latecl - 0.020 * dcos(2 * $Mjp - 4 * $Mst - 2);
    $latecl = $latecl + 0.018 * dsin(2 * $Mjp - 6 * $Mst - 49);
    }if ($desc == "Uranus") {
    $Mjp = rev(19.8950 + 0.0830853001 * $d);
    $Mst = rev(316.9670 + 0.0334442282 * $d);
    $Mun = rev(142.5905 + 0.011725806 * $d);
    $lonecl = $lonecl + 0.040 * dsin($Mst - 2 * $Mun + 6);
    $lonecl = $lonecl + 0.035 * dsin($Mst - 3 * $Mun + 33);
    $lonecl = $lonecl - 0.015 * dsin($Mjp - $Mun + 20);
    }$px = $r * dcos($lonecl) * dcos($latecl);
    $py = $r * dsin($lonecl) * dcos($latecl);
    $pz = $r * dsin($latecl);
}
function planet($desc, $v, $r) {
$oblecl = 23.4393 - 3.563E-7 * $d;
if ($v == null || $r == null) {
$M = rev($M);
$E = $M + 180 / M_PI * $e * dsin($M) * 1 + $e * dcos($M);
$E0 = 0;
$E1 = $E;
while (abs($E0 - $E1) > .005) {$E0 = $E1;
$E1 = $E0 - $E0 - 180 / M_PI * $e * dsin($E0) - $M / 1 - $e * dcos($E0);
}$E = $E1;
$x = $a * dcos($E) - $e;
$y = $a * dsin($E) * sqrt(1 - $e * $e);
$r = sqrt($x * $x + $y * $y);
$v = datan2($y, $x);
}$x = $r * dcos($N) * dcos($v + $w) - dsin($N) * dsin($v + $w) * dcos($i);
$y = $r * dsin($N) * dcos($v + $w) + dcos($N) * dsin($v + $w) * dcos($i);
$z = $r * dsin($v + $w) * dsin($i);
$px = $x;
$py = $y;
$pz = $z;
perturbations($desc);
$x = $px;
$y = $py;
$z = $pz;
if ($desc != "Moon") {
$x = $x + $xsun;
$y = $y + $ysun;
$z = $z + $zsun;
}$x0 = $x;
$y0 = $y * dcos($oblecl) - $z * dsin($oblecl);
$z0 = $y * dsin($oblecl) + $z * dcos($oblecl);
$r = sqrt($x0 * $x0 + $y0 * $y0 + $z0 * $z0);
$RA = datan2($y0, $x0) / 15;
while ($RA > 24) {$RA = $RA - 24;
}while ($RA < 0) {$RA = $RA + 24;
}$Decl = datan2($z0, sqrt($x0 * $x0 + $y0 * $y0));
$pRA = $RA;
$pDecl = $Decl;
topocentric($desc, $r);
$Decl = $pDecl;
$RA = $pRA;
$msg = $msg + '<br> ' + $desc + ' AD: ' + HMS($RA) + ' Dec: ' + DMS($Decl);
$HA = $LST - $RA * 15;
$x = dcos($HA) * dcos($Decl);
$y = dsin($HA) * dcos($Decl);
$z = dsin($Decl);
$xhor = $x * dsin($lat) - $z * dcos($lat);
$yhor = $y;
$zhor = $x * dcos($lat) + $z * dsin($lat);
$az = datan2($yhor, $xhor) + 180;
$al = dasn($zhor);
$msg = $msg + ' az: ' + DMS($az) + ' alt: ' + DMS($al);
}
function pluto() {
$oblecl = 23.4393 - 3.563E-7 * $d;
$S = 50.03 + 0.033459652 * $d;
$P = 238.95 + 0.003968789 * $d;
$lonecl = 238.9508 + 0.00400703 * $d;
$lonecl = $lonecl - 19.799 * dsin($P) + 19.848 * dcos($P);
$lonecl = $lonecl + 0.897 * dsin(2 * $P) - 4.956 * dcos(2 * $P);
$lonecl = $lonecl + 0.610 * dsin(3 * $P) + 1.211 * dcos(3 * $P);
$lonecl = $lonecl - 0.341 * dsin(4 * $P) - 0.190 * dcos(4 * $P);
$lonecl = $lonecl + 0.128 * dsin(5 * $P) - 0.034 * dcos(5 * $P);
$lonecl = $lonecl - 0.038 * dsin(6 * $P) + 0.031 * dcos(6 * $P);
$lonecl = $lonecl + 0.020 * dsin($S - $P) - 0.010 * dcos($S - $P);
$latecl = -3.9082;
$latecl = $latecl - 5.453 * dsin($P) - 14.975 * dcos($P);
$latecl = $latecl + 3.527 * dsin(2 * $P) + 1.673 * dcos(2 * $P);
$latecl = $latecl - 1.051 * dsin(3 * $P) + 0.328 * dcos(3 * $P);
$latecl = $latecl + 0.179 * dsin(4 * $P) - 0.292 * dcos(4 * $P);
$latecl = $latecl + 0.019 * dsin(5 * $P) + 0.100 * dcos(5 * $P);
$latecl = $latecl - 0.031 * dsin(6 * $P) - 0.026 * dcos(6 * $P);
$latecl = $latecl + 0.011 * dcos($S - $P);
$r = 40.72;
$r = $r + 6.68 * dsin($P) + 6.90 * dcos($P);
$r = $r - 1.18 * dsin(2 * $P) - 0.03 * dcos(2 * $P);
$r = $r + 0.15 * dsin(3 * $P) - 0.14 * dcos(3 * $P);
$x = $r * dcos($lonecl) * dcos($latecl);
$y = $r * dsin($lonecl) * dcos($latecl);
$z = $r * dsin($latecl);
$x = $x + $xsun;
$y = $y + $ysun;
$z = $z + $zsun;
$x0 = $x;
$y0 = $y * dcos($oblecl) - $z * dsin($oblecl);
$z0 = $y * dsin($oblecl) + $z * dcos($oblecl);
$RA = datan2($y0, $x0) / 15;
while ($RA > 24) {$RA = $RA - 24;
}while ($RA < 0) {$RA = $RA + 24;
}$Decl = datan2($z0, sqrt($x0 * $x0 + $y0 * $y0));
$msg = $msg + '<br> Pluton AD: ' + HMS($RA) + ' Dec: ' + DMS($Decl);
$HA = $LST - $RA * 15;
$x = dcos($HA) * dcos($Decl);
$y = dsin($HA) * dcos($Decl);
$z = dsin($Decl);
$xhor = $x * dsin($lat) - $z * dcos($lat);
$yhor = $y;
$zhor = $x * dcos($lat) + $z * dsin($lat);
$az = datan2($yhor, $xhor) + 180;
$al = dasn($zhor);
$msg = $msg + ' az: ' + DMS($az) + ' alt: ' + DMS($al);
}
function topocentric($desc, $r) {
$RA = $pRA;
$Decl = $pDecl;
$mpar = dasn(1 / $r);
if ($desc != "Moon") {
$mpar = 8.794 / 3600 / $r;
}$gclat = $lat - 0.1924 * dsin(2 * $lat);
$rho = 0.99883 + 0.00167 * dcos(2 * $lat);
$HA = $LST - $RA * 15;
$g = datan(dtan($gclat) / dcos($HA));
$pRA = $RA * 15 - $mpar * $rho * dcos($gclat) * dsin($HA) / dcos($Decl);
$pRA = $pRA / 15;
while ($pRA > 24) {$pRA = $pRA - 24;
}while ($pRA < 0) {$pRA = $pRA + 24;
}$pDecl = $Decl - $mpar * $rho * dsin($gclat) * dsin($g - $Decl) / dsin($g);
}
function ut($dte, $zone) {
$ut = null;
$ut = $dte->getHours() + $dte->getMinutes() / 100 + $dte->getSeconds() / 10000;
$ut = deg($ut) + $zone;
$ut_flag = 0;
if ($ut > 24) {
$ut = $ut - 24;
$ut_flag = 1;
}return rnd($ut, 4);
}
function zhour($dte) {
$y = NumFloat($dte->getYear()) + 1900;
if ($y >= 3900) {
$y = $y - 1900;
}$m = NumFloat($dte->getMonth()) + 1;
$d = NumFloat($dte->getDate());
return new Date($y, $m - 1, $d);
}
function cDte() {
$dte = new Date();
$mon = NumFloat($dte->getMonth()) + 1;
$dow = NumFloat($dte->getDay()) + 1;
$dom = NumFloat($dte->getDate());
$doy = NumFloat($dte->getYear()) + 1900;
if ($doy >= 3900) {
$doy = $doy - 1900;
}$msg = $day[$dow] + "<br>" + $dom + " " + $month[$mon] + " " + $doy;
$hr = NumFloat($dte->getHours());
$mn = NumFloat($dte->getMinutes());
$sc = NumFloat($dte->getSeconds());
$am = "AM";
if ($hr >= 12) {
$hr = $hr - 12;
$am = "PM";
}if ($hr == 0) {
$hr = 12;
}if ($mn < 10) {
$mn = "0" + $mn;
}if ($sc < 10) {
$sc = "0" + $sc;
}$msg = $msg + "<br>" + $hr + "h " + $mn + "min " + $sc + "s " + $am;
$UT = ut($dte, $zone + $dst);
$msg = $msg + "<br>Temps Universel (UT): " + $UT + ' (' + HMS($UT) + ')';
$j = julian($dte, $zone + $dst);
$msg = $msg + "<br>Date Julienne: " + rnd($j, 6);
$d = julian($dte, $zone + $dst) - 2451543.5;
$msg = $msg + "<br>Jour (d): " + $d;
$w = 282.9404 + 4.70935E-5 * $d;
$a = 1.000000;
$e = 0.016709 - 1.151E-9 * $d;
$M = 356.0470 + 0.9856002585 * $d;
$oblecl = 23.4393 - 3.563E-7 * $d;
$M = rev($M);
$L = rev($w + $M);
$Ms = $M;
$Ls = $L;
$E = $M + 180 / M_PI * $e * dsin($M) * 1 + $e * dcos($M);
$E0 = 0;
$E1 = $E;
while (abs($E0 - $E1) > .005) {$E0 = $E1;
$E1 = $E0 - $E0 - 180 / M_PI * $e * dsin($E0) - $M / 1 - $e * dcos($E0);
}$E = $E1;
$x = $a * dcos($E) - $e;
$y = $a * dsin($E) * sqrt(1 - $e * $e);
$r = sqrt($x * $x + $y * $y);
$v = datan2($y, $x);
$lng = rev($v + $w);
$x = $r * dcos($lng);
$y = $r * dsin($lng);
$z = 0.0;
$xsun = $x;
$ysun = $y;
$zsun = $z;
$x0 = $x;
$y0 = $y * dcos($oblecl) - $z * dsin($oblecl);
$z0 = $y * dsin($oblecl) + $z * dcos($oblecl);
$r = sqrt($x0 * $x0 + $y0 * $y0 + $z0 * $z0);
$RA = datan2($y0, $x0) / 15;
while ($RA > 24) {$RA = $RA - 24;
}while ($RA < 0) {$RA = $RA + 24;
}$Decl = datan2($z0, sqrt($x0 * $x0 + $y0 * $y0));
$GST = rev($L / 15 + 12) + $UT;
while ($GST > 24) {$GST = $GST - 24;
}while ($GST < 0) {$GST = $GST + 24;
}$msg = $msg + "<br>Temps Sidéral de Greenwich (GST): " + HMS($GST);
$LST = $GST + $lon / 15;
while ($LST > 24) {$LST = $LST - 24;
}while ($LST < 0) {$LST = $LST + 24;
}$msg = $msg + "<br>Temps Sidéral Local (LST) à Liège, Belgique: " + HMS($LST);
$HA = $LST - $RA * 15;
$x = dcos($HA) * dcos($Decl);
$y = dsin($HA) * dcos($Decl);
$z = dsin($Decl);
$xhor = $x * dsin($lat) - $z * dcos($lat);
$yhor = $y;
$zhor = $x * dcos($lat) + $z * dsin($lat);
$az = datan2($yhor, $xhor) + 180;
$al = dasn($zhor);
$msg = $msg + '<br>Soleil AD: ' + HMS($RA) + ' Dec: ' + DMS($Decl);
$msg = $msg + ' az: ' + DMS($az) + ' alt: ' + DMS($al);
$N = 125.1228 - 0.0529538083 * $d;
$i = 5.1454;
$w = 318.0634 + 0.1643573223 * $d;
$a = 60.2666;
$e = 0.054900;
$M = 115.3654 + 13.0649929509 * $d;
planet("Lune");
$N = 48.3313 + 3.24587E-5 * $d;
$i = 7.0047 + 5.00E-8 * $d;
$w = 29.1241 + 1.01444E-5 * $d;
$a = 0.387098;
$e = 0.205635 + 5.59E-10 * $d;
$M = 168.6562 + 4.0923344368 * $d;
planet("Mercure");
$N = 76.6799 + 2.46590E-5 * $d;
$i = 3.3946 + 2.75E-8 * $d;
$w = 54.8910 + 1.38374E-5 * $d;
$a = 0.723330;
$e = 0.006773 - 1.302E-9 * $d;
$M = 48.0052 + 1.6021302244 * $d;
planet("Venus");
$N = 49.5574 + 2.11081E-5 * $d;
$i = 1.8497 - 1.78E-8 * $d;
$w = 286.5016 + 2.92961E-5 * $d;
$a = 1.523688;
$e = 0.093405 + 2.516E-9 * $d;
$M = 18.6021 + 0.5240207766 * $d;
planet("Mars");
$N = 100.4542 + 2.76854E-5 * $d;
$i = 1.3030 - 1.557E-7 * $d;
$w = 273.8777 + 1.64505E-5 * $d;
$a = 5.20256;
$e = 0.048498 + 4.469E-9 * $d;
$M = 19.8950 + 0.0830853001 * $d;
planet("Jupiter");
$N = 113.6634 + 2.38980E-5 * $d;
$i = 2.4886 - 1.081E-7 * $d;
$w = 339.3939 + 2.97661E-5 * $d;
$a = 9.55475;
$e = 0.055546 - 9.499E-9 * $d;
$M = 316.9670 + 0.0334442282 * $d;
planet("Saturne");
$N = 74.0005 + 1.3978E-5 * $d;
$i = 0.7733 + 1.9E-8 * $d;
$w = 96.6612 + 3.0565E-5 * $d;
$a = 19.18171 - 1.55E-8 * $d;
$e = 0.047318 + 7.45E-9 * $d;
$M = 142.5905 + 0.011725806 * $d;
planet("Uranus");
$N = 131.7806 + 3.0173E-5 * $d;
$i = 1.7700 - 2.55E-7 * $d;
$w = 272.8461 - 6.027E-6 * $d;
$a = 30.05826 + 3.313E-8 * $d;
$e = 0.008606 + 2.15E-9 * $d;
$M = 260.2471 + 0.005995147 * $d;
planet("Neptune");
pluto();
if ($udo != "") {
object($udo);
}return $msg;
}
function Clock() {
$c = cDte();
if ($DocLay == true) {
$c = '<p align="center">' + $c + '<\/p>';
}$x = DynWrite('p001', $c);
if ($x == true) {
$timerid = setTimeout('Clock()', $timerlen);
}}
$DocDom = $DocLay = $DocAll = false;
$DocDom = ($document->getElementById) ? true : false;
if (!$DocDom) {
$DocLay = ($document->layers) ? true : false;
$DocAll = ($document->all) ? true : false;
}if ($DocDom) {
$DocDom = false;
foreach ($document as $i => $___){$DocDom = true;
break;}}$DocStr = "return false";
if ($DocLay) {
$DocStr = "return document.layers[id]";
}if ($DocAll) {
$DocStr = "return document.all[id]";
}if ($DocDom) {
$DocStr = "return document.getElementById(id)";
}$GetRef = new Function("id", $DocStr);
if ($DocAll || $DocDom) {
$DynWrite = new Function("id", "S", "GetRef(id).innerHTML=S; return true");
} else if ($DocLay) {
$DynWrite = new Function("id", "S", "var x=GetRef(id).document;" + "x.open('text/html'); x.write(S); x.close(); return true");
} else {$DynWrite = new Function("return false");
}$month = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
$day = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$msg = "";
$msgs = "";
$lst = "";
$timerid = null;
$timerlen = 1 * 1000;
$dst2 = null;
$zon1 = null;
$zon2 = null;
$zon1 = NumFloat(getStandardTimezoneOffset() / 60);
$dst2 = new Date();
$zon2 = NumFloat($dst2->getTimezoneOffset() / 60);
$dte = null;
$zone = null;
$dst = null;
$lat = null;
$lon = null;
$alt = null;
$dte = new Date();
$lat = 50.61666666666666;
$lon = 5.56666666666666;
$dst = $zon2 - $zon1;
$zone = NumFloat($dte->getTimezoneOffset() / 60) - $dst;
if (gettype($parent->locsrch) == "string") {
$x = array_search("?", $parent->locsrch);
if ($x != -1) {
$r = substr($parent->locsrch, $x + 1);
$x = array_search("lat=", strtolower($r));
if ($x != -1) {
$lat = NumFloat(substr($r, $x + 4));
}$x = array_search("lon=", strtolower($r));
if ($x != -1) {
$lon = NumFloat(substr($r, $x + 4));
}$x = array_search("zone=", strtolower($r));
if ($x != -1) {
$zone = NumFloat(substr($r, $x + 5));
}$x = array_search("dst=", strtolower($r));
if ($x != -1) {
$dst = NumFloat(substr($r, $x + 4));
}}}$latx = ($lat > 0) ? "N" : "S";
$lonx = ($lon > 0) ? "E" : "W";
$tmsg = '"' + 'Latitude=' + DMS(abs($lat), true) + $latx;
$tmsg = $tmsg + '&#013;&#010;Longitude=' + DMS(abs($lon), true) + $lonx;
$tmsg = $tmsg + '&#013;&#010;Time Zone=' + $zone;
$tmsg = $tmsg + '&#013;&#010;Daylight Savings=' + $dst;
$tmsg = $tmsg + '"';
$udo = "";
$udoT = 0;
$udow = 0;
$udoN = 0;
$udoNe = 2000;
$udoi = 0;
$udoe = 1;
$udoq = 0;
if (gettype($parent->topvar) == "string" && $parent->topvar != "") {
$r = $parent->topvar;
$x = array_search("$", strtolower($r));
if ($x != -1) {
$udo = $r->slice(0, $x);
}$x = array_search(" T ", strtoupper($r));
if ($x != -1) {
$udoT = newDate(substr($r, $x + 3));
}$x = array_search("peri.", strtolower($r));
if ($x != -1) {
$udow = parseFloat(substr($r, $x + 5));
}$x = array_search("incl.", strtolower($r));
if ($x != -1) {
$udoi = parseFloat(substr($r, $x + 5));
}$x = array_search(" e ", strtolower($r));
if ($x != -1) {
$udoe = parseFloat(substr($r, $x + 3));
}$x = array_search(" q ", strtolower($r));
if ($x != -1) {
$udoq = parseFloat(substr($r, $x + 3));
}$x = array_search("node ", strtolower($r));
if ($x != -1) {
$udoN = parseFloat(substr($r, $x + 5));
}if ($x != -1) {
$t = substr($r, $x + 5);
$x = 0;
while (substr($t, $x, 1) == " ") {$x++;
}while (substr($t, $x, 1) != " ") {$x++;
}if (substr($t, $x, 1) == " " && parseFloat(substr($t, $x)) > 1000) {
$udoNe = parseFloat(substr($t, $x));
}}}