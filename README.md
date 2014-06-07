MoonCalc
========

New php class for all moon calculations

REFERENCES: Meeus, Jean. "Astronomical Algorithms, 1st ed." Willmann-Bell. Inc. 1991.

Usage example :
<?=number_format(100 * simple_illumination(), 2)."% "?> is the original getMoonIll() function

<?php $sol = equinox_solstice(2014, 1);
					$ete = julian_to_date($sol);
					$pl  = julian_to_date(moon_phase(2014 + (date("n")-1)/12, 2));
					$pq  = julian_to_date(aeaster(2014));?>
<?="Eté " . date("d M Y", mktime(0, 0, 0, $ete["month"], $ete["day"], $ete["year"]));?>
<?="Pleine lune " . date("d M Y", mktime(0, 0, 0, $pl["month"], $pl["day"], $pl["year"]));?>
<?="Paques " . date("d M Y", mktime(0, 0, 0, $pq["month"], $pq["day"], $pq["year"]));?>
