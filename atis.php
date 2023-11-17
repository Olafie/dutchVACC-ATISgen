<?php
include 'metarv2.php';
include 'trend.php';
$wx = $_GET['wx'];

$ownremarks = $_GET['own'];
if (preg_match('/[A-Z]/', $ownremarks)) {
    $own = " .. " . substr($ownremarks, 0);
} else {
    $own = "";
}

define("info", [
    'A' => 'ALPHA',
    'B' => 'BRAVO',
    'C' => 'CHARLIE',
    'D' => 'DELTA',
    'E' => 'ECHO',
    'F' => 'FOXTROT',
    'G' => 'GOLF',
    'H' => 'HOTEL',
    'I' => 'INDIA',
    'J' => 'JULIET',
    'K' => 'KILO',
    'L' => 'LIMA',
    'M' => 'MIKE',
    'N' => 'NOVEMBER',
    'O' => 'OSCAR',
    'P' => 'PAPA',
    'Q' => 'QUEBEC',
    'R' => 'ROMEO',
    'S' => 'SIERRA',
    'T' => 'TANGO',
    'U' => 'UNIFORM',
    'V' => 'VICTOR',
    'W' => 'WHISKEY',
    'X' => 'X RAY',
    'Y' => 'YANKEE',
    'Z' => 'ZULU',
]);
$cycle = info[$_GET['atis']];

$time = gmdate("Gi");
$timestamp = sprintf('%04d', $time);

if (str_starts_with($ap, "EH")) {
    include 'eh/trl.php';
    if ($ap === "EHAM") {
        define("app", [
            'sra' => ' .. SURVEILLANCE RADAR APPROACH',
            'rnp' => " .. RNP APPROACH",
            '' => '',
        ]);
        $app1 = app[$_GET['apt1']];
        $app2 = app[$_GET['apt2']];

        //Preferential Runway System - Arrivals
        $arr = $_GET['arr'];
        if (preg_match('/[0-3][0-9][CLR],[0-3][0-9][CLR]/', $arr, $arrmatch)) {
            if (str_contains($arrmatch[0], '18R')) {
                $arr_pri = '18R';
                $arr_sec = substr($arrmatch[0], 0, 3);
            } else if (str_contains($arrmatch[0], '36R')) {
                $arr_pri = '36R';
                $arr_sec = substr($arrmatch[0], 0, 3);
            } else {
                $arr_pri = substr($arrmatch[0], 0, 2);
                $arr_sec = substr($arrmatch[0], 4, 3);
            }
            $arrrwy = "MAIN LANDING RUNWAY " . $arr_pri . $app1 . " .. SECONDARY LANDING RUNWAY " . $arr_sec . $app2;
        } else if (preg_match('/[0-3][0-9],[0-3][0-9][CLR]/', $arr, $arrmatch)) {
            if (str_starts_with($arrmatch[0], '06')) {
                $arr_pri = '06';
                $arr_sec = substr($arrmatch[0], 3, 3);
            } else if (str_ends_with($arrmatch[0], '18R')) {
                $arr_pri = '18R';
                $arr_sec = substr($arrmatch[0], 0, 2);
            } else if (str_ends_with($arrmatch[0], '36R')) {
                $arr_pri = '36R';
                $arr_sec = substr($arrmatch[0], 0, 2);
            } else if (str_ends_with($arrmatch[0], '18C')) {
                $arr_pri = '18C';
                $arr_sec = substr($arrmatch[0], 0, 2);
            } else if (str_ends_with($arrmatch[0], '36C')) {
                $arr_pri = '36C';
                $arr_sec = substr($arrmatch[0], 0, 2);
            } else if (str_starts_with($arrmatch[0], '22')) {
                $arr_pri = substr($arrmatch[0], 3, 3);
                $arr_sec = '22';
            } else {
                $arr_pri = substr($arrmatch[0], 0, 2);
                $arr_sec = substr($arrmatch[0], 3, 3);
            }
            $arrrwy = "MAIN LANDING RUNWAY " . $arr_pri . $app1 . " .. SECONDARY LANDING RUNWAY " . $arr_sec . $app2;
        } else if (preg_match('/[0-3][0-9],[0-3][0-9]/', $arr, $arrmatch)) {
            if (str_starts_with($arrmatch[0], '22')) {
                $arr_pri = substr($arrmatch[0], 3, 3);
                $arr_sec = '22';
            } else {
                $arr_pri = substr($arrmatch[0], 0, 2);
                $arr_sec = substr($arrmatch[0], 3, 2);
            }
            $arrrwy = "MAIN LANDING RUNWAY " . $arr_pri . $app1 . " .. SECONDARY LANDING RUNWAY " . $arr_sec . $app2;
        } else {
            $arr_pri = $arr;
            $arrrwy = "MAIN LANDING RUNWAY " . $arr_pri . $app1;
        }

        //Preferential Runway System - Departures
        $dep = $_GET['dep'];
        if (preg_match('/[0-3][0-9][CLR],[0-3][0-9][CLR]/', $dep, $depmatch)) {
            if (str_ends_with($depmatch[0], '36L')) {
                $dep_pri = '36L';
                $dep_sec = substr($depmatch[0], 0, 3);
            } else if (str_contains($depmatch[0], '18L')) {
                $dep_pri = '18L';
                $dep_sec = substr($depmatch[0], 0, 3);
            } else {
                $dep_pri = substr($depmatch[0], 0, 2);
                $dep_sec = substr($depmatch[0], 3, 3);
            }
            $deprwy = "MAIN DEPARTING RUNWAY " . $dep_pri . " .. SECONDARY DEPARTING RUNWAY " . $dep_sec;
        } else if (preg_match('/[0-3][0-9],[0-3][0-9][CLR]/', $dep, $depmatch)) {
            if (str_ends_with($depmatch[0], '36L')) {
                $dep_pri = '36L';
                $dep_sec = substr($depmatch[0], 0, 2);
            } else if (str_starts_with($depmatch[0], '24')) {
                $dep_pri = '24';
                $dep_sec = substr($depmatch[0], 3, 3);
            } else if (str_ends_with($depmatch[0], '18C')) {
                $dep_pri = '18C';
                $dep_sec = substr($depmatch[0], 0, 2);
            } else {
                $dep_pri = substr($depmatch[0], 0, 2);
                $dep_sec = substr($depmatch[0], 3, 3);
            }
            $deprwy = "MAIN DEPARTING RUNWAY " . $dep_pri . " .. SECONDARY DEPARTING RUNWAY " . $dep_sec;
        } else if (preg_match('/[0-3][0-9][CLR],[0-3][0-9]/', $dep, $depmatch)) {
            if (str_starts_with($depmatch[0], '36L')) {
                $dep_pri = '36L';
                $dep_sec = substr($depmatch[0], 0, 2);
            } else if (str_ends_with($depmatch[0], '24')) {
                $dep_pri = '24';
                $dep_sec = substr($depmatch[0], 0, 3);
            } else if (str_starts_with($depmatch[0], '18C')) {
                $dep_pri = '18C';
                $dep_sec = substr($depmatch[0], 0, 2);
            } else {
                $dep_pri = substr($depmatch[0], 0, 3);
                $dep_sec = substr($depmatch[0], 4, 2);
            }
            $deprwy = "MAIN DEPARTING RUNWAY " . $dep_pri . " .. SECONDARY DEPARTING RUNWAY " . $dep_sec;
        } else if (preg_match('/[0-3][0-9],[0-3][0-9]/', $dep, $depmatch)) {
            if (str_ends_with($depmatch[0], '27')) {
                $dep_pri = substr($depmatch[0], 3, 2);
                $dep_sec = '27';
            } else {
                $dep_pri = substr($depmatch[0], 0, 2);
                $dep_sec = substr($depmatch[0], 3, 2);
            }
            $deprwy = "MAIN DEPARTING RUNWAY " . $dep_pri . " .. SECONDARY DEPARTING RUNWAY " . $dep_sec;
        } else {
            $dep_pri = $dep;
            $deprwy = "MAIN DEPARTING RUNWAY " . $dep_pri;
        }

        //Operational Reports
        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] | CAVOK /', $wx, $visibilitymatch);

        if (str_contains($wx, 'CAVOK')) {
            $bzo = '';
        } else if ($visibilitymatch[0] < "550") {
            $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if ($visibilitymatch[0] <= "1500") {
            $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "1500") {
                $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }

        //Output
        $weather = $winds . $wind_gst . $wind_vrb . $vis_data . $vv_output . $rvr_atc . $phenomena . $clouds . $qnh . $trend;
        echo "THIS IS SCHIPHOL INFORMATION " . $cycle . " .. " . $arrrwy . " .. " . $deprwy . " .. TRANSITION LEVEL " . $trl . " .. " . $weather . " .. OPERATIONAL REPORT .. " . $bzo . "CONTACT APPROACH AND ARRIVAL CALLSIGN ONLY .. NOISE ABATEMENT N-A-D-P 2 PROCEDURES SHALL BE APPLIED" . $own . " .. CONFIRM INFORMATION " . $cycle . " ON FIRST CONTACT";

        //EUROSCOPE Link: http://localhost/atis/atis.php?arr=$arrrwy(EHAM)&deprwy=$deprwy(EHAM)&wx=$metar(EHAM)&atis=$atiscode&apt1=$apt2=&own=
    } else if ($ap === "EHBK") {        
        if ($_GET['apt1'] === 'ix') {
            $app_type = ' .. ILS X-RAY APPROACH ';
        } else if ($_GET['apt1'] === 'rnp') {
            $app_type = ' .. RNP APPROACH ';
        } else if ($_GET['apt1'] === 'ndb') {
            $app_type = ' .. NDB APPROACH ';
        } else if ($_GET['apt1'] === 'vis') {
            $app_type = ' .. VISUAL APPROACH ';
        } else {
            $app_type = '';
        }

        $arrrwy = $_GET['arr'];

        //Runway Condition Report
        if (preg_match('/RA/', $wx)) {
            $condrep = ' .. RUNWAY ' . $arrrwy . ' CONDITION REPORT AT TIME ' . $timestamp . ' .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 100 PERCENT WET .. SECOND PART 100 PERCENT WET .. THIRD PART 100 PERCENT WET';
        } else {
            $condrep = '';
        }

        //Operational Reports
        // First Frequency
        if ($_GET['ff'] === 'twr') {
            $first_freq = ' .. FOR STARTUP CONTACT BEEK TOWER ON FREQUENCY 118.480 ';
        } else if ($_GET['ff'] === 'app') {
            $first_freq = ' .. FOR STARTUP CONTACT BEEK APPROACH ON FREQUENCY 123.980 ';
        } else if ($_GET['ff'] === 'acw') {
            $first_freq = ' .. FOR STARTUP CONTACT AMSTERDAM RADAR ON FREQUENCY 125.750 ';
        } else if ($_GET['ff'] === 'ace') {
            $first_freq = ' .. FOR STARTUP CONTACT AMSTERDAM RADAR ON FREQUENCY 124.880 ';
        } else if ($_GET['ff'] === 'acs') {
            $first_freq = ' .. FOR STARTUP CONTACT AMSTERDAM RADAR ON FREQUENCY 123.850 ';
        } else if ($_GET['ff'] === '') {
            $first_freq = '';
        }

        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $wx, $visibilitymatch);

        //  RVR
        if (preg_match('/[0-9][0-9][0-9][0-9][DNU]/', $wx, $rvrmatch)) {
            $rvr = substr($rvrmatch[0], 0, 4);
        } else {
            $rvr = '9999';
        }
        if (str_contains($wx, 'CAVOK')) {
            $bzo = '';
        } else if ($visibilitymatch[0] <= "2000") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if ($rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }

        if (str_contains($bzo, 'LOW VISIBILITY') || str_contains($first_freq, 'FOR STARTUP') || preg_match('/[A-Z]/', $own)) {
            $sitrep = ' .. OPERATIONAL REPORT';
        } else {
            $sitrep = '';
        }

        //Output
        $weather = $winds . $wind_vrb . $minmaxwinds . $vis_data . $vv_output . $rvr_atc . $clouds . $phenomena . $temp_dp_output . $qnh . $trend;
        echo "THIS IS MAASTRICHT AACHEN INFORMATION " . $cycle . " .. MAIN LANDING RUNWAY " . $arrrwy . $app_type . " .. TRANSITION LEVEL " . $trl . $condrep . " .. " . $weather . $sitrep . $bzo . $first_freq . " .. ACKNOWLEDGE INFORMATION " . $cycle;

        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?arr=$arrrwy(EHBK)&wx=$metar(EHBK)&atis=$atiscode&app=&ff=&own=
    } else if ($ap === "EHEH") {
        define("app", [
            'x' => 'EXPECT C D O APPROACH TO ILS X RAY .. ',
            'y' => 'EXPECT RADAR VECTORING TO ILS YANKEE APPROACH .. ',
            'vis' => 'EXPECT VISUAL APPROACH .. ',
            '' => 'EXPECT C D O APPROACH TO ILS X RAY .. ',
        ]);
        $app_type = app[$_GET['apt1']];
        
        $arrrwy = $_GET['arr'];
        
        // Runway Condition Code
        if (preg_match('/\-SN/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 5 5 5 .. SNOW 100 PERCENT 1 MILLIMETER .. ';
        } else if (preg_match('/\+SN/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 4 4 4 .. SNOW 100 PERCENT 3 MILLIMETERS .. ';
        } else if (preg_match('/SN/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 4 4 4 .. SNOW 100 PERCENT 2 MILLIMETERS .. ';
        } else if (preg_match('/\+SH/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 5 5 5 .. 100 PERCENT 2 MILLIMETER WET .. ';
        } else if (preg_match('/RA|SH|DZ/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 5 5 5 .. 100 PERCENT 1 MILLIMETER WET .. ';
        } else if (preg_match('/REDZ|RERA|RESH/', $wx)) {
            $rwycond = 'RUNWAY CONDITION IS DAMP .. ';
        } else {
            $rwycond = 'RUNWAY CONDITION IS DRY .. ';
        }
        
        //Operational Reports
        //  RVR
        if (preg_match('/[0-9][0-9][0-9][0-9][DNU]/', $wx, $rvrmatch)) {
            $rvr = substr($rvrmatch[0], 0, 4);
        } else {
            $rvr = '9999';
        }
        
        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $wx, $visibilitymatch);
        
        if (str_contains($wx, 'CAVOK')) {
            $bzo = '';
        } else if ($visibilitymatch[0] <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if ($rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/LVP/', $wx)) {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        }
        else {
            $bzo = '';
        }
        
        if (str_contains($bzo, 'LOW VISIBILITY')) {
            $sitrep = ' .. OPERATIONAL REPORT';
        } else {
            $sitrep = '';
        }
        
        //Output
        $weather = $winds . $wind_vrb . $vis_data_m . $vv_output . $rvr_atc . $phenomena . $clouds . $temp_dp_output . $qnh;
        echo  "THIS IS EINDHOVEN INFORMATION " . $cycle . " .. " . $time . " .. " . $app_type . "RUNWAY " . $arrrwy . " .. FOR ARRIVAL, AND DEPARTURE" . " .. " . $rwycond .  "TRANSITION LEVEL " . $trl . $own . " .. " . $weather . $sitrep . $bzo . " .. CONFIRM EINDHOVEN INFORMATION " . $cycle . " ON FIRST CONTACT";
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?arr=$arrrwy(EHEH)&wx=$metar(EHEH)&atis=$atiscode&app=&own=
    } else if ($ap === "EHGG") {
        if ($_GET['apt1'] === 'i') {
            $app_type = 'ILS APPROACH .. ';
        } else if ($_GET['apt1'] === 'rnp') {
            $app_type = 'RNP APPROACH .. ';
        } else if ($_GET['apt1'] === 'vor') {
            $app_type = 'VOR APPROACH .. ';
        } else if ($_GET['apt1'] === 'vis') {
            $app_type = 'VISUAL APPROACH .. ';
        } else {
            $app_type = '';
        }
        
        $arrrwy = $_GET['arr'];
        
        //WEATHER
        //Operational Reports
        //Runway Condition Report
        if (preg_match('/RA/', $wx)) {
            $rwycondition = ' .. RUNWAY ' . $arrrwy . ' CONDITION REPORT AT TIME ' . $timestamp . ' .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 100 PERCENT WET .. SECOND PART 100 PERCENT WET .. THIRD PART 100 PERCENT WET';
        } else {
            $rwycondition = '';
        }

        //LOW QNH
        if ($q < "1000") {
            $lowqnh = " .. CAUTION LOW QNH .. CHECK I F R TRANSITION ALTITUDE IS 3000 FEET";
        } else {
            $lowqnh = "";
        }
        
        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $wx, $visibilitymatch);
        
        //  RVR
        if (preg_match('/[0-9][0-9][0-9][0-9][DNU]/', $wx, $rvrmatch)) {
            $rvr = substr($rvrmatch[0], 0, 4);
        } else {
            $rvr = '9999';
        }
        
        if (str_contains($wx, 'CAVOK')) {
            $bzo = '';
        } else if ($visibilitymatch[0] <= "2000") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN OPERATION ';
        } else if ($rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN OPERATION ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN OPERATION ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN OPERATION ';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }
        
        if (str_contains($bzo, 'LOW VISIBILITY') || preg_match('/[A-Z]/', $own)) {
            $sitrep = ' .. OPERATIONAL REPORT';
        } else {
            $sitrep = '';
        }
        
        //Output
        $weather = $winds . $wind_gst . $wind_vrb . $minmaxwinds . $vis_data_m . $vv_output . $rvr_atc . $clouds . $phenomena . $temp_dp_output . $qnh . $lowqnh . $trend;
        echo "THIS IS EELDE INFORMATION " . $cycle . " .. MAIN LANDING RUNWAY " . $arrrwy . " .. " . $app_type . "TRANSITION LEVEL " . $trl . $rwycondition . " .. " . $weather . $sitrep . $bzo . $own . " .. ACKNOWLEDGE INFORMATION " . $cycle;
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?arr=$arrrwy(EHGG)&wx=$metar(EHGG)&atis=$atiscode&app=&own=
    } else if ($ap === "EHKD") {
        define("app", [
            'ils' => 'EXPECT I L S APPROACH .. ',
            'rnp' => 'EXPECT R N P APPROACH .. ',
            '' => 'EXPECT I L S APPROACH .. ',
        ]);
        $app_type = app[$_GET['apt1']];
        
        $arrrwy = $_GET['arr'];
        
        // Runway Condition Code
        if (preg_match('/\-SN/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 5 5 5 .. SNOW 100 PERCENT 1 MILLIMETER .. ';
        } else if (preg_match('/\+SN/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 4 4 4 .. SNOW 100 PERCENT 3 MILLIMETER .. ';
        } else if (preg_match('/SN/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 4 4 4 .. SNOW 100 PERCENT 2 MILLIMETER .. ';
        } else if (preg_match('/\+SH/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 5 5 5 .. 100 PERCENT 2 MILLIMETER WET .. ';
        } else if (preg_match('/RA|SH|DZ/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 5 5 5 .. 100 PERCENT 1 MILLIMETER WET .. ';
        } else if (preg_match('/-DZ/', $wx)) {
            $rwycond = 'RUNWAY CONDITION REPORT AT TIME ' . $time . ' .. RUNWAY CONDITION CODE 5 5 5 .. 75 PERCENT WET .. ';
        } else if (preg_match('/REDZ|RERA|RESH/', $wx)) {
            $rwycond = 'RUNWAY CONDITION DAMP .. ';
        } else {
            $rwycond = 'RUNWAY CONDITION DRY .. ';
        }
        
        //Operational Reports
        //  RVR
        if (preg_match('/[0-9][0-9][0-9][0-9][DNU]/', $wx, $rvrmatch)) {
            $rvr = substr($rvrmatch[0], 0, 4);
        } else {
            $rvr = '9999';
        }
        
        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $wx, $visibilitymatch);
        
        if (str_contains($wx, 'CAVOK')) {
            $bzo = '';
        } else if ($visibilitymatch[0] <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if ($rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/LVP/', $wx)) {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        }
        else {
            $bzo = '';
        }
        
        //Output
        $weather = $winds . $wind_vrb . $vis_data_m . $vv_output . $rvr_atc . $phenomena . $clouds . $temp_dp_output . $qnh;
        echo  "THIS IS DE KOOY INFORMATION " . $cycle . " .. " . $time . " .. " . $app_type . "RUNWAY " . $arrrwy . " .. FOR ARRIVAL, AND DEPARTURE" . " .. " . $rwycond . "TRANSITION LEVEL " . $trl . $own . " .. BIRD STATUS ALERT" . $bzo . " .. " . $weather . " .. CONFIRM INFORMATION " . $cycle . " ON FIRST CONTACT";
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?arr=$arrrwy(EHKD)&wx=$metar(EHKD)&atis=$atiscode&app=&own=
    } else if ($ap === "EHLE") {
        
        if ($_GET['apt1'] === 'i') {
            $app_type = 'ILS APPROACH .. ';
        } else if ($_GET['apt1'] === 'r') {
            $app_type = 'RNP APPROACH .. ';
        } else {
            $app_type = '';
        }

        $arrrwy = $_GET['arr'];
        
        //Operational Reports
        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $wx, $visibilitymatch);
        
        //  RVR
        if (preg_match('/[0-9][0-9][0-9][0-9][DNU]/', $wx, $rvrmatch)) {
            $rvr = substr($rvrmatch[0], 0, 4);
        } else {
            $rvr = '9999';
        }
        
        if (str_contains($wx, 'CAVOK')) {
            $bzo = '';
        } else if ($visibilitymatch[0] <= "2000") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if ($rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }
        
        if (str_contains($bzo, 'LOW VISIBILITY') || preg_match('/[A-Z]/', $own)) {
            $sitrep = ' .. OPERATIONAL REPORT';
        } else {
            $sitrep = '';
        }
        
        //Output
        $weather = $winds . $wind_gst . $wind_vrb . $minmaxwinds . $vis_data_m . $vv_output . $rvr_atc . $phenomena . $clouds . $temp_dp_output . $qnh;
        echo "THIS IS LELYSTAD INFORMATION " . $cycle . " .. MAIN LANDING RUNWAY " . $arrrwy . " .. " . $app_type . " REPORT FULL INTENTIONS AT START UP .. TRANSITION LEVEL " . $trl . " .. " . $weather . $sitrep . $bzo . $own . " .. ACKNOWLEDGE INFORMATION " . $cycle;
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?arr=$arrrwy(EHLE)&wx=$metar(EHLE)&atis=$atiscode&app=&own=
    } else if ($ap === "EHRD") {
        if ($_GET['apt1'] === 'i') {
            $app_type = 'ILS APPROACH .. ';
        } else if ($_GET['apt1'] === 'rnp') {
            $app_type = 'RNP APPROACH .. ';
        } else if ($_GET['apt1'] === 'vor') {
            $app_type = 'VOR APPROACH .. ';
        } else if ($_GET['apt1'] === 'vis') {
            $app_type = 'VISUAL APPROACH .. ';
        } else {
            $app_type = '';
        }
        
        $arrrwy = $_GET['arr'];
        
        //Runway Condition Report
        if (preg_match('/DZ/', $wx)) {
            $condrep = ' .. RUNWAY ' . $arrrwy . ' CONDITION REPORT AT TIME ' . $timestamp . ' .. RUNWAY CONDITION CODE 5 5 5 .. ENTIRE RUNWAY 1 0 0 PERCENT WET';
        } else if (preg_match('/RA/', $wx)) {
            $condrep = ' .. RUNWAY ' . $arrrwy . ' CONDITION REPORT AT TIME ' . $timestamp . ' .. RUNWAY CONDITION CODE 5 5 5 .. ENTIRE RUNWAY 1 0 0 PERCENT WET';
        } else {
            $condrep = '';
        }
        
        //LOW QNH
        if ($q < "1000") {
            $lowqnh = " .. CAUTION LOW QNH .. CHECK I F R TRANSITION ALTITUDE IS *3000 FEET";
        } else {
            $lowqnh = '';
        }
        
        //Operational Reports
        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $wx, $visibilitymatch);
        
        //  RVR
        if (preg_match('/[0-9][0-9][0-9][0-9][DNU]/', $wx, $rvrmatch)) {
            $rvr = substr($rvrmatch[0], 0, 4);
        } else {
            $rvr = '9999';
        }
        
        if (str_contains($wx, 'CAVOK')) {
            $bzo = '';
        } else if ($visibilitymatch[0] <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if ($rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $wx, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $wx, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }
        
        if (str_contains($bzo, 'LOW VISIBILITY') || preg_match('/[A-Z]/', $own)) {
            $sitrep = ' .. OPERATIONAL REPORT';
        } else {
            $sitrep = '';
        }
        
        //Output
        $weather = $winds . $wind_gst . $wind_vrb . $minmaxwinds . $vis_data_m . $vv_output . $rvr_atc . $phenomena . $clouds . $temp_dp_output . $qnh . $trend;
        echo "THIS IS ROTTERDAM INFORMATION " . $cycle . " .. " . " MAIN LANDING RUNWAY " . $arrrwy . " .. " . $app_type . "TRANSITION LEVEL " . $trl . $lowqnh . $condrep . " .. " . $weather . $sitrep . $bzo . $own . " .. ACKNOWLEDGE INFORMATION " . $cycle;
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?arr=$arrrwy(EHRD)&wx=$metar(EHRD)&atis=$atiscode&app=&own=
    } else {
        echo "NO ATIS DEFINED FOR THIS AIRPORT";
    }
} else {
    echo "NO ATIS DEFINED FOR THIS AIRPORT";
}
?>