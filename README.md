MoonCalc
========
Php class for some moon calculations.
Static use is necessary with an include (object isnt declared) (self instead this).
<br>
REFERENCES: Meeus, Jean. "Astronomical Algorithms, 1st ed." Willmann-Bell. Inc. 1991.
<br>
Usage examples :
```
include_once 'modules/meteo/mooncalc.php';
$mooncalc = new moon();
echo number_format(100 * $mooncalc::simple_illumination(), 2)."% "
$sol = $mooncalc::equinox_solstice(2014, 1);
$ete = $mooncalc::julian_to_date($sol);
$pl  = $mooncalc::julian_to_date($mooncalc::moon_phase(2014 + (date("n")-1)/12, 2));
$pq  = $mooncalc::julian_to_date($mooncalc::aeaster(2014));
"Eté " . date("d M Y", mktime(0, 0, 0, $ete["month"], $ete["day"], $ete["year"]));
echo "Pleine lune " . date("d M Y", mktime(0, 0, 0, $pl["month"], $pl["day"], $pl["year"]));
echo "Pâques " . date("d M Y", mktime(0, 0, 0, $pq["month"], $pq["day"], $pq["year"]));
```				
