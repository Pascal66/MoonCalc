<?php
/*
 * REFERENCES: Meeus, Jean. "Astronomical Algorithms, 1st ed." Willmann-Bell. Inc. 1991. pp. 315 DATE/PROGRAMMER/NOTE:
 * 07-31-2001 Todd A. Guillory created Adapted from C to php @author Pascal Péchard
 */
class moon {
    var $today;
    /**
     *
     * @param timestamp $today
     */
    function __construct($today = null) {
        ($today == null) ? $today = time() : $today = today;
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
        /*
         * U.S. Naval Observatory Astronomical Applications Department 2014 Phases of the Moon Universal Time New Moon
         * First Quarter Full Moon Last Quarter d h m d h m d h m d h m Jan 1 11 14 Jan 8 3 39 Jan 16 4 52 Jan 24 5 20
         * Jan 30 21 38 Feb 6 19 22 Feb 14 23 53 Feb 22 17 15 Mar 1 8 00 Mar 8 13 27 Mar 16 17 08 Mar 24 1 46 Mar 30 18
         * 45 Apr 7 8 31 Apr 15 7 42 Apr 22 7 52 Apr 29 6 14 May 7 3 15 May 14 19 16 May 21 12 59 May 28 18 40 Jun 5 20
         * 39 Jun 13 4 11 Jun 19 18 39 Jun 27 8 08 Jul 5 11 59 Jul 12 11 25 Jul 19 2 08 Jul 26 22 42 Aug 4 0 50 Aug 10
         * 18 09 Aug 17 12 26 Aug 25 14 13 Sep 2 11 11 Sep 9 1 38 Sep 16 2 05 Sep 24 6 14 Oct 1 19 32 Oct 8 10 51 Oct 15
         * 19 12 Oct 23 21 57 Oct 31 2 48 Nov 6 22 23 Nov 14 15 15 Nov 22 12 32 Nov 29 10 06 Dec 6 12 27 Dec 14 12 51
         * Dec 22 1 36 Dec 28 18 31
         */
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
        $k = floor(($year - 2000.0) * 12.3685) + ($phase * 0.25);
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
        
        $phase = new Moonphase();
        
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
                                 // $LAT = deg2rad( 42.7136788229883 );
                                 // $LON = deg2rad( 2.84930393099784 );
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
         * sun's mean anomaly @var $double
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
        $S = 485 * cos(deg2rad(324.96) + deg2rad((1934.136 * $julian_centuries))) + 203 * cos(deg2rad(337.23) + deg2rad((32964.467 * $julian_centuries))) + 199 * cos(deg2rad(342.08) + deg2rad((20.186 * $julian_centuries))) + 182 * cos(deg2rad(27.85) + $kDegRad * (445267.112 * $julian_centuries)) + 156 * cos($kDegRad * 73.14 + $kDegRad * (45036.886 * $julian_centuries)) + 136 * cos($kDegRad * 171.52 + $kDegRad * (22518.443 * $julian_centuries)) + 77 * cos($kDegRad * 222.54 + $kDegRad * (65928.934 * $julian_centuries)) + 74 * cos($kDegRad * 296.72 + $kDegRad * (3034.906 * $julian_centuries)) + 70 * cos($kDegRad * 243.58 + $kDegRad * (9037.513 * $julian_centuries)) + 58 * cos($kDegRad * 119.81 + $kDegRad * (33718.147 * $julian_centuries)) + 52 * cos($kDegRad * 297.17 + $kDegRad * (150.678 * $julian_centuries)) + 50 * cos($kDegRad * 21.02 + $kDegRad * (2281.226 * $julian_centuries)) + 45 * cos($kDegRad * 247.54 + $kDegRad * (29929.562 * $julian_centuries)) + 44 * cos($kDegRad * 325.15 + $kDegRad * (31555.956 * $julian_centuries)) + 29 * cos($kDegRad * 60.93 + $kDegRad * (4443.417 * $julian_centuries)) + 28 * cos($kDegRad * 155.12 + $kDegRad * (67555.328 * $julian_centuries)) + 17 * cos($kDegRad * 288.79 + $kDegRad * (4562.452 * $julian_centuries)) + 16 * cos($kDegRad * 198.04 + $kDegRad * (62894.029 * $julian_centuries)) + 14 * cos($kDegRad * 199.76 + $kDegRad * (31436.921 * $julian_centuries)) + 12 * cos($kDegRad * 95.39 + $kDegRad * (14577.848 * $julian_centuries)) + 12 * cos($kDegRad * 287.11 + $kDegRad * (31931.756 * $julian_centuries)) + 12 * cos($kDegRad * 320.81 + deg2rad((34777.259 * $julian_centuries))) + 9 * cos($kDegRad * 227.73 + deg2rad((1222.114 * $julian_centuries))) + 8 * cos(deg2rad(15.45) + deg2rad((16859.074 * $julian_centuries)));
        
        return ($jden + (0.00001 * $S / $lambda));
    }
    
    /**
     * aeaster - Astronomical Easter
     * Returns the Julian Day Easter occurs on as an astronomical event
     *
     * @param year (int) year to compute Easter in
     * @return Julian day (double) of Easter
     *         FUNCTIONS CALLED:
     *         julian2date, equinox_solstice, moonphase, day_of_week
     *         DATE/PROGRAMMER/NOTE:
     *         02-18-2001 Todd A. Guillory started
     *         02-20-2001 Todd A. Guillory 1981 and 2019 still wrong
     *         Notes:
     *         Easter is defined as the first Sunday AFTER the first full moon ON or AFTER
     *         the Vernal Equinox, thus, it can ONLY occur in March or April, subtract
     *         46 days from Easter to find Ash Wednesday. Lent is 40 days + 6 Sundays
     */
    function aeaster($inyear) {
        // short m;
        // double d;
        // int y;
        // double yd;
        // double moon;
        
        /* calculate the vernal equinox for the given year */
        $equinox = zero_hour_julian(equinox_solstice($inyear, 0/*March*/));
        
        /* find the first full moon ON or AFTER the equinox */
        $y = julian_to_date($equinox/*, &m, &d, &y*/);
        // echo "<tr><td>Vernal Equinox " . date("d M Y", mktime(0, 0, 0, $y["month"], $y["day"], $y["year"]));
        $yd = $y["year"];
        
        $moon = zero_hour_julian(moon_phase($yd, Moonphases::fullmoon));
        $tmp = julian_to_date($moon/*, &m, &d, &y*/);
        // echo "<tr><td>moon1 " . date("d M Y", mktime(0, 0, 0, $tmp["month"], $tmp["day"], $tmp["year"]));
        
        while ( $moon < $equinox ) {
            $yd += 0.04;
            $moon = zero_hour_julian(moon_phase($yd, Moonphases::fullmoon));
        }
        $tmp = julian_to_date($moon/*, &m, &d, &y*/);
        // echo "<tr><td>moon2 " . date("d M Y", mktime(0, 0, 0, $tmp["month"], $tmp["day"], $tmp["year"]));
        
        /* find the first Sunday AFTER the full moon */
        $moon++;
        while ( day_of_week($moon) != 0 ) {
            $moon++;
        }
        
        return $moon;
    }
    /**
     * Julian2Date PURPOSE: Converts a Julian Day to a Gregorian month, day and year REFERENCES; Meeus, Jean.
     * "Astronomical Algorithms, 1st ed." Willmann-Bell. Inc. 1991. pp. 63 INPUT ARGUMENTS: JD (double) input Julian Day
     * OUTPUT ARGUMENTS: month (short) Julian Day day (double) day year (int) year RETURNED VALUE: 0 if error occured in
     * calculation 1 if no error GLOBALS USED: none FUNCTIONS CALLED: floor DATE/PROGRAMMER/NOTES: 10-15-1998 Todd A.
     * Guillory created NOTES: does not work for negative Julian Day values but does work for negative years
     */
    function julian_to_date($JD/*, $month, $day, $year*/) {
        
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
        
        $JU["day"] =/* $day_m =*/ $B - $D - floor(30.6001 * $E) + $F;
        
        if ($E < 14) $JU["month"] =/* $month_m =*/ ($E - 1.0);
        else if ($E == 14 || $E == 15) $JU["month"] =/* $month_m =*/ ($E - 13.0);
        else return 0; /* error */
        
        if ($JU["month"] /*= $month_m*/ > 2) $JU["year"] =/* $year_m =*/ ($C - 4716.0);
        else if ($JU["month"] /*= $month_m*/ == 1 || $JU["month"] /*= $month_m*/ == 2) $JU["year"] =/* $year_m =*/ ($C - 4715.0);
        else return 0; /* error */
        
        // return $day_m . " / " . $month_m . " / " . $year_m; // 1;
        // return date("D d M Y", mktime(0,0,0,$month_m,$day_m,$year_m));
        // var_dump($JU);
        return $JU;
        // return mktime(0,0,0,$month_m,$day_m,$year_m);
    }
    
    /**
     * ZeroHourJulian
     * Returns the JD value at 0 hour (midnight) of the given input Julian Day
     *
     * @param JD (double) Julian Day for day to calculate
     *        Julian Day value at midnight of the input day
     *        DATE/PROGRAMMER/NOTE:
     *        09-16-1999 Todd A. Guillory created
     *        NOTES:
     *        This function is usful for some AstroAlgo functions such as RiseSetTrans
     *        ******************************************************************************
     */
    function zero_hour_julian($JD) {
        return floor($JD - 0.5) + 0.5;
    }
    
    /**
     * day_of_week
     * Returns what day of the week the input Julian Day is
     *
     * @param (double) j input Julian Day
     *        RETURNED VALUE:
     *        day of the week
     *        0 = Sunday...6 = Saturday
     *        FUNCTIONS CALLED:
     *        ZeroHourJulian
     *        DATE/PROGRAMMER/NOTE:
     *        02-18-2001 Todd A. Guillory written in ANSI C
     *        02-19-2001 Todd A. Guillory tested, 2451959.50 -> 1 for Monday
     *        ******************************************************************************
     */
    function day_of_week($j) {
        return ( int ) (zero_hour_julian($j) + 1.5) % 7;
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
}

?>
