<?php
/**
 * REFERENCES: Meeus, Jean.
 * "Astronomical Algorithms, 1st ed." Willmann-Bell. Inc. 1991. pp. 315
 *
 * @author Todd A. Guillory 07-31-2001 created
 * @author Pascal Pechard <meteo66240@free.fr>
 *         06-01-2014 Adapted from C to PHP
 */
class moon implements Moonphases {
    public static $today;
    public static $latitude;
    public static $longitude;
    public static $J2000;
    public static $step;
    /**
     *
     * @param timestamp $today
     */
    function __construct($stamp = null) {
        self::$today = is_null($stamp) ? time() : $stamp;
        self::$latitude = deg2rad(42.7136788229883);
        self::$longitude = deg2rad(2.84930393099784);
        self::$J2000 = self::days(self::$today);
        self::$step = self::$J2000 - date("Z", self::$today) / 3600;
    }
    /**
     * Calculate Julian Day a given input phase occurs on
     *
     * @param float $year Year to compute phase on, day and time are fractions of the year
     * @param float Moonphases $phase
     *        phase to compute: 0 new, 0.25 first quarter, 0.5 full, 0.75 last quarter
     * @return float Julian Day that phase occurs on closest to the input day
     */
    function moon_phase($year, /*Moonphases*/ $phase) {

// U.S. Naval Observatory
// Astronomical Applications Department

// 2014 Phases of the Moon
// Universal Time

// New Moon First Quarter Full Moon Last Quarter

// d h m d h m d h m d h m

// Jan 1 11 14 Jan 8 3 39 Jan 16 4 52 Jan 24 5 20
// Jan 30 21 38 Feb 6 19 22 Feb 14 23 53 Feb 22 17 15
// Mar 1 8 00 Mar 8 13 27 Mar 16 17 08 Mar 24 1 46
// Mar 30 18 45 Apr 7 8 31 Apr 15 7 42 Apr 22 7 52
// Apr 29 6 14 May 7 3 15 May 14 19 16 May 21 12 59
// May 28 18 40 Jun 5 20 39 Jun 13 4 11 Jun 19 18 39
// Jun 27 8 08 Jul 5 11 59 Jul 12 11 25 Jul 19 2 08
// Jul 26 22 42 Aug 4 0 50 Aug 10 18 09 Aug 17 12 26
// Aug 25 14 13 Sep 2 11 11 Sep 9 1 38 Sep 16 2 05
// Sep 24 6 14 Oct 1 19 32 Oct 8 10 51 Oct 15 19 12
// Oct 23 21 57 Oct 31 2 48 Nov 6 22 23 Nov 14 15 15
// Nov 22 12 32 Nov 29 10 06 Dec 6 12 27 Dec 14 12 51
// Dec 22 1 36 Dec 28 18 31

        // $k = 0;
// $t = 0; time in Julian centuries
// $m = 0; Sun's mean anomaly
// $mprime = 0; Moon's mean anomaly
// $f = 0; Moon's argument of latitude
// $omega = 0; Longitude of the ascending node of the lunar orbit
// $w = 0; quarter phase corrections
// $a[14] = {0}; planatary arguments
// $atotal = 0; sum of planatary arguments
// $corrections = 0; sum of corrections
// $e = 0; eccentricity of Earth's orbit
        $k = floor((/*self::Year()*/$year - 2000.0) * 12.3685) + ($phase * 0.25);
        $t = ($k / 1236.85);
        $e = 1.0 - $t * (0.002516 - (0.0000074 * $t)); // pg 308
        $m = deg2rad(2.5534 + (29.10535669 * $k) - $t * $t * (0.0000218 - (0.00000011 * $t)));
        $mprime = deg2rad((201.5643 + (385.81693528 * $k) + $t * $t * (0.0107438 + (0.00001239 * $t) - (0.000000058 * $t * $t))));
        $f = deg2rad((160.7108 + (390.67050274 * $k) + $t * $t * (0.0016341 + (0.00000227 * $t) - (0.000000011 * $t * $t))));
        $omega = deg2rad((124.7746 - (1.56375580 * $k) + $t * $t * (0.0020691 + (0.00000215 * $t))));
        
        $a[0] = deg2rad((299.77 + (0.107408 * $k) - (0.009173 * $t * $t)));
        $a[1] = deg2rad((251.88 + (0.016321 * $k)));
        $a[2] = deg2rad((251.83 + (26.651886 * $k)));
        $a[3] = deg2rad((349.42 + (36.412478 * $k)));
        $a[4] = deg2rad((84.66 + (18.206239 * $k)));
        $a[5] = deg2rad((141.74 + (53.303771 * $k)));
        $a[6] = deg2rad((207.14 + (2.453732 * $k)));
        $a[7] = deg2rad((154.84 + (7.306860 * $k)));
        $a[8] = deg2rad((34.52 + (27.261239 * $k)));
        $a[9] = deg2rad((207.19 + (0.121824 * $k)));
        $a[10] = deg2rad((291.34 + (1.844379 * $k)));
        $a[11] = deg2rad((161.72 + (24.198154 * $k)));
        $a[12] = deg2rad((239.56 + (25.513099 * $k)));
        $a[13] = deg2rad((331.55 + (3.592518 * $k)));
        
        $atotal = .000001 * ((325 * sin($a[0])) + (165 * sin($a[1])) + (164 * sin($a[2])) + (126 * sin($a[3])) + (110 * sin($a[4])) + (62 * sin($a[5])) + (60 * sin($a[6])) + (56 * sin($a[7])) + (47 * sin($a[8])) + (42 * sin($a[9])) + (40 * sin($a[10])) + (37 * sin($a[11])) + (35 * sin($a[12])) + (23 * sin($a[13])));
        
        // $phase = new Moonphase();
        
        switch ($phase) {
            case Moonphases::newmoon :
                $corrections = -(0.40720 * sin($mprime)) + (0.17241 * $e * sin($m)) + (0.01608 * sin(2 * $mprime)) + (0.01039 * sin(2 * $f)) + (0.00739 * $e * sin($mprime - $m)) - (0.00514 * $e * sin($mprime + $m)) + (0.00208 * $e * $e * sin(2 * $m)) - (0.00111 * sin($mprime - 2 * $f)) - (0.00057 * sin($mprime + 2 * $f)) + (0.00056 * $e * sin(2 * $mprime + $m)) - (0.00042 * sin(3 * $mprime)) + (0.00042 * $e * sin($m + 2 * $f)) + (0.00038 * $e * sin($m - 2 * $f)) - (0.00024 * $e * sin(2 * $mprime - $m)) - (0.00017 * sin($omega)) - (0.00007 * sin($mprime + 2 * $m)) + (0.00004 * sin(2 * $mprime - 2 * $f)) + (0.00004 * sin(3 * $m)) + (0.00003 * sin($mprime + $m - 2 * $f)) + (0.00003 * sin(2 * $mprime + 2 * $f)) - (0.00003 * sin($mprime + $m + 2 * $f)) + (0.00003 * sin($mprime - $m + 2 * $f)) - (0.00002 * sin($mprime - $m - 2 * $f)) - (0.00002 * sin(3 * $mprime + $m)) + (0.00002 * sin(4 * $mprime));
                break;
            
            case Moonphases::fullmoon :
                $corrections = -(0.40614 * sin($mprime)) + (0.17302 * $e * sin($m)) + (0.01614 * sin(2 * $mprime)) + (0.01043 * sin(2 * $f)) + (0.00734 * $e * sin($mprime - $m)) - (0.00515 * $e * sin($mprime + $m)) + (0.00209 * $e * $e * sin(2 * $m)) - (0.00111 * sin($mprime - 2 * $f)) - (0.00057 * sin($mprime + 2 * $f)) + (0.00056 * $e * sin(2 * $mprime + $m)) - (0.00042 * sin(3 * $mprime)) + (0.00042 * $e * sin($m + 2 * $f)) + (0.00038 * $e * sin($m - 2 * $f)) - (0.00024 * $e * sin(2 * $mprime - $m)) - (0.00017 * sin($omega)) - (0.00007 * sin($mprime + 2 * $m)) + (0.00004 * sin(2 * $mprime - 2 * $f)) + (0.00004 * sin(3 * $m)) + (0.00003 * sin($mprime + $m - 2 * $f)) + (0.00003 * sin(2 * $mprime + 2 * $f)) - (0.00003 * sin($mprime + $m + 2 * $f)) + (0.00003 * sin($mprime - $m + 2 * $f)) - (0.00002 * sin($mprime - $m - 2 * $f)) - (0.00002 * sin(3 * $mprime + $m)) + (0.00002 * sin(4 * $mprime));
                break;
            
            case Moonphases::firstquarter :
            
            case Moonphases::lastquarter :
                $corrections = -(0.62801 * sin($mprime)) + (0.17172 * $e * sin($m)) - (0.01183 * $e * sin($mprime + $m)) + (0.00862 * sin(2 * $mprime)) + (0.00804 * sin(2 * $f)) + (0.00454 * $e * sin($mprime - $m)) + (0.00204 * $e * $e * sin(2 * $m)) - (0.00180 * sin($mprime - 2 * $f)) - (0.00070 * sin($mprime + 2 * $f)) - (0.00040 * sin(3 * $mprime)) - (0.00034 * $e * sin(2 * $mprime - $m)) + (0.00032 * $e * sin($m + 2 * $f)) + (0.00032 * $e * sin($m - 2 * $f)) - (0.00028 * $e * $e * sin($mprime + 2 * $m)) + (0.00027 * $e * sin(2 * $mprime + $m)) - (0.00017 * sin($omega)) - (0.00005 * sin($mprime - $m - 2 * $f)) + (0.00004 * sin(2 * $mprime + 2 * $f)) - (0.00004 * sin($mprime + $m + 2 * $f)) + (0.00004 * sin($mprime - 2 * $m)) + (0.00003 * sin($mprime + $m - 2 * $f)) + (0.00003 * sin(3 * $m)) + (0.00002 * sin(2 * $mprime - 2 * $f)) + (0.00002 * sin($mprime - $m + 2 * $f)) - (0.00002 * sin(3 * $mprime + $m));
                
                $w = .00306 - .00038 * $e * cos($m) + .00026 * cos($mprime) - .00002 * cos($mprime - $m) + .00002 * cos($mprime + $m) + .00002 * cos(2 * $f);
                
                if ($phase == Moonphases::lastquarter) $w = -$w;
                break;
            
            default :
                return -1.0;
        }
        
        return (2451550.09765 + (29.530588853 * $k) + (0.0001337 * pow($t, 2)) - (0.000000150 * pow($t, 3)) + (0.00000000073 * pow($t, 4)) + $corrections + $atotal + $w);
    }
    
    /**
     * Calculate the percent illumination of the moon (0 <= k <= 1) on the given input Julian Day
     *
     * @param double $inJulian Fractional Julian Day to calculate percent illumination of the moon's disc on
     * @return double illuminated fraction of moon's disc
     * @uses Revolution
     */
    function simple_illumination() {
        // From MyWee Pascal
        $DST = date("Z") / 3600; // 2;
                                 // $JOU = date("z");
                                 // $LAT = deg2rad( 42. );
                                 // $LON = deg2rad( 2. );
                                 // $SO = 1367.6;
                                 // $HOD = (date("G")*60 + date("i") - .5) / 60; //tSV
                                 // $HRA = 2*pi()*($HOD - 12.0) / 24.0; //Angle Horaire
        $jd = GregorianToJD(date("m"), date("d"), date("Y"));
        // $HD = ((date("G")+((date('i') + date('s') / 60)/60))/24);
        // correct for half-day offset
        $dayfrac = date('G') / 24 - .5;
        if ($dayfrac < 0) $dayfrac += 1;
        // now set the fraction of a day
        $frac = $dayfrac + (date('i') + date('s') / 60) / 60 / 24;
        $julianDate = $jd + $frac - $DST / 24;
        
        // pg. 131 */
        /**
         * Julian Centuries
         */
        $julian_centuries = ($julianDate - 2451545.0) / 36525.0;
        /**
         * mean elogation of the moon
         */
        $D = 297.8502042 + (445267.1115168 * $julian_centuries) - (0.0016300 * $julian_centuries * $julian_centuries) + (($julian_centuries * $julian_centuries * $julian_centuries) / 545868) - (($julian_centuries * $julian_centuries * $julian_centuries * $julian_centuries) / 113065000);
        /**
         * sun's mean anomaly
         */
        $M = 357.5291092 + (35999.0502909 * $julian_centuries) - (0.0001536 * $julian_centuries * $julian_centuries) + (($julian_centuries * $julian_centuries * $julian_centuries) / 24490000);
        /**
         * moon's mean anomaly
         */
        $Mprime = 134.9634114 + (477198.8676313 * $julian_centuries) + (0.0089970 * $julian_centuries * $julian_centuries) + (($julian_centuries * $julian_centuries * $julian_centuries) / 69699) - (($julian_centuries * $julian_centuries * $julian_centuries * $julian_centuries) / 14712000);
        
        $D = fmod($D, 360.0);
        $M = fmod($M, 360.0);
        $Mprime = fmod($Mprime, 360.0);
        
        $M = deg2rad($M);
        $Mprime = deg2rad($Mprime);
        /**
         * phase angle of the moon
         */
        $i = 180 - $D - (6.289 * sin($Mprime)) + (2.100 * sin($M)) - (1.274 * sin(2 * ($D) - $Mprime)) - (0.658 * sin(2 * ($D))) - (0.214 * sin(2 * $Mprime)) - (0.110 * sin(deg2rad($D)));
        
        /**
         * illuminated fraction of moon's disc
         */
        $k = (1 + cos(deg2rad($i))) / 2;
        
        return $k;
    }
    
    /**
     * Compute the Julian Day for the Equinox and Solstice
     *
     * @param double $inYear Year to compute event in
     * @param unsigned short $inES
     *        Event: 0 march equinox, 1 june solstice, 2 september equinox, 3 december solstice
     * @return number closest Julian Day event occurs
     */
    function equinox_solstice($inYear, $inES) {
        // $y;
        // $jden; /* Julian Ephemeris Day */
        // $julian_centuries; /* Julian Centuries */
        // $W;
        // $lambda;
        // $S; /* sum of periodic terms */
        if ($inYear >= 1000) {
            $y = (floor($inYear) - 2000) / 1000;
            
            if ($inES == 0) /* march equinox */
                $jden = 2451623.80984 + 365242.37404 * $y + 0.05169 * ($y * $y) - 0.00411 * ($y * $y * $y) - 0.00057 * ($y * $y * $y * $y);
            elseif ($inES == 1) /* june solstice */
            $jden = 2451716.56767 + 365241.62603 * $y + 0.00325 * ($y * $y) - 0.00888 * ($y * $y * $y) - 0.00030 * ($y * $y * $y * $y);
            elseif ($inES == 2) /* september equinox */
            $jden = 2451810.21715 + 365242.01767 * $y + 0.11575 * ($y * $y) - 0.00337 * ($y * $y * $y) - 0.00078 * ($y * $y * $y * $y);
            elseif ($inES == 3) /* december solstice */
            $jden = 2451900.05952 + 365242.74049 * $y + 0.06223 * ($y * $y) - 0.00823 * ($y * $y * $y) - 0.00032 * ($y * $y * $y * $y);
            else return -1;
        } else {
            $y = floor($inYear) / 1000;
            
            if ($inES == 0) /* march equinox */
                $jden = 1721139.29189 + 365242.13740 * $y + 0.06134 * ($y * $y) - 0.00111 * ($y * $y * $y) - 0.00071 * ($y * $y * $y * $y);
            else if ($inES == 1) /* june solstice */
                $jden = 1721233.25401 + 365241.72562 * $y + 0.05323 * ($y * $y) - 0.00907 * ($y * $y * $y) - 0.00025 * ($y * $y * $y * $y);
            else if ($inES == 2) /* september equinox */
                $jden = 1721325.70455 + 365242.49558 * $y + 0.11677 * ($y * $y) - 0.00297 * ($y * $y * $y) - 0.00074 * ($y * $y * $y * $y);
            else if ($inES == 3) /* december solstice */
                $jden = 1721414.39987 + 365242.88257 * $y + 0.00769 * ($y * $y) - 0.00933 * ($y * $y * $y) - 0.00006 * ($y * $y * $y * $y);
            else return -1;
        }
        
        $julian_centuries = ($jden - 2451545.0) / 36525;
        $W = 35999.373 * $julian_centuries - 2.47;
        $lambda = 1 + 0.0334 * cos(deg2rad($W)) + 0.0007 * cos(deg2rad(2 * $W));
        $S = 485 * cos(deg2rad(324.96) + deg2rad((1934.136 * $julian_centuries))) + 203 * cos(deg2rad(337.23) + deg2rad((32964.467 * $julian_centuries))) + 199 * cos(deg2rad(342.08) + deg2rad((20.186 * $julian_centuries))) + 182 * cos(deg2rad(27.85) + $kDegRad * (445267.112 * $julian_centuries)) + 156 * cos(deg2rad(73.14) + $kDegRad * (45036.886 * $julian_centuries)) + 136 * cos(deg2rad(171.52) + $kDegRad * (22518.443 * $julian_centuries)) + 77 * cos($kDegRad * 222.54 + $kDegRad * (65928.934 * $julian_centuries)) + 74 * cos(deg2rad(296.72) + $kDegRad * (3034.906 * $julian_centuries)) + 70 * cos(deg2rad(243.58) + $kDegRad * (9037.513 * $julian_centuries)) + 58 * cos($kDegRad * 119.81 + $kDegRad * (33718.147 * $julian_centuries)) + 52 * cos($kDegRad * 297.17 + $kDegRad * (150.678 * $julian_centuries)) + 50 * cos($kDegRad * 21.02 + $kDegRad * (2281.226 * $julian_centuries)) + 45 * cos($kDegRad * 247.54 + $kDegRad * (29929.562 * $julian_centuries)) + 44 * cos($kDegRad * 325.15 + $kDegRad * (31555.956 * $julian_centuries)) + 29 * cos($kDegRad * 60.93 + $kDegRad * (4443.417 * $julian_centuries)) + 28 * cos($kDegRad * 155.12 + $kDegRad * (67555.328 * $julian_centuries)) + 17 * cos($kDegRad * 288.79 + $kDegRad * (4562.452 * $julian_centuries)) + 16 * cos($kDegRad * 198.04 + $kDegRad * (62894.029 * $julian_centuries)) + 14 * cos($kDegRad * 199.76 + $kDegRad * (31436.921 * $julian_centuries)) + 12 * cos($kDegRad * 95.39 + $kDegRad * (14577.848 * $julian_centuries)) + 12 * cos($kDegRad * 287.11 + $kDegRad * (31931.756 * $julian_centuries)) + 12 * cos($kDegRad * 320.81 + deg2rad((34777.259 * $julian_centuries))) + 9 * cos($kDegRad * 227.73 + deg2rad((1222.114 * $julian_centuries))) + 8 * cos(deg2rad(15.45) + deg2rad((16859.074 * $julian_centuries)));
        
        return ($jden + (0.00001 * $S / $lambda));
    }
    
    /**
     * aeaster - Astronomical Easter
     * Returns the Julian Day Easter occurs on as an astronomical event.
     * *Notes: 1981 and 2019 still wrong.
     * Easter is defined as the first Sunday AFTER the first full moon ON or AFTER the Vernal Equinox, thus, it can
     * ONLY occur in March or April, subtract 46 days from Easter to find Ash Wednesday. Lent is 40 days + 6 Sundays
     *
     * @param (int) year to compute Easter in
     * @return (double) Julian day of Easter
     * @uses julian2date, equinox_solstice, moonphase, day_of_week
     */
    function aeaster($inyear) {
        // short m;
        // double d;
        // int y;
        // double yd;
        // double moon;
        
        /**
         * calculate the vernal equinox for the given year
         */
        $equinox = self::zero_hour_julian(self::equinox_solstice($inyear, 0/*March*/));
        
        /**
         * find the first full moon ON or AFTER the equinox
         */
        $y = self::julian_to_date($equinox);
        // echo "<tr><td>Vernal Equinox " . date("d M Y", mktime(0, 0, 0, $y["month"], $y["day"], $y["year"]));
        $yd = $y["year"];
        
        $moon = self::zero_hour_julian(self::moon_phase($yd, Moonphases::fullmoon));
        $tmp = self::julian_to_date($moon);
        // echo "<tr><td>moon1 " . date("d M Y", mktime(0, 0, 0, $tmp["month"], $tmp["day"], $tmp["year"]));
        
        while ( $moon < $equinox ) {
            $yd += 0.04;
            $moon = self::zero_hour_julian(self::moon_phase($yd, Moonphases::fullmoon));
        }
        $tmp = self::julian_to_date($moon);
        // echo "<tr><td>moon2 " . date("d M Y", mktime(0, 0, 0, $tmp["month"], $tmp["day"], $tmp["year"]));
        
        /**
         * find the first Sunday AFTER the full moon
         */
        $moon++;
        while ( self::day_of_week($moon) != 0 ) {
            $moon++;
        }
        
        return $moon;
    }
    
    /**
     * Julian2Date
     * Converts a Julian Day to a Gregorian month, day and year.
     * Meeus, Jean. "Astronomical Algorithms, 1st ed." Willmann-Bell. Inc. 1991. pp. 63
     *
     * @param JD (double) input Julian Day
     * @return array bool $month, $day, $year )
     *         0 if error occured in calculation.
     *         *Notes: does not work for negative Julian Day values but does work for negative years
     */
    function julian_to_date($JD) {
        
        // double A,B,C,D,E,F,J,Z;
        // double alpha;
        $J = $JD + 0.5;
        
        $Z = floor($J);
        
        $F = $J - $Z;
        
        if ($Z >= 2299161) {
            $alpha = floor(($Z - 1867216.25) / 36524.25);
            $A = $Z + 1 + $alpha - floor($alpha / 4);
        } else
            $A = $Z;
        
        $B = $A + 1524;
        
        $C = floor(($B - 122.1) / 365.25);
        
        $D = floor(365.25 * $C);
        
        $E = floor(($B - $D) / 30.6001);
        
        $JU["day"] = $B - $D - floor(30.6001 * $E) + $F;
        
        if ($E < 14) $JU["month"] = ($E - 1.0);
        else if ($E == 14 || $E == 15) $JU["month"] = ($E - 13.0);
        else return 0; /* error */
        
        if ($JU["month"] > 2) $JU["year"] = ($C - 4716.0);
        else if ($JU["month"] == 1 || $JU["month"] == 2) $JU["year"] = ($C - 4715.0);
        else return 0; /* error */
        
        return $JU;
        // return mktime(0,0,0,$month_m,$day_m,$year_m);
    }
    
    /**
     * ZeroHourJulian
     * Returns the JD value at 0 hour (midnight) of the given input Julian Day
     *
     * @param (double) $JD Julian Day for day to calculate Julian Day value at midnight of the input day.
     *        *Notes: This function is usful for some AstroAlgo functions such as RiseSetTrans
     */
    function zero_hour_julian($JD) {
        return floor($JD - 0.5) + 0.5;
    }
    
    /**
     * day_of_week
     * Returns what day of the week the input Julian Day is.
     * *Notes: tested, 2451959.50 -> 1 for Monday
     *
     * @param (double) j input Julian Day
     * @return (int) day of the week
     *         0 = Sunday...6 = Saturday
     * @uses ZeroHourJulian().
     */
    function day_of_week($j) {
        return ( int ) (self::zero_hour_julian($j) + 1.5) % 7;
    }

// Topocentric Moon Monthly Calendar
// Keith Burnett (kburnett@btinternet.com)
// http://www2.arnes.si/~gljsentvid10/tmoon.c

// Overview
// --------

// This program calculates the approximate values of various numbers
// of interest to lunar observers for a month at a time. I have
// written the program using 'standard' C code and libraries.

// For each day in the month, the program calculates the time of
// Moon transit, and then calculates for that time the optical
// libration of the Moon in selenographical longitude and latitude,
// the position angle of the Moon's rotation axis, the selenographic
// colongitude and latitude of the sub-Solar point, i.e. the point
// on the Moon's surface where the Sun is overhead, the position
// angle of the bright limb of the Moon (just add and subtract 90
// degrees from this to get the position angles of the 'horns' of
// the crescent), and finally the percentage illumination of the
// Moon. The program also adds a character after the transit time
// indicating the state of the Sun, blank for night, 'a', 'n', 'c'
// for astronomical, nautical or civil twilight, and '*' if the Sun
// is above the horizon at the time of lunar transit. A further
// character is printed after the colongitude, an 'r' shows that the
// Sun is rising on the Moon, and an 's' indicates a waning Moon.

// Topocentric positions are calculated to your position on the Earth.
// Geocentric positions are calculated to the centre of the Earth. There
// is an appreciable difference for objects that are near to us, so that
// your displacement from the centre of the Earth changes your angle to
// the object by a significant amount.

// The quantities here are calculated using topocentric positions for the
// Moon and geocentric positions for the Sun, so that the resulting
// values should represent the Moon's face that you will see in your
// eyepiece fairly well. As the Moon is roughly 60 Earth radii away,
// the topocentric correction has a large effect on the Moon's
// position in your sky (sometimes up to one degree). The Sun is
// roughly 140,000 Earth radii away, so I assume that the
// topocentric correction to the Sun's position is so small as to
// have no appreciable effect on the illumination of the Moon's
// face.

// Your location moves round the Earth's rotation axis in a 'small
// circle' once a day, and the radius of the small circle depends on
// your latitude. The geocentric optical libration changes on a
// scale of the lunar month, as it arises in part from the
// elliptical nature of the Moon's orbit around the Earth. The size
// of the difference between the topocentric and geocentric
// librations will therefore change during a day as you move round
// your small circle. There will also be a 'constant' or more slowly
// changing topocentric correction depending on your latitude and
// the declination of the Moon. I imagine a smooth slow cycle with a
// small amplitude fast 'wiggle' superimposed on it from the
// topocentric librations. I have a strong suspicion that the
// diurnal component of the topocentric correction to the Moon's
// position is actually zero when the Moon transits, as at that
// time the centre of the Moon, you and the centre of the Earth are
// in a plane that includes your meridian.

// Most published books and articles about Moon observation will
// record the geocentric librations of the Moon, and these will be
// different from the figures produced by this program.

// Accuracy
// --------

// I checked a modified version of this program calculating
// quantities for 0h UT each day against a topocentric ephemeris
// generated using the JPL Horizons online ephemeris generator.
// Quantities agreed to within 0.03 degrees for optical librations
// (as might be expected as that is the level at which the physical
// librations become important) and to within better than 0.005 for
// the colongitude. I chose only one month to check, and a graph of
// the colongitude errors looked as if there might be a periodic
// term of amplitude about 0.02 degrees or so. All output has been rounded
// to one decimal place for the selenographic coordinates, and whole
// degrees for the position angles.

// An earlier version of the program used the D46 formulas (0.3
// degrees error in position) for the Moon coordinates - and I found
// the errors in librations were three times as bad. The current moonpos()
// function is good to 2 arcmin 'most of the time', and 5 arcmin
// worst case (odd spikes in the error signal over a complete
// saros).

// Algorithm
// ---------

// The 'logic' of the main() function looks a bit like this:

// Get inputs
// Do some error checking
// print title and column headings
// for each day in the month
// find the time of lunar transit using an iteration loop
// if no transit, print message and skip to next day
// find the days since J2000.0 corresponding to this time
// calculate the ecliptic coordinates of the Sun
// convert to equatorial coordinates
// find the sun's altitude
// code sun's altitude using a character ' anc*'
// find the ecliptic coordinates of the Moon
// convert to equatorial coordinates
// find altitude of Moon
// if Moon below horizon at transit print message and skip to next day
// apply topocentric correction to Moon equatorial coords
// convert back to get topo ecliptic coords
// find librations and pa of pole axis
// find approximate heliocentric coordinates of Moon
// use libration procedure to find coords of sub-solar point
// find if Sun rising 'r' or setting 's' over Moon
// find pa of bright limb using topocentric Moon coords
// find % illumination
// print line of output for that day
// next day
// print key to table
// end

// I have used 'pass by reference' a lot using pointers in the
// functions to get round the limitation that C functions can only
// return a single argument. Perhaps some structures for collections
// of coordinates would be more elegant.

// Command line
// ------------

// The program takes a command line with four purely numerical
// arguments,

// - the year and month in the format yyyymm,
// - your time zone as a decimal number of hours before or after
// Greenwich, before Greenwich taken as negative
// - your longitude as a signed integer in the format +/-dddmm with
// West longitudes taken as negative,
// - your latitude as a signed integer in the format +/-ddmm with
// North latitudes taken as positive.

// For example, the command line

// >tmoon 200009 0 -00155 5230 > sept.txt

// will calculate a monthly Moon calendar for September 2000, in
// time zone 0 (UT) for longitude 1 deg 55 minutes West, and 52 deg
// 30 minutes North. In this example, the symbol '>' tells the
// operating system to 're-direct' the program output to a file
// called 'sept.txt'. Redirection of output depends on the compiler
// and operating system you are using - most support this feature.
// Error messages are printed to the console using fprintf(stderr,
// stuff).

// References
// ----------

// The formulas for the Moon's geocentric coordinates, the siderial
// time, days since J2000.0, coordinate transformations and all the
// formulas for calculating the output quantities were modified and
// simplified from Jean Meeus' book 'Astronomical Algorithms' (1st
// Edition) published by Willmann-Bell, ISBN 0-943396-35-2. Chapter
// 51 was especially useful, but I have neglected the physical
// libration (or 'free space motion') of the Moon and the nutation,
// resulting in a simplification of the formulas.

// The formulas for the Sun's geocentric coordinates, and the common
// sense topocentric correction for the Moon's position (ignoring
// the polar flattening of Earth), were taken from the Astronomical
// Almanac pages C24 and D46. The iterative scheme to find the time
// of Moon transit each day was adapted from the 'Explanatory
// Supplement to the Astronomical Almanac', 1994, section 9.31 and
// 9.32.

// Mealy Mouthed Disclaimer
// ------------------------

// This program was written as a bit of a giggle, and to improve
// my own understanding of grunge C and selenography. I wouldn't trust
// it for anything important if I were you. I take no responsibility
// for anything that might happen as a consequence of your use of this
// program or any bit of it for anything anywhere at anytime.

// I would like to hear from you if you find a mistake, or find a
// nice new application for bits of the code.
    /*
     * // nclude <stdio.h> // nclude <stdlib.h> // nclude <math.h> // efine RADS 0.0174532925199433 // efine DEGS
     * 57.2957795130823 // efine TPI 6.28318530717959 /* ratio of earth radius to astronomical unit
     */
    // efine ER_OVER_AU 0.0000426352325194252
    
    /* all prototypes here */
    
    // double getcoord(int coord);
    // void getargs(int argc, char *argv[], int *y, int *m, double *tz, double *glong, double *glat);
    // double range(double y);
    // double rangerad(double y);
    // double days(int y, int m, int dn, double hour);
    // void moonpos(double, double *, double *, double *);
    // void sunpos(double , double *, double *, double *);
    // double moontransit(int y, int m, int d, double timezone, double glat, double glong, int *nt);
    // double atan22(double y, double x);
    // double epsilon(double d);
    // void equatorial(double d, double *lon, double *lat, double *r);
    // void ecliptic(double d, double *lon, double *lat, double *r);
    // double gst(double d);
    // void topo(double lst, double glat, double *alp, double *dec, double *r);
    // double alt(double glat, double ha, double dec);
    // void libration(double day, double lambda, double beta, double alpha, double *l, double *b, double *p);
    // void illumination(double day, double lra, double ldec, double dr, double sra, double sdec, double *pabl, double
    // *ill);
    // int daysinmonth(int y, int m);
    // int isleap(int y);
    
    // static const char
    // *usage = " Usage: tmoon date[yyyymm] timz[+/-h.hh] long[+/-dddmm] lat[+/-ddmm]\n"
    // "example: tmoon 200009 0 -00155 5230\n";
    
    // static const char
    // *keytext = "\n\nKey to column headings\n"
    // "----------------------\n\n"
    // " DY: Date in the month\n"
    // "TRAN: Zone time of Moon transit, followed by a letter indicating\n"
    // " the sun state, * means the Sun is up, blank is full night\n"
    // " and c, n, a stand for civil, nautical and astronomical twilights\n"
    // " ALT: Altitude of the Moon at transit\n"
    // " L: Optical libration in longitude\n"
    // " B: Libration in latitude\n"
    // " PaX: Position angle of Moon's polar axis\n"
    // " Co: Colongitude of the Sun, r means sunrise, s means lunar sunset\n"
    // " Bs: Selenographic latitude of the subsolar point\n"
    // "PaBl: Position angle of the bright limb\n"
    // " %%: Percentage illumination of Moon's earth-facing disc\n";
    
    // int main(int argc, char *argv[])
    // {
    
    // double mlambda, mbeta, mrv, slambda, sbeta, srv;
    // double tlambda, tbeta, trv;
    // double tz, dt, glong, glat, time;
    // double lst, sunalt, shr, day, Co;
    // double salpha, sdelta, malpha, mdelta, mhr, moonalt, l, b;
    // double ls, bs, hlambda, hbeta, dratio, paxis, dummy, pabl, ill;
    // int nt = 0, date, hour, min, y, m, longitude, latitude;
    // char sunchar, moonchar, eastwest, northsouth;
    
    // /* get the date, time zone, and observer's position */
    // getargs(argc, argv, &y, &m, &tz, &glong, &glat);
    
    // /* print title and column headings */
    
    // printf("Topocentric lunar ephemeris\n");
    // printf("Year: %04d Month: %02d\n", y, m);
    
    // longitude = atoi(argv[3]);
    // latitude = atoi(argv[4]);
    // if (longitude < 0) eastwest = 'W';
    // else eastwest = 'E';
    // if (latitude < 0) northsouth = 'S';
    // else northsouth = 'N';
    // printf("Location: %05d%1c, %04d%1c\n\n", abs(longitude), eastwest, abs(latitude), northsouth);
    
    // printf("DY TRAN ALT L B PaX Co Bs PaBL %%\n");
    // printf("-------------------------------------------------------\n");
    
    // /* this is the main month loop - generates each line in the table */
    
    // for(date = 1; date <= daysinmonth(y, m); date++) {
    
    // /* find the time of transit on this day */
    
    // dt = moontransit(y, m, date, tz, glat, glong, &nt);
    // if (nt == 1) {
    // printf("%02d ---- no transit this day\n", date);
    // /* skip to next day */
    // continue;
    // }
    // day = days(y, m , date, dt * DEGS / 15) - tz/24;
    // lst = gst(day) + glong;
    
    // /* find Moon topocentric coordinates for libration calculations
    // and check that the Moon is above the horizon at transit */
    
    // moonpos(day, &mlambda, &mbeta, &mrv);
    // malpha = mlambda;
    // mdelta = mbeta;
    // equatorial(day, &malpha, &mdelta, &mrv);
    // topo(lst, glat, &malpha, &mdelta, &mrv);
    // mhr = rangerad(lst - malpha);
    // moonalt = alt(glat, mhr, mdelta);
    // if (moonalt < 0 * RADS) {
    // printf(" %02d **** moon below horizon all day\n", date);
    // /* skip to next day */
    // continue;
    // }
    
    // /* find sun altitude character */
    
    // sunpos(day, &slambda, &sbeta, &srv);
    // salpha = slambda;
    // sdelta = sbeta;
    // equatorial(day, &salpha, &sdelta, &srv);
    // shr = rangerad(lst - salpha);
    // sunalt = alt(glat, shr, sdelta);
    // if (sunalt > 0 * RADS) sunchar = '*';
    // if (sunalt < 0 * RADS) sunchar = 'c';
    // if (sunalt < - 6 * RADS) sunchar = 'n';
    // if (sunalt < -12 * RADS) sunchar = 'a';
    // if (sunalt < -18 * RADS) sunchar = ' ';
    
    // /* Optical libration and Position angle of the Pole */
    
    // tlambda = malpha;
    // tbeta = mdelta;
    // trv = mrv;
    // ecliptic(day, &tlambda, &tbeta, &trv);
    // libration(day, tlambda, tbeta, malpha, &l, &b, &paxis);
    
    // /* Selen Colongitude and latitude of sub solar point */
    
    // dratio = mrv / srv * ER_OVER_AU;
    // hlambda = slambda + PI + dratio * cos(mbeta) * sin(slambda - mlambda);
    // hbeta = dratio * mbeta;
    // libration(day, hlambda, hbeta, salpha, &ls, &bs, &dummy);
    // ls = rangerad(ls);
    // if(ls < 90 * RADS) Co = 90 * RADS - ls;
    // else Co = 450 * RADS - ls;
    // if(Co < 90 * RADS || Co > 270 * RADS) moonchar = 'r';
    // else moonchar = 's';
    
    // /* PA of bright limb, and percentage illumination */
    
    // illumination(day, malpha, mdelta, dratio, salpha, sdelta, &pabl, &ill);
    
    // /* convert transit time to hhmm */
    
    // time = dt * DEGS / 15;
    // hour = floor(time);
    // min = floor((time - hour) * 60 + 0.5);
    
    // /* Print the line for this day */
    
    // printf("%02d %02d%02d", date, hour, min);
    // printf("%1c ", sunchar);
    // printf("%3.0f ", moonalt * DEGS);
    // printf("% 3.1f % 2.1f ", l * DEGS, b * DEGS);
    // printf("%3.0f ", paxis * DEGS);
    // printf("%5.1f%1c % 2.1f ", Co * DEGS, moonchar, bs * DEGS);
    // printf("%4.0f %3.0f\n", pabl * DEGS, ill * 100);
    // }
    // /* end of day in Month loop */
    
    // printf(keytext);
    // exit(EXIT_SUCCESS);
    // }
    // /* end of main() */
    
    // /*
    // getargs() gets the arguments from the command line, does some basic error
    // checking, and converts arguments into numerical form. Arguments are passed
    // back in pointers. Error messages print to stderr so re-direction of output
    // to file won't leave users blind. Error checking prints list of all errors
    // in a command line before quitting.
    // */
    // void
    // getargs(int argc, char *argv[], int *y, int *m, double *tz, double *glong, double *glat) {
    
    // int date, latitude, longitude;
    // int mflag = 0, yflag = 0, longflag = 0, latflag = 0, tzflag = 0;
    // int longminflag = 0, latminflag = 0, dflag = 0;
    
    // /* if not right number of arguments, then print example command line */
    
    // if (argc !=5) {
    // fprintf(stderr, usage);
    // exit(EXIT_FAILURE);
    // }
    
    // date = atoi(argv[1]);
    // *y = date / 100;
    // *m = date - *y * 100;
    // *tz = (double) atof(argv[2]);
    // longitude = atoi(argv[3]);
    // latitude = atoi(argv[4]);
    // *glong = RADS * getcoord(longitude);
    // *glat = RADS * getcoord(latitude);
    
    // /* set a flag for each error found */
    
    // if (*m > 12 || *m < 1) mflag = 1;
    // if (*y > 2500) yflag = 1;
    // if (date < 150001) dflag = 1;
    // if (fabs((float) *glong) > 180 * RADS) longflag = 1;
    // if (abs(longitude) % 100 > 59) longminflag = 1;
    // if (fabs((float) *glat) > 90 * RADS) latflag = 1;
    // if (abs(latitude) % 100 > 59) latminflag = 1;
    // if (fabs((float) *tz) > 12) tzflag = 1;
    
    // /* print all the errors found */
    
    // if (dflag == 1) {
    // fprintf(stderr, "date: dates must be in form yyyymm, gregorian, and later than 1500 AD\n");
    // }
    // if (yflag == 1) {
    // fprintf(stderr, "date: too far in future - accurate from 1500 to 2500\n");
    // }
    // if (mflag == 1) {
    // fprintf(stderr, "date: month must be in range 0 to 12, eg - August 2000 is entered as 200008\n");
    // }
    // if (tzflag == 1) {
    // fprintf(stderr, "timz: must be in range +/- 12 hours, eg -6 for Chicago\n");
    // }
    // if (longflag == 1) {
    // fprintf(stderr, "long: must be in range +/- 180 degrees\n");
    // }
    // if (longminflag == 1) {
    // fprintf(stderr, "long: last two digits are arcmin - max 59\n");
    // }
    // if (latflag == 1) {
    // fprintf(stderr, " lat: must be in range +/- 90 degrees\n");
    // }
    // if (latminflag == 1) {
    // fprintf(stderr, " lat: last two digits are arcmin - max 59\n");
    // }
    
    // /* quits if one or more flags set */
    
    // if (dflag + mflag + yflag + longflag + latflag + tzflag + longminflag + latminflag > 0) {
    // exit(EXIT_FAILURE);
    // }
    
    // }
    
    /**
     * coordinates in decimal degrees
     * coord as a ddmm value stored in an integer.
     *
     * @return coordinates
     * @param
     */
    // double
    function getcoord($coord) {
        $west = 1;
        // double glg, deg;
        if ($coord < 0) $west = -1;
        $glg = abs(( double ) $coord / 100);
        $deg = floor($glg);
        $glg = $west * ($deg + ($glg - $deg) * 100 / 60);
        return ($glg);
    }
    
    /**
     * *Assumes Gregorian calendar.
     * TODO: PP $step is decimal hours....
     *
     * @param $date
     * @return the number of days since J2000.0.
     */
    // double
    function days($date) {
        // http://www.giss.nasa.gov/tools/mars24/help/algorithm.html
        // J2000.0 (ou J2000), désigne le jour julien 2 451 545.0 TT, soit le 1er janvier 2000 dans le calendrier
        // grégorien, à 11 h 58 min 55,816 s UTC (11 h 59 min 27,816 s TAI)
        
        /**
         * A-2.
         * Convert millis to Julian Date (UT).
         * Although there's plenty of sample code available on-line which demonstrates how to convert a Gregorian
         * calendar date to a Julian Date, we simply use the offset from a known, recent Julian Date. Again, we use the
         * Unix epoch, 00:00:00 on Jan. 1, 1970.
         */
        $JDUT = 2440587.5 + (date("U", $date) / (86400)); // s/day);
        /**
         * A-3.
         * Determine time offset from J2000 epoch (UT).
         * This step is optional; we only need to make this calculation if the date is before Jan. 1, 1972. Determine
         * the elapsed time in Julian centuries since 12:00 on Jan. 1, 2000 (UT).
         */
        $T = ($JDUT - 2451545.0) / 36525.;
        /**
         * A-4.
         * Determine UTC to TT conversion. (Replaces AM2000, eq. 27)
         * Terrestrial Time (TT) advances at constant rate, as does UTC, but no leap seconds are inserted into it and so
         * it gradually gets further ahead of UTC. The best way to determine the difference between TT and UTC is to
         * consult a table of leap seconds. Alternatively, one could try to use an empirical formula.
         * In Mars24 we, oddly enough, use both methods. We use the USNO table for dates after Jan. 1, 1972, and a
         * formula for dates prior to then. In consulting the USNO table, however, it is important to note that the
         * table provides values for the TAI-UTC difference, where TAI is International Atomic Time. To obtain the
         * TT-UTC difference, add 32.184 seconds to the value of TAI-UTC. For example, the USNO table indicates that on
         * Jan. 1, 2006, the TAI-UTC value is 33.0 seconds, and thus, the value for TT-UTC on that date (and until the
         * next date on which another leap second is added to the clock) would be 33.0s + 32.184s = 65.184s.
         * The formula applied for dates prior to Jan. 1, 1972, is similar to AM2000, eq. 27, but has been revised and
         * includes additional terms:
         */
        $TT_UTC = 64.184 /*s*/ + 59 /*s*/ * $T; // - 51.2 /*s*/ * $T2 - 67.1 /*s*/ * $T3 - 16.4 /*s*/ * $T4;
        /**
         * (Note: Mars24 uses the USNO table which includes the leap second added Jan.
         * 1, 2006. Obviously, then, it does not allow for any leap seconds which might be subsequently added. Bulletin
         * C 33 from the IERS Earth Orientation Centre indicates this will not occur any earlier than Jan. 1, 2009.)
         * A-5. Determine Julian Date (TT).
         */
        $JDTT = $JDUT + ($TT_UTC) / 86400; // s·day-1]
        
        /**
         * A-6.
         * Determine time offset from J2000 epoch (TT). (AM2000, eq. 15)
         */
        $DtJ2000 = $JDTT - 2451545.0;
        // Jan. 4, 2004 (UTC)
        // Eq. Parameter Value
        // A-1 millis 1073137591000 ms
        // A-2 JDUT 2453008.07397
        // A-3 T —
        // A-4 TT - UTC 64.184 s
        // A-5 JDTT 2453008.07471
        // A-6 DtJ2000 1463.07471
        
        return $DtJ2000;
        
        // The lines below work from 1900 march to feb 2100
        $a = 367 * $y - 7 * ($y + ($m + 9) / 12) / 4 + 275 * $m / 9 + $d;
        $day = $a - 730531.5 + $hour / 24;
        
        // These lines work for any Gregorian date since 0 AD
        if ($m == 1 || $m == 2) {
            $m += 12;
            $y -= 1;
        }
        $a = $y / 100;
        $b = 2 - $a + $a / 4;
        $day = floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $d + $b - 1524.5 - 2451545 + $h / 24;
        return ($day);
    }
    
    /**
     * This function is within a couple of arcminutes most of the time, and is truncated from the Meeus Ch45 series,
     * themselves truncations of ELP-2000.
     * Terms have been written out explicitly rather than using the table based method as only a small number of terms
     * is retained.
     *
     * @return array ($lambda, $beta, $rvet) ecliptic coordinates of moon and moon distance in earth radii.
     * @internal $J2000
     */
    // void
    function moonpos(/*$day*//*, $lambda, $beta, $rvec*/) {
        // double dl, dB, dR, L, D, M, M1, F, e, lm, bm, rm, t;
        $t = self::GetJ2000()/*$day*/ / 36525;
        
        $L = deg2rad(218.3164591 + 481267.88134236 * $t);
        $D = deg2rad(297.8502042 + 445267.1115168 * $t);
        $M = deg2rad(357.5291092 + 35999.0502909 * $t);
        $M1 = deg2rad(134.9634114 + 477198.8676313 * $t - .008997 * $t * $t);
        $F = deg2rad(93.27209929999999 + 483202.0175273 * $t - .0034029 * $t * $t);
        $e = 1 - .002516 * $t;
        
        $dl = 6288774 * sin($M1);
        $dl += 1274027 * sin(2 * $D - $M1);
        $dl += 658314 * sin(2 * $D);
        $dl += 213618 * sin(2 * $M1);
        $dl -= $e * 185116 * sin($M);
        $dl -= 114332 * sin(2 * $F);
        $dl += 58793 * sin(2 * $D - 2 * $M1);
        $dl += $e * 57066 * sin(2 * $D - $M - $M1);
        $dl += 53322 * sin(2 * $D + $M1);
        $dl += $e * 45758 * sin(2 * $D - $M);
        $dl -= $e * 40923 * sin($M - $M1);
        $dl -= 34720 * sin($D);
        $dl -= $e * 30383 * sin($M + $M1);
        $dl += 15327 * sin(2 * $D - 2 * $F);
        $dl -= 12528 * sin($M1 + 2 * $F);
        $dl += 10980 * sin($M1 - 2 * $F);
        $lm = ($L + deg2rad($dl / 1000000));
        
        $dB = 5128122 * sin($F);
        $dB += 280602 * sin($M1 + $F);
        $dB += 277693 * sin($M1 - $F);
        $dB += 173237 * sin(2 * $D - $F);
        $dB += 55413 * sin(2 * $D - $M1 + $F);
        $dB += 46271 * sin(2 * $D - $M1 - $F);
        $dB += 32573 * sin(2 * $D + $F);
        $dB += 17198 * sin(2 * $M1 + $F);
        $dB += 9266 * sin(2 * $D + $M1 - $F);
        $dB += 8822 * sin(2 * $M1 - $F);
        $dB += $e * 8216 * sin(2 * $D - $M - $F);
        $dB += 4324 * sin(2 * $D - 2 * $M1 - $F);
        $bm = deg2rad($dB / 1000000);
        
        $dR = -20905355 * cos($M1);
        $dR -= 3699111 * cos(2 * $D - $M1);
        $dR -= 2955968 * cos(2 * $D);
        $dR -= 569925 * cos(2 * $M1);
        $dR += $e * 48888 * cos($M);
        $dR -= 3149 * cos(2 * $F);
        $dR += 246158 * cos(2 * $D - 2 * $M1);
        $dR -= $e * 152138 * cos(2 * $D - $M - $M1);
        $dR -= 170733 * cos(2 * $D + $M1);
        $dR -= $e * 204586 * cos(2 * $D - $M);
        $dR -= $e * 129620 * cos($M - $M1);
        $dR += 108743 * cos($D);
        $dR += $e * 104755 * cos($M + $M1);
        $dR += 79661 * cos($M1 - 2 * $F);
        $rm = 385000.56 + $dR / 1000;
        
        $moonpos['lambda'] = $lm;
        $moonpos['beta'] = $bm;
        // /* distance to Moon must be in Earth radii */
        $moonpos['rvec'] = $rm / 6378.14;
        return $moonpos;
    }
    
    /**
     * Expects Moon-Earth distance in Earth radii.
     * *Notes: Formulas scavenged from Astronomical Almanac 'low precision formulae for Moon position' page D46.
     *
     * @param local siderial time
     * @param the geographical latitude of the observer equatorial coordinates.
     * @return array ( $alp, $dec, $rvec ) geocentric coordinates with topocentric coordinates on a simple spherical
     *         earth model (no polar flattening).
     */
    
    // void
    function topo($lst, $alp, $dec, $rvec) {
        // double x, y, z, r1;
        $x = $rvec * cos($dec) * cos($alp) - cos(self::GetLatitude()) * cos($lst);
        $y = $rvec * cos($dec) * sin($alp) - cos(self::GetLatitude()) * sin($lst);
        $z = $rvec * sin($dec) - sin($glat);
        $r1 = sqrt($x * $x + $y * $y + $z * $z);
        $topo['alpha'] = self::atan22($y, $x);
        $topo['delta'] = asin($z / $r1);
        $topo['rvec'] = $r1;
        return $topo;
    }
// Location: W 42°42'40.0", N 2°51'30.0", 54m
// (Longitude referred to Greenwich meridian)

// Time Zone: 2h 00m east of Greenwich

// Date Rise Az. Transit Alt. Set Az.
// (Zone)
// h m ° h m ° h m °
// 2014 Jun 09 (Mon) 19:50 102 01:09 77S 07:19 260
// 2014 Jun 10 (Tue) 20:43 105 01:59 74S 08:09 256
// 2014 Jun 11 (Wed) 21:39 108 02:52 71S 09:02 253
// 2014 Jun 12 (Thu) 22:38 109 03:49 69S 09:59 251
// 2014 Jun 13 (Fri) 23:39 109 04:49 68S 10:59 251
// 2014 Jun 14 (Sat) 05:49 68S 12:00 252
// 2014 Jun 15 (Sun) 00:39 107 06:50 70S 13:00 254
// 2014 Jun 16 (Mon) 01:37 104 07:48 73S 13:59 257
// 2014 Jun 17 (Tue) 02:33 101 08:44 77S 14:55 262
// 2014 Jun 18 (Wed) 03:26 96 09:38 82S 15:49 266
// 2014 Jun 19 (Thu) 04:17 92 10:29 87S 16:41 271
// 2014 Jun 20 (Fri) 05:07 87 11:19 89N 17:32 275
// 2014 Jun 21 (Sat) 05:55 82 12:08 84N 18:22 280
// 2014 Jun 22 (Sun) 06:44 79 12:58 80N 19:12 283
// 2014 Jun 23 (Mon) 07:32 75 13:48 77N 20:03 286
// 2014 Jun 24 (Tue) 08:22 73 14:38 75N 20:54 288
// 2014 Jun 25 (Wed) 09:12 71 15:28 74N 21:44 289
// 2014 Jun 26 (Thu) 10:02 71 16:18 74N 22:34 289
// 2014 Jun 27 (Fri) 10:51 71 17:07 74N 23:22 288
// 2014 Jun 28 (Sat) 11:39 73 17:54 76N
// 2014 Jun 29 (Sun) 12:26 75 18:40 78N 00:09 286
// 2014 Jun 30 (Mon) 13:12 78 19:25 81N 00:54 284
// 2014 Jul 01 (Tue) 13:56 81 20:08 85N 01:38 281
// 2014 Jul 02 (Wed) 14:40 85 20:51 89N 02:20 277
// 2014 Jul 03 (Thu) 15:23 89 21:34 87S 03:02 273
// 2014 Jul 04 (Fri) 16:07 93 22:17 83S 03:44 269
// 2014 Jul 05 (Sat) 16:52 97 23:02 79S 04:26 265
// 2014 Jul 06 (Sun) 17:39 100 23:49 76S 05:11 261
// 2014 Jul 07 (Mon) 18:30 104 05:58 258
// 2014 Jul 08 (Tue) 19:23 107 00:39 72S 06:48 255
// 2014 Jul 09 (Wed) 20:20 108 01:33 70S 07:42 252
// 2014 Jul 10 (Thu) 21:20 109 02:30 68S 08:40 251
// 2014 Jul 11 (Fri) 22:21 108 03:30 68S 09:41 251
// 2014 Jul 12 (Sat) 23:22 106 04:32 69S 10:43 253
// 2014 Jul 13 (Sun) 05:33 72S 11:44 256
// 2014 Jul 14 (Mon) 00:20 103 06:32 75S 12:43 259
// 2014 Jul 15 (Tue) 01:17 98 07:28 80S 13:40 264
// 2014 Jul 16 (Wed) 02:10 94 08:22 85S 14:35 269
// 2014 Jul 17 (Thu) 03:02 89 09:14 90S 15:27 274
// 2014 Jul 18 (Fri) 03:52 84 10:05 86N 16:19 278
// 2014 Jul 19 (Sat) 04:41 80 10:55 82N 17:10 282
// 2014 Jul 20 (Sun) 05:31 76 11:45 78N 18:00 285
// 2014 Jul 21 (Mon) 06:20 74 12:35 76N 18:51 287
// 2014 Jul 22 (Tue) 07:09 72 13:25 74N 19:41 289
// 2014 Jul 23 (Wed) 07:59 71 14:15 74N 20:31 289
// 2014 Jul 24 (Thu) 08:48 71 15:04 74N 21:19 288
// 2014 Jul 25 (Fri) 09:36 72 15:51 75N 22:06 287
// 2014 Jul 26 (Sat) 10:23 74 16:38 77N 22:52 285
// 2014 Jul 27 (Sun) 11:09 77 17:23 80N 23:36 282
// 2014 Jul 28 (Mon) 11:54 80 18:07 83N
// 2014 Jul 29 (Tue) 12:38 83 18:50 87N 00:19 279
// 2014 Jul 30 (Wed) 13:21 87 19:32 89S 01:01 275
// 2014 Jul 31 (Thu) 14:05 91 20:15 85S 01:42 271
// 2014 Aug 01 (Fri) 14:49 95 20:58 81S 02:24 267
// 2014 Aug 02 (Sat) 15:34 99 21:43 77S 03:07 263
// 2014 Aug 03 (Sun) 16:22 102 22:31 74S 03:52 259
// 2014 Aug 04 (Mon) 17:13 105 23:22 71S 04:40 256
// 2014 Aug 05 (Tue) 18:06 108 05:31 253
// 2014 Aug 06 (Wed) 19:03 109 00:16 69S 06:25 252
// 2014 Aug 07 (Thu) 20:02 109 01:13 68S 07:23 251
// 2014 Aug 08 (Fri) 21:02 107 02:12 68S 08:23 252
// 2014 Aug 09 (Sat) 22:02 104 03:13 70S 09:24 254
// 2014 Aug 10 (Sun) 23:00 101 04:13 73S 10:24 257
// 2014 Aug 11 (Mon) 23:56 96 05:12 77S 11:23 262
// 2014 Aug 12 (Tue) 06:08 82S 12:21 266
    /**
     * * See Explanatory Supplement to Astronomical Almanac
     * section 9.32 and 9.31 for the method.
     *
     * @param date
     * @param time zone
     * @param geographic longitude
     *        of observer
     * @return (decimal hours) the time of lunar transit on that day if there is one, and sets the notransit flag if
     *         there isn't.
     */
    // double
    function moontransit(/*$date*/ /*, int *notransit*/) {
        // double hm, ht, ht1, lon, lat, rv, dnew, lst;
        // int itcount;
        $ht1 = deg2rad(180);
        $ht = 0;
        $itcount = 0;
        // $tz = date("Z", $date) / 3600;
        // *notransit = 0;
        do {
            $ht = $ht1;
            $itcount++;
            self::$step += rad2deg($ht) / 15;
            // $dnew = self::days($date, rad2deg($ht) / 15) - $tz / 24;
            var_dump(self::$step);
            $lst = self::gst($dnew) + self::GetLongitude();
            // /* find the topocentric Moon ra (hence hour angle) and dec */
            $moonpos = self::moonpos($dnew/*, &lon, &lat, &rv*/);
            $equatorial = self::equatorial($dnew, $moonpos['lambda'], $moonpos['beta']/*, &rv*/);
            $topo = self::topo($lst, $equatorial['lon'], $equatorial['lat'], $moonpos['rvec']);
            $hm = ($lst - $topo['alpha']);
            $ht1 = ($ht - $hm);
            // /* if no convergence, then no transit on that day */
            if ($itcount > 30) {
                return true; // *notransit = 1;
                                 // break;
            }
        } while ( abs($ht - $ht1) > deg2rad(0.04) );
        return ($ht1);
    }
    
    /**
     * Calculates the selenographic coordinates of either the sub Earth point (optical libration) or the sub-solar point
     * (selen coords of centre of bright hemisphere).
     * Notes: Based on Meeus chapter 51 but neglects physical libration and nutation, with some simplification of the
     * formulas.
     *
     * @internal days from J2000.0
     * @return array ( $lambda, $beta, $alpha )
     */
    // void
    function libration(/*$day,*/ $lambda, $beta, $alpha/*, double *l, double *b, double *p*/) {
        // double i, f, omega, w, y, x, a, t, eps;
        $t = self::GetJ2000()/*$day*/ / 36525.;
        $i = deg2rad(1.54242);
        $eps = self::epsilon(/*$day*/);
        $f = deg2rad(93.2720993 + 483202.0175273 * $t - .0034029 * $t * $t);
        $omega = deg2rad(125.044555 - 1934.1361849 * $t + .0020762 * $t * $t);
        $w = $lambda - $omega;
        $y = sin($w) * cos($beta) * cos($i) - sin($beta) * sin($i);
        $x = cos($w) * cos($beta);
        $a = self::atan22($y, $x);
        $libration['l'] = $a - $f;
        
        // /* kludge to catch cases of 'round the back' angles */
        if ($libration['l'] < deg2rad(-90)) $libration['l'] += 2 * pi();
        if ($libration['l'] > deg2rad(90)) $libration['l'] -= 2 * pi();
        $libration['b'] = asin(-sin($w) * cos($beta) * sin($i) - sin($beta) * cos($i));
        
        // /* pa pole axis - not used for Sun stuff */
        $x = sin($i) * sin($omega);
        $y = sin($i) * cos($omega) * cos($eps) - cos($i) * sin($eps);
        $w = self::atan22(x, y);
        $libration['p'] = (asin(sqrt($x * $x + $y * $y) * cos($alpha - $w) / cos($libration['b'])));
        return $libration;
    }
    
    /**
     *
     * @param coords Moon
     * @param ratio of moon to sun distance
     * @param eq coords Sun
     *        @Return array ($pabl, $ill) position angle of bright limb wrt NCP, percentage illumination of Sun
     */
    // void
    function illumination(/*$day, */$lra, $ldec, $dr, $sra, $sdec/*, double *pabl, double *ill*/)
     {
        // double x, y, phi, i;
        $y = cos($sdec) * sin($sra - $lra);
        $x = sin($sdec) * cos($ldec) - cos($sdec) * sin($ldec) * cos($sra - $lra);
        $illumination['pabl'] = self::atan22($y, $x);
        $phi = acos(sin($sdec) * sin($ldec) + cos($sdec) * cos($ldec) * cos($sra - $lra));
        $i = self::atan22(sin($phi), ($dr - cos($phi)));
        $illumination['ill'] = 0.5 * (1 + cos($i));
        return $illumination;
    }
    
    /**
     * Latitude is zero at this level of precision, but pointer left in for consistency in number of arguments.
     * *Notes : This function is within 0.01 degree (1 arcmin) almost all the time for a century either side of
     * J2000.0.
     * This is from the 'low precision fomulas for the Sun' from C24 of Astronomical Alamanac
     *
     * @internal days from J2000.0
     * @return array ($lambda, $beta, $rvec) ecliptic longitude of Sun.
     */
    // void
    function sunpos(/*$day*//*, double *lambda, double *beta, double *rvec*/) {
        // double L, g, ls, bs, rs;
        $L = deg2rad(280.461 + .9856474 * self::GetJ2000()/*$day*/);
        $g = deg2rad(357.528 + .9856003 * self::GetJ2000()/*$day*/);
        $ls = $L + deg2rad(1.915 * sin($g) + .02 * sin(2 * $g));
        $bs = 0;
        $rs = 1.00014 - .01671 * cos($g) - .00014 * cos(2 * $g);
        $sunpos['lambda'] = $ls;
        $sunpos['beta'] = $bs;
        $sunpos['rvec'] = $rs;
        return $sunpos;
    }
    
    /**
     * Used to find the Sun's altitude to put a letter code on the transit time, and to find the Moon's altitude
     * at transit just to make sure that the Moon is visible.
     *
     * @return the altitude given the days since J2000.0
     * @param the hour angle and declination of the object and the latitude of the observer.
     * @uses GetLatitude()
     */
    // double
    function alt(/*$glat, */$ha, $dec) {
        return (asin(sin($dec) * sin(self::GetLatitude()) + cos($dec) * cos(self::GetLatitude()) * cos($ha)));
    }
    /**
     *
     * @return the atan2 function returning angles in the right order and range.
     */
    function atan22($y, $x) {
        // double a;
        $a = atan2($y, $x);
        if ($a < 0) $a += (2 * pi());
        return ($a);
    }
    
    /**
     *
     * @internal days from J2000.0
     * @return mean obliquity of ecliptic in radians given days since J2000.0.
     */
    // double
    function epsilon(/*$day*/) {
        $t = self::GetJ2000()/*$day*/ / 36525;
        return ((23.4392911111111 - deg2rad($t * (46.8150 + 0.00059 * $t) / 3600)));
    }
    
    /**
     *
     * @param ecliptic coordinates
     * @return ($lon, $lat) equatorial coordinates.
     *         *note: $rvec is unchanged.
     */
    // void
    function equatorial(/*$d,*/ $lambda, $beta/*, double *r*/) {
        // double eps, ceps, seps, l, b;
        $eps = self::epsilon(/*$d*/);
        $ceps = cos($eps);
        $seps = sin($eps);
        $eq['lon'] = self::atan22(sin($lambda) * $ceps - tan($beta) * $seps, cos($lambda));
        $eq['lat'] = asin(sin($beta) * $ceps + cos($beta) * $seps * sin($lambda));
        return $eq;
    }
    
    /**
     * Replaces equatorial coordinates with ecliptic ones.
     * Inverse of above, but used to find topocentric ecliptic coords.
     */
    // void
    function ecliptic(/*$d,*/ $alp, $dec/*, double *r*/) {
        // double eps, ceps, seps, alp, dec;
        $eps = epsilon(/*$d*/);
        $ceps = cos($eps);
        $seps = sin($eps);
        $eq['lon'] = self::atan22(sin($alp) * $ceps + tan($dec) * $seps, cos($alp));
        $eq['lon'] = asin(sin($dec) * $ceps - cos($dec) * $seps * sin($alp));
        return $eq;
    }
    
    /**
     *
     * @return the siderial time at greenwich meridian as an angle in radians given the days since J2000.0.
     * @internal days from J2000.0
     */
    // double
    function gst(/*$day*/) {
        $t = self::GetJ2000()/*$day*/ / 36525;
        // double theta;
        $theta = (280.46061837 + 360.98564736629 * self::GetJ2000()/*$day*/ + 0.000387933 * $t * $t);
        return (deg2rad($theta));
    }
    static function GetDate() {
        return self::$today;
    }
    static function GetJ2000() {
        return self::$J2000;
    }
    static function GetLatitude() {
        return self::$latitude;
    }
    static function GetLongitude() {
        return self::$longitude;
    }
}
/**
 * enumerations typedef enum moonphases
 */
interface Moonphases {
    const newmoon = 0;
    const firstquarter = 1;
    const fullmoon = 2;
    const lastquarter = 3;
    const TPI = 6.28318530717959;
    
    /* ratio of earth radius to astronomical unit */
    const ER_OVER_AU = 0.0000426352325194252;
}

?>
