<?php
// astro_calculator.php - Calculateur principal des positions astronomiques
require_once 'astro_utils.php';

class AstroCalculator {
    
    private $lat;     // Latitude
    private $lon;     // Longitude  
    private $elev;     // Altitude
    private $d;       // Jour julien modifié
    private $oblecl;  // Obliquité de l'écliptique
    private $LST;     // Temps sidéral local
    private $Ms;      // Anomalie moyenne du Soleil
    private $Ls;      // Longitude vraie du Soleil
    private $xsun, $ysun, $zsun; // Position du Soleil
    private $px, $py, $pz; // Position planétaire temporaire
    private $pRA, $pDecl; // RA/Dec temporaires pour topocentric
    
    // Variables temporaires pour les calculs planétaires
    private $N, $i, $w, $a, $e, $M;
    
    public function __construct(float $lat = 45.783552, float $lon = 3.071572, float $elev = 400) {
        $this->lat = $lat;
        $this->lon = $lon;
        $this->elev = $elev;
    }
    
    // Calcul des perturbations planétaires
    private function perturbations(string $desc): void {
        $x = $this->px;
        $y = $this->py;
        $z = $this->pz;
        
        $lonecl = AstroUtils::rev(AstroUtils::datan2($y, $x));
        $latecl = AstroUtils::datan2($z, sqrt($x*$x + $y*$y));
        $r = sqrt($x*$x + $y*$y + $z*$z);
        
        if ($desc == "Moon" || $desc == "Lune") {
            $Mm = AstroUtils::rev($this->M);
            $Lm = AstroUtils::rev($this->N + $this->w + $this->M);
            $D = $Lm - $this->Ls;
            $F = $Lm - $this->N;
            
            $lonecl = $lonecl - 1.274 * AstroUtils::dsin($Mm - 2*$D);
            $lonecl = $lonecl + 0.658 * AstroUtils::dsin(2*$D);
            $lonecl = $lonecl - 0.186 * AstroUtils::dsin($this->Ms);
            $lonecl = $lonecl - 0.059 * AstroUtils::dsin(2*$Mm - 2*$D);
            $lonecl = $lonecl - 0.057 * AstroUtils::dsin($Mm - 2*$D + $this->Ms);
            $lonecl = $lonecl + 0.053 * AstroUtils::dsin($Mm + 2*$D);
            $lonecl = $lonecl + 0.046 * AstroUtils::dsin(2*$D - $this->Ms);
            $lonecl = $lonecl + 0.041 * AstroUtils::dsin($Mm - $this->Ms);
            $lonecl = $lonecl - 0.035 * AstroUtils::dsin($D);
            $lonecl = $lonecl - 0.031 * AstroUtils::dsin($Mm + $this->Ms);
            $lonecl = $lonecl - 0.015 * AstroUtils::dsin(2*$F - 2*$D);
            $lonecl = $lonecl + 0.011 * AstroUtils::dsin($Mm - 4*$D);
            
            $latecl = $latecl - 0.173 * AstroUtils::dsin($F - 2*$D);
            $latecl = $latecl - 0.055 * AstroUtils::dsin($Mm - $F - 2*$D);
            $latecl = $latecl - 0.046 * AstroUtils::dsin($Mm + $F - 2*$D);
            $latecl = $latecl + 0.033 * AstroUtils::dsin($F + 2*$D);
            $latecl = $latecl + 0.017 * AstroUtils::dsin(2*$Mm + $F);
            
            $r = $r - 0.58 * AstroUtils::dcos($Mm - 2*$D);
            $r = $r - 0.46 * AstroUtils::dcos(2*$D);
        }
        
        if ($desc == "Jupiter") {
            $Mjp = AstroUtils::rev(19.8950 + 0.0830853001 * $this->d);
            $Mst = AstroUtils::rev(316.9670 + 0.0334442282 * $this->d);
            $lonecl = $lonecl - 0.332 * AstroUtils::dsin(2*$Mjp - 5*$Mst - 67.6);
            $lonecl = $lonecl - 0.056 * AstroUtils::dsin(2*$Mjp - 2*$Mst + 21);
            $lonecl = $lonecl + 0.042 * AstroUtils::dsin(3*$Mjp - 5*$Mst + 21);
            $lonecl = $lonecl - 0.036 * AstroUtils::dsin($Mjp - 2*$Mst);
            $lonecl = $lonecl + 0.022 * AstroUtils::dcos($Mjp - $Mst);
            $lonecl = $lonecl + 0.023 * AstroUtils::dsin(2*$Mjp - 3*$Mst + 52);
            $lonecl = $lonecl - 0.016 * AstroUtils::dsin($Mjp - 5*$Mst - 69);
        }
        
        if ($desc == "Saturn" || $desc == "Saturne") {
            $Mjp = AstroUtils::rev(19.8950 + 0.0830853001 * $this->d);
            $Mst = AstroUtils::rev(316.9670 + 0.0334442282 * $this->d);
            $lonecl = $lonecl + 0.812 * AstroUtils::dsin(2*$Mjp - 5*$Mst - 67.6);
            $lonecl = $lonecl - 0.229 * AstroUtils::dcos(2*$Mjp - 4*$Mst - 2);
            $lonecl = $lonecl + 0.119 * AstroUtils::dsin($Mjp - 2*$Mst - 3);
            $lonecl = $lonecl + 0.046 * AstroUtils::dsin(2*$Mjp - 6*$Mst - 69);
            $lonecl = $lonecl + 0.014 * AstroUtils::dsin($Mjp - 3*$Mst + 32);
            
            $latecl = $latecl - 0.020 * AstroUtils::dcos(2*$Mjp - 4*$Mst - 2);
            $latecl = $latecl + 0.018 * AstroUtils::dsin(2*$Mjp - 6*$Mst - 49);
        }
        
        if ($desc == "Uranus") {
            $Mjp = AstroUtils::rev(19.8950 + 0.0830853001 * $this->d);
            $Mst = AstroUtils::rev(316.9670 + 0.0334442282 * $this->d);
            $Mun = AstroUtils::rev(142.5905 + 0.011725806 * $this->d);
            $lonecl = $lonecl + 0.040 * AstroUtils::dsin($Mst - 2*$Mun + 6);
            $lonecl = $lonecl + 0.035 * AstroUtils::dsin($Mst - 3*$Mun + 33);
            $lonecl = $lonecl - 0.015 * AstroUtils::dsin($Mjp - $Mun + 20);
        }
        
        $this->px = $r * AstroUtils::dcos($lonecl) * AstroUtils::dcos($latecl);
        $this->py = $r * AstroUtils::dsin($lonecl) * AstroUtils::dcos($latecl);
        $this->pz = $r * AstroUtils::dsin($latecl);
    }
    
    // Correction topocentric
    private function topocentric(string $desc, float $r): void {
        $RA = $this->pRA;
        $Decl = $this->pDecl;
        $mpar = AstroUtils::dasn(1 / $r);
        if ($desc != "Moon" && $desc != "Lune") $mpar = (8.794 / 3600) / $r;
        $gclat = $this->lat - 0.1924 * AstroUtils::dsin(2 * $this->lat);
        $rho = 0.99883 + 0.00167 * AstroUtils::dcos(2 * $this->lat);
        $HA = ($this->LST - $RA) * 15;
        $g = AstroUtils::datan(AstroUtils::dtan($gclat) / AstroUtils::dcos($HA));
        $this->pRA = $RA * 15 - $mpar * $rho * AstroUtils::dcos($gclat) * AstroUtils::dsin($HA) / AstroUtils::dcos($Decl);
        $this->pRA = $this->pRA / 15;
        while ($this->pRA > 24) { $this->pRA = $this->pRA - 24; }
        while ($this->pRA < 0) { $this->pRA = $this->pRA + 24; }
        $this->pDecl = $Decl - $mpar * $rho * AstroUtils::dsin($gclat) * AstroUtils::dsin($g - $Decl) / AstroUtils::dsin($g);
    }
    
    // Calcul des positions planétaires
    private function planet(string $desc, ?float $v = null, ?float $r = null): array {
        if ($v === null || $r === null) {
            $this->M = AstroUtils::rev($this->M);
            
            $E = $this->M + (180 / pi()) * $this->e * AstroUtils::dsin($this->M) * (1 + $this->e * AstroUtils::dcos($this->M));
            
            $E0 = 0;
            $E1 = $E;
            while (abs($E0 - $E1) > 0.005) {
                $E0 = $E1;
                $E1 = $E0 - ($E0 - (180 / pi()) * $this->e * AstroUtils::dsin($E0) - $this->M) / (1 - $this->e * AstroUtils::dcos($E0));
            }
            $E = $E1;
            
            $x = $this->a * (AstroUtils::dcos($E) - $this->e);
            $y = $this->a * (AstroUtils::dsin($E) * sqrt(1 - $this->e * $this->e));
            
            $r = sqrt($x*$x + $y*$y);
            $v = AstroUtils::datan2($y, $x);
        }
        
        $x = $r * (AstroUtils::dcos($this->N) * AstroUtils::dcos($v + $this->w) - AstroUtils::dsin($this->N) * AstroUtils::dsin($v + $this->w) * AstroUtils::dcos($this->i));
        $y = $r * (AstroUtils::dsin($this->N) * AstroUtils::dcos($v + $this->w) + AstroUtils::dcos($this->N) * AstroUtils::dsin($v + $this->w) * AstroUtils::dcos($this->i));
        $z = $r * AstroUtils::dsin($v + $this->w) * AstroUtils::dsin($this->i);
        
        $this->px = $x;
        $this->py = $y;
        $this->pz = $z;
        $this->perturbations($desc);
        $x = $this->px;
        $y = $this->py;
        $z = $this->pz;
        
        if ($desc != "Moon" && $desc != "Lune") {
            $x = $x + $this->xsun;
            $y = $y + $this->ysun;
            $z = $z + $this->zsun;
        }
        
        $x0 = $x;
        $y0 = $y * AstroUtils::dcos($this->oblecl) - $z * AstroUtils::dsin($this->oblecl);
        $z0 = $y * AstroUtils::dsin($this->oblecl) + $z * AstroUtils::dcos($this->oblecl);
        
        $r = sqrt($x0*$x0 + $y0*$y0 + $z0*$z0);
        
        $RA = AstroUtils::datan2($y0, $x0) / 15;
        while ($RA > 24) { $RA = $RA - 24; }
        while ($RA < 0) { $RA = $RA + 24; }
        $Decl = AstroUtils::datan2($z0, sqrt($x0*$x0 + $y0*$y0));
        
        $this->pRA = $RA;
        $this->pDecl = $Decl;
        $this->topocentric($desc, $r);
        $Decl = $this->pDecl;
        $RA = $this->pRA;
        
        $HA = ($this->LST - $RA) * 15;
        
        $x = AstroUtils::dcos($HA) * AstroUtils::dcos($Decl);
        $y = AstroUtils::dsin($HA) * AstroUtils::dcos($Decl);
        $z = AstroUtils::dsin($Decl);
        
        $xhor = $x * AstroUtils::dsin($this->lat) - $z * AstroUtils::dcos($this->lat);
        $yhor = $y;
        $zhor = $x * AstroUtils::dcos($this->lat) + $z * AstroUtils::dsin($this->lat);
        
        $az = AstroUtils::datan2($yhor, $xhor) + 180;
        $al = AstroUtils::dasn($zhor);
        
        return [
            'name' => $desc,
            'ra' => AstroUtils::HMS($RA),
            'dec' => AstroUtils::DMS($Decl),
            'az' => AstroUtils::DMS($az),
            'alt' => AstroUtils::DMS($al)
        ];
    }
    
    // Calcul de Pluton
    private function pluto(): array {
        $S = 50.03 + 0.033459652 * $this->d;
        $P = 238.95 + 0.003968789 * $this->d;
        
        $lonecl = 238.9508 + 0.00400703 * $this->d;
        $lonecl = $lonecl - 19.799 * AstroUtils::dsin($P) + 19.848 * AstroUtils::dcos($P);
        $lonecl = $lonecl + 0.897 * AstroUtils::dsin(2*$P) - 4.956 * AstroUtils::dcos(2*$P);
        $lonecl = $lonecl + 0.610 * AstroUtils::dsin(3*$P) + 1.211 * AstroUtils::dcos(3*$P);
        $lonecl = $lonecl - 0.341 * AstroUtils::dsin(4*$P) - 0.190 * AstroUtils::dcos(4*$P);
        $lonecl = $lonecl + 0.128 * AstroUtils::dsin(5*$P) - 0.034 * AstroUtils::dcos(5*$P);
        $lonecl = $lonecl - 0.038 * AstroUtils::dsin(6*$P) + 0.031 * AstroUtils::dcos(6*$P);
        $lonecl = $lonecl + 0.020 * AstroUtils::dsin($S-$P) - 0.010 * AstroUtils::dcos($S-$P);
        
        $latecl = -3.9082;
        $latecl = $latecl - 5.453 * AstroUtils::dsin($P) - 14.975 * AstroUtils::dcos($P);
        $latecl = $latecl + 3.527 * AstroUtils::dsin(2*$P) + 1.673 * AstroUtils::dcos(2*$P);
        $latecl = $latecl - 1.051 * AstroUtils::dsin(3*$P) + 0.328 * AstroUtils::dcos(3*$P);
        $latecl = $latecl + 0.179 * AstroUtils::dsin(4*$P) - 0.292 * AstroUtils::dcos(4*$P);
        $latecl = $latecl + 0.019 * AstroUtils::dsin(5*$P) + 0.100 * AstroUtils::dcos(5*$P);
        $latecl = $latecl - 0.031 * AstroUtils::dsin(6*$P) - 0.026 * AstroUtils::dcos(6*$P);
        $latecl = $latecl + 0.011 * AstroUtils::dcos($S-$P);
        
        $r = 40.72;
        $r = $r + 6.68 * AstroUtils::dsin($P) + 6.90 * AstroUtils::dcos($P);
        $r = $r - 1.18 * AstroUtils::dsin(2*$P) - 0.03 * AstroUtils::dcos(2*$P);
        $r = $r + 0.15 * AstroUtils::dsin(3*$P) - 0.14 * AstroUtils::dcos(3*$P);
        
        $x = $r * AstroUtils::dcos($lonecl) * AstroUtils::dcos($latecl);
        $y = $r * AstroUtils::dsin($lonecl) * AstroUtils::dcos($latecl);
        $z = $r * AstroUtils::dsin($latecl);
        
        $x = $x + $this->xsun;
        $y = $y + $this->ysun;
        $z = $z + $this->zsun;
        
        $x0 = $x;
        $y0 = $y * AstroUtils::dcos($this->oblecl) - $z * AstroUtils::dsin($this->oblecl);
        $z0 = $y * AstroUtils::dsin($this->oblecl) + $z * AstroUtils::dcos($this->oblecl);
        
        $RA = AstroUtils::datan2($y0, $x0) / 15;
        while ($RA > 24) { $RA = $RA - 24; }
        while ($RA < 0) { $RA = $RA + 24; }
        $Decl = AstroUtils::datan2($z0, sqrt($x0*$x0 + $y0*$y0));
        
        $HA = ($this->LST - $RA) * 15;
        
        $x = AstroUtils::dcos($HA) * AstroUtils::dcos($Decl);
        $y = AstroUtils::dsin($HA) * AstroUtils::dcos($Decl);
        $z = AstroUtils::dsin($Decl);
        
        $xhor = $x * AstroUtils::dsin($this->lat) - $z * AstroUtils::dcos($this->lat);
        $yhor = $y;
        $zhor = $x * AstroUtils::dcos($this->lat) + $z * AstroUtils::dsin($this->lat);
        
        $az = AstroUtils::datan2($yhor, $xhor) + 180;
        $al = AstroUtils::dasn($zhor);
        
        return [
            'name' => 'Pluton',
            'ra' => AstroUtils::HMS($RA),
            'dec' => AstroUtils::DMS($Decl),
            'az' => AstroUtils::DMS($az),
            'alt' => AstroUtils::DMS($al)
        ];
    }
    
    // Fonction principale de calcul
    public function calculatePositions(DateTime $dte): array {
        // Initialisation des variables de base - Temps Universel (UT/GMT)
        $utResult = AstroUtils::ut($dte);
        $UT = $utResult['ut'];
        
        // Date julienne et jour modifié (d) depuis J2000.0
        $j = AstroUtils::julian($dte);
        $this->d = $j - 2451543.5;
        
        // Obliquité de l'écliptique
        $this->oblecl = 23.4393 - 3.563E-7 * $this->d;
        
        // ===============================================
        // CALCUL DU SOLEIL (nécessaire pour GST et LST)
        // ===============================================
        $w = 282.9404 + 4.70935E-5 * $this->d;    // longitude du périhélie
        $a = 1.000000;                            // distance moyenne (UA)
        $e = 0.016709 - 1.151E-9 * $this->d;     // excentricité
        $M = 356.0470 + 0.9856002585 * $this->d; // anomalie moyenne
        
        $M = AstroUtils::rev($M);
        $L = AstroUtils::rev($w + $M);  // Longitude moyenne du Soleil
        
        // Sauvegarde pour perturbations lunaires
        $this->Ms = $M;
        $this->Ls = $L;
        
        // Résolution de l'équation de Kepler pour le Soleil
        $E = $M + (180 / pi()) * $e * AstroUtils::dsin($M) * (1 + $e * AstroUtils::dcos($M));
        
        $E0 = 0;
        $E1 = $E;
        while (abs($E0 - $E1) > 0.005) {
            $E0 = $E1;
            $E1 = $E0 - ($E0 - (180 / pi()) * $e * AstroUtils::dsin($E0) - $M) / (1 - $e * AstroUtils::dcos($E0));
        }
        $E = $E1;
        
        // Position héliocentrique du Soleil
        $x = $a * (AstroUtils::dcos($E) - $e);
        $y = $a * (AstroUtils::dsin($E) * sqrt(1 - $e*$e));
        
        $r = sqrt($x*$x + $y*$y);
        $v = AstroUtils::datan2($y, $x);
        
        $lng = AstroUtils::rev($v + $w);  // Longitude vraie du Soleil
        
        // Position géocentrique du Soleil (inverse car nous sommes sur Terre)
        $x = $r * AstroUtils::dcos($lng);
        $y = $r * AstroUtils::dsin($lng);
        $z = 0.0;
        
        // Position du Soleil pour les calculs planétaires
        $this->xsun = $x;
        $this->ysun = $y;
        $this->zsun = $z;
        
        // ===============================================
        // CALCUL DES TEMPS SIDÉRAUX (GST et LST)
        // ===============================================
        
        // Temps Sidéral de Greenwich (GST) - Formule exacte
        // GST = L_soleil/15 + 12 + UT (en heures)
        $GST = AstroUtils::rev(($L / 15) + 12) + $UT;
        while ($GST > 24) { $GST = $GST - 24; }
        while ($GST < 0) { $GST = $GST + 24; }
        
        // Temps Sidéral Local (LST) = GST + longitude/15
        $this->LST = $GST + $this->lon / 15;
        while ($this->LST > 24) { $this->LST = $this->LST - 24; }
        while ($this->LST < 0) { $this->LST = $this->LST + 24; }
        
        // ===============================================
        // POSITION ÉQUATORIALE DU SOLEIL
        // ===============================================
        
        // Conversion vers les coordonnées équatoriales
        $x0 = $x;
        $y0 = $y * AstroUtils::dcos($this->oblecl) - $z * AstroUtils::dsin($this->oblecl);
        $z0 = $y * AstroUtils::dsin($this->oblecl) + $z * AstroUtils::dcos($this->oblecl);
        
        // Ascension Droite et Déclinaison du Soleil
        $RA = AstroUtils::datan2($y0, $x0) / 15;
        while ($RA > 24) { $RA = $RA - 24; }
        while ($RA < 0) { $RA = $RA + 24; }
        $Decl = AstroUtils::datan2($z0, sqrt($x0*$x0 + $y0*$y0));
        
        // ===============================================
        // COORDONNÉES HORIZONTALES DU SOLEIL (Az, Alt)
        // ===============================================
        
        // Angle Horaire du Soleil
        $HA = ($this->LST - $RA) * 15;
        
        // Transformation équatoriale → horizontale
        $x = AstroUtils::dcos($HA) * AstroUtils::dcos($Decl);
        $y = AstroUtils::dsin($HA) * AstroUtils::dcos($Decl);
        $z = AstroUtils::dsin($Decl);
        
        // Rotation selon la latitude de l'observateur
        $xhor = $x * AstroUtils::dsin($this->lat) - $z * AstroUtils::dcos($this->lat);
        $yhor = $y;
        $zhor = $x * AstroUtils::dcos($this->lat) + $z * AstroUtils::dsin($this->lat);
        
        // Azimut et Altitude
        $az = AstroUtils::datan2($yhor, $xhor) + 180;
        $al = AstroUtils::dasn($zhor);
        
        // ===============================================
        // STOCKAGE DES RÉSULTATS
        // ===============================================
        
        $results = [];
        
        // Informations temporelles pour debug/affichage
        $timeInfo = [
            'ut_gmt' => $UT,
            'julian_day' => $j,
            'day_number' => $this->d,
            'gst' => $GST,
            'lst' => $this->LST,
            'obliquity' => $this->oblecl
        ];
        
        $results[] = [
            'name' => 'Soleil',
            'ra' => AstroUtils::HMS($RA),
            'dec' => AstroUtils::DMS($Decl),
            'az' => AstroUtils::DMS($az),
            'alt' => AstroUtils::DMS($al)
        ];
        
        // Lune
        $this->N = 125.1228 - 0.0529538083 * $this->d;
        $this->i = 5.1454;
        $this->w = 318.0634 + 0.1643573223 * $this->d;
        $this->a = 60.2666;
        $this->e = 0.054900;
        $this->M = 115.3654 + 13.0649929509 * $this->d;
        $results[] = $this->planet("Lune");
        
        // Mercure
        $this->N = 48.3313 + 3.24587E-5 * $this->d;
        $this->i = 7.0047 + 5.00E-8 * $this->d;
        $this->w = 29.1241 + 1.01444E-5 * $this->d;
        $this->a = 0.387098;
        $this->e = 0.205635 + 5.59E-10 * $this->d;
        $this->M = 168.6562 + 4.0923344368 * $this->d;
        $results[] = $this->planet("Mercure");
        
        // Vénus
        $this->N = 76.6799 + 2.46590E-5 * $this->d;
        $this->i = 3.3946 + 2.75E-8 * $this->d;
        $this->w = 54.8910 + 1.38374E-5 * $this->d;
        $this->a = 0.723330;
        $this->e = 0.006773 - 1.302E-9 * $this->d;
        $this->M = 48.0052 + 1.6021302244 * $this->d;
        $results[] = $this->planet("Venus");
        
        // Mars
        $this->N = 49.5574 + 2.11081E-5 * $this->d;
        $this->i = 1.8497 - 1.78E-8 * $this->d;
        $this->w = 286.5016 + 2.92961E-5 * $this->d;
        $this->a = 1.523688;
        $this->e = 0.093405 + 2.516E-9 * $this->d;
        $this->M = 18.6021 + 0.5240207766 * $this->d;
        $results[] = $this->planet("Mars");
        
        // Jupiter
        $this->N = 100.4542 + 2.76854E-5 * $this->d;
        $this->i = 1.3030 - 1.557E-7 * $this->d;
        $this->w = 273.8777 + 1.64505E-5 * $this->d;
        $this->a = 5.20256;
        $this->e = 0.048498 + 4.469E-9 * $this->d;
        $this->M = 19.8950 + 0.0830853001 * $this->d;
        $results[] = $this->planet("Jupiter");
        
        // Saturne
        $this->N = 113.6634 + 2.38980E-5 * $this->d;
        $this->i = 2.4886 - 1.081E-7 * $this->d;
        $this->w = 339.3939 + 2.97661E-5 * $this->d;
        $this->a = 9.55475;
        $this->e = 0.055546 - 9.499E-9 * $this->d;
        $this->M = 316.9670 + 0.0334442282 * $this->d;
        $results[] = $this->planet("Saturne");
        
        // Uranus
        $this->N = 74.0005 + 1.3978E-5 * $this->d;
        $this->i = 0.7733 + 1.9E-8 * $this->d;
        $this->w = 96.6612 + 3.0565E-5 * $this->d;
        $this->a = 19.18171 - 1.55E-8 * $this->d;
        $this->e = 0.047318 + 7.45E-9 * $this->d;
        $this->M = 142.5905 + 0.011725806 * $this->d;
        $results[] = $this->planet("Uranus");
        
        // Neptune
        $this->N = 131.7806 + 3.0173E-5 * $this->d;
        $this->i = 1.7700 - 2.55E-7 * $this->d;
        $this->w = 272.8461 - 6.027E-6 * $this->d;
        $this->a = 30.05826 + 3.313E-8 * $this->d;
        $this->e = 0.008606 + 2.15E-9 * $this->d;
        $this->M = 260.2471 + 0.005995147 * $this->d;
        $results[] = $this->planet("Neptune");
        
        // Pluton
        $results[] = $this->pluto();
        
        return $results;
    }
}
?>