<?php
// astro_utils.php - Fonctions utilitaires astronomiques
// Conversion fidèle du script JavaScript vers PHP 8+

class AstroUtils {
    
    // Fonction intr() - retourne la partie entière d'un nombre
    public static function intr(float $num): int {
        $n = (int)floor(abs($num));
        if ($num < 0) $n = $n * -1;
        return $n;
    }
    
    // Fonction NumFloat() - retourne une valeur numérique
    public static function NumFloat($num): float {
        $temp = (float)$num;
        if (!($temp > 0 || $temp < 0 || $temp == 0)) $temp = 0.0;
        return $temp;
    }
    
    // Fonction rnd() - arrondit un nombre avec décimales spécifiées
    public static function rnd(float $num, int $num2): float {
        return round($num * pow(10, $num2)) / pow(10, $num2);
    }
    
    // Fonction sgn() - retourne le signe d'un nombre
    public static function sgn(float $num): int {
        if ($num < 0) return -1;
        if ($num > 0) return 1;
        return 0;
    }
    
    // Fonction cbrt() - racine cubique
    public static function cbrt(float $x): float {
        if ($x > 0) return exp(log($x) / 3);
        if ($x < 0) return -self::cbrt(-$x);
        return 0.0;
    }
    
    // Conversion degrés vers radians
    public static function dtor(float $num): float {
        return $num / 57.29577951;
    }
    
    // Conversion radians vers degrés
    public static function rtod(float $num): float {
        return $num * 57.29577951;
    }
    
    // Sinus décimal
    public static function dsin(float $num): float {
        return sin(self::dtor($num));
    }
    
    // Cosinus décimal
    public static function dcos(float $num): float {
        return cos(self::dtor($num));
    }
    
    // Tangente décimale
    public static function dtan(float $num): float {
        return tan(self::dtor($num));
    }
    
    // Arcsinus décimal
    public static function dasn(float $num): float {
        if ($num == 1) {
            $y = 1.570796327;
        } else {
            $y = atan($num / sqrt(-1 * $num * $num + 1));
        }
        return self::rtod($y);
    }
    
    // Arccosinus décimal
    public static function dacs(float $num): float {
        if ($num == 1) {
            $y = 0;
        } else {
            $y = 1.570796327 - atan($num / sqrt(-1 * $num * $num + 1));
        }
        return self::rtod($y);
    }
    
    // Arctangente décimale
    public static function datan(float $num): float {
        return self::rtod(atan($num));
    }
    
    // Arctangente2 décimale
    public static function datan2(float $y, float $x): float {
        return self::rtod(atan2($y, $x));
    }
    
    // DD.MMSS vers degrés
    public static function deg(float $a): float {
        $signe = 1;
        if ($a < 0) {
            $a = -1 * $a;
            $signe = -1;
        }
        $a1 = self::intr($a);
        $mm = ($a - $a1) * 100;
        $mm = self::rnd($mm, 6);
        $a2 = self::intr($mm);
        $ss = ($mm - $a2) * 100;
        $ss = self::rnd($ss, 6);
        $a3 = $ss;
        return $signe * ($a1 + $a2 / 60 + $a3 / 3600);
    }
    
    // Degrés vers dms_internal
    public static function dms_internal(float $a): float {
        $signe = 1;
        if ($a < 0) {
            $a = -1 * $a;
            $signe = -1;
        }
        $a1 = self::intr($a);
        $mm = ($a - $a1) * 60;
        $mm = self::rnd($mm, 6);
        $a2 = self::intr($mm);
        $ss = ($mm - $a2) * 60;
        $ss = self::rnd($ss, 6);
        $a3 = self::intr($ss);
        return $signe * ($a1 + $a2 / 100 + $a3 / 10000);
    }
    
    // Format HMS (Heures Minutes Secondes)
    public static function HMS(float $x): string {
        $temp = self::dms_internal(abs($x));
        $hr = self::intr($temp);
        $temp = ($temp - $hr) * 100;
        $temp = self::rnd($temp, 6);
        $mn = self::intr($temp);
        $temp = ($temp - $mn) * 100;
        $temp = self::rnd($temp, 6);
        $sc = self::intr($temp);
        if ($mn < 10) $mn = "0" . $mn;
        if ($sc < 10) $sc = "0" . $sc;
        $tmp = (self::sgn($x) == -1) ? "-" : "";
        return $tmp . $hr . "h " . $mn . "min " . $sc . "s";
    }
    
    // Format DMS (Degrés Minutes Secondes)
    public static function DMS(float $x): string {
        $temp = self::dms_internal(abs($x));
        $hr = self::intr($temp);
        $temp = ($temp - $hr) * 100;
        $temp = self::rnd($temp, 6);
        $mn = self::intr($temp);
        $temp = ($temp - $mn) * 100;
        $temp = self::rnd($temp, 6);
        $sc = self::intr($temp);
        if ($mn < 10) $mn = "0" . $mn;
        if ($sc < 10) $sc = "0" . $sc;
        $tmp = (self::sgn($x) == -1) ? "-" : "";
        return $tmp . $hr . "°" . $mn . "'" . $sc . '"';
    }
    
    // Normalise un angle à 360 degrés
    public static function rev(float $x): float {
        return $x - floor($x / 360.0) * 360.0;
    }
    
    // Calcul du temps julien
    public static function julian(DateTime $dte): float {
        $ut = $dte->format('H') + $dte->format('i') / 100 + $dte->format('s') / 10000;
        $ut = self::deg($ut);
        
        $y = (float)$dte->format('Y');
        $m = (float)$dte->format('n');
        $d = (float)$dte->format('j') + $ut / 24;
        
        if ($m <= 2) {
            $m = $m + 12;
            $y = $y - 1;
        }
        $A = self::intr($y / 100);
        $B = 2 - $A + self::intr($A / 4);
        if ($y < 1582) $B = 0;
        
        if ($y < 0) {
            $C = self::intr((365.25 * $y) - 0.75);
        } else {
            $C = self::intr(365.25 * $y);
        }
        $D = self::intr(30.6001 * ($m + 1));
        return self::rnd($B + $C + $D + $d + 1720994.5, 6);
    }
    
    // Temps universel
    public static function ut(DateTime $dte): array {
        $ut = $dte->format('H') + $dte->format('i') / 100 + $dte->format('s') / 10000;
        $ut = self::deg($ut);
        $ut_flag = 0;
        if ($ut > 24) {
            $ut = $ut - 24;
            $ut_flag = 1;
        }
        return ['ut' => self::rnd($ut, 4), 'flag' => $ut_flag];
    }
}
?>