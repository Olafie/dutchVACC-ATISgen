<?php
$metar = $_GET['wx'];

if (str_starts_with($metar, 'METAR')) {
    $ap = substr($metar, 6, 4);
} else {
    $ap = substr($metar, 0, 4);
}

if (preg_match('/BLU /', $metar)) {
    $metar = str_replace('BLU ', ' ', $metar);
} else if (preg_match('/GRN /', $metar)) {
    $metar = str_replace('GRN ', ' ', $metar);
} else if (preg_match('/YLO /', $metar)) {
    $metar = str_replace('YLO ', ' ', $metar);
} else if (preg_match('/RED /', $metar)) {
    $metar = str_replace('RED ', ' ', $metar);
} else if (preg_match('/BLACK /', $metar)) {
    $metar = str_replace('BLACK ', ' ', $metar);
} else if (preg_match('/AMB /', $metar)) {
    $metar = str_replace('AMB ', ' ', $metar);
} else if (preg_match('/WHT /', $metar)) {
    $metar = str_replace('WHT ', ' a', $metar);
} else {
    $metar = $metar;
}

//Remove trend
if (str_contains($metar, ' BECMG ')) {
    $metar = substr($metar, 0, strpos($metar, "BECMG"));
} else if (str_contains($metar, ' TEMPO ')) {
    $metar = substr($metar, 0, strpos($metar, "TEMPO"));
} else if (str_contains($metar, ' NOSIG ')) {
    $metar = substr($metar, 0, strpos($metar, "NOSIG"));
} else {
    $metar = $metar;
}

if (str_contains($metar, 'TEMPO')) {
    $metar = substr($metar, 0, strpos($metar, "TEMPO"));
} else if (str_contains($metar, 'BECMG')) {
    $metar = substr($metar, 0, strpos($metar, "BECMG"));
} else {
    $metar = $metar;
}

if (preg_match('/[0-9][0-9][0-9][0-9][0-9][0-9]Z/', $metar, $time)) {
    $time = substr($time[0], 2, 4);
} else {
    $time = '';
}

$wxr = explode('%20', $metar);

//Wind
$wind_gst = "";
if (preg_match('/00000KT/', $metar)) {
    $winds = "WIND CALM .. ";
} else if (preg_match('/[0-9][0-9][0-9]01KT/', $metar, $windmatch)) {
    $winds = "WIND " . substr($windmatch[0], 0, 3) . "DEGREES .. 1 KNOT .. ";
} else if (preg_match('/[0-9][0-9][0-9][0-9][0-9]KT/', $metar, $windmatch)) {
    $winds = "WIND " . substr($windmatch[0], 0, 3) . " DEGREES .. " .  ltrim(substr($windmatch[0], 3, 2),"0") . " KNOTS .. ";
} else if (preg_match('/VRB01KT/', $metar, $windmatch)) {
    $winds = "WIND VARIABLE .. 1 KNOT .. ";
} else if (preg_match('/VRB[0-9][0-9]KT/', $metar, $windmatch)) {
    $winds = "WIND VARIABLE .." . ltrim(substr($windmatch[0], 3, 2),"0") . " KNOTS .. ";
} else if (preg_match('/[0-9][0-9][0-9][0-9][0-9]G[0-9][0-9]KT/', $metar, $windmatch)) {
    $winds = "WIND " . substr($windmatch[0], 0, 3) . " DEGREES .. " . ltrim(substr($windmatch[0], 3, 2), "0") . " KNOTS .. ";
    $wind_gst = "GUST .. " . ltrim(substr($windmatch[0], 6, 2), "0") . " KNOTS .. ";
} else {
    $winds = "WIND UNAVAILABLE";
}
$wind_output = $winds;

//Variable winds
if (preg_match('/[0-3][0-9][0-9]V[0-3][0-9][0-9]/', $metar, $vrbmatch)) {
    if ($ap === "EHKD") {
        $wind_vrb = "VARYING BETWEEN " . substr($vrbmatch[0], 0, 3) . " AND " . substr($vrbmatch[0], 4, 3) . " DEGREES .. ";
    } else {
        $wind_vrb = "VARIABLE BETWEEN " . substr($vrbmatch[0], 0, 3) . " AND " . substr($vrbmatch[0], 4, 3) . " DEGREES .. ";
    }
} else {
    $wind_vrb = '';
}

//Max/Min winds
$minmaxwinds_extract = preg_match('/[0-9]{5}KT|[0-9]{5}G[0-9]{2}KT/', $metar);
$minmaxwinds_knots = substr($minmaxwinds_extract, 3, 2);
if ($minmaxwinds_knots > '10') {
    $minmaxwinds = ltrim(substr($minmaxmatch[0], 3, 2) + 3, "0") . " KNOTS MAXIMUM, " . ltrim(substr($minmaxmatch[0], 3, 2) - 2, "0") . " KNOTS MINIMUM .. ";
} else {
    $minmaxwinds = "";
}

//Visibility
if (preg_match('/ 9999 /', $metar)) {
    $vis_data_m = "VISIBILITY 10 KILOMETERS OR MORE .. ";
    $vis_data = "VISIBILITY 10 KILOMETERS OR MORE .. ";
} else if (preg_match('/ [0-9]000 /', $metar, $vismatch)) {
    $vis_data_m = "VISIBILITY *" . substr($vismatch[0], 1, 4) . " METERS .. ";
    $vis_data  = "VISIBILITY " . substr($vismatch[0], 1, 1) . " KILOMETERS .. ";
} else if (preg_match('/ [0-9]{4} /', $metar, $vismatch)) {
    $vis_data_m = "VISIBILITY *" . ltrim(substr($vismatch[0], 1, 4), "0") . " METERS .. ";
    $vis_data  = "VISIBILITY " . substr($vismatch[0], 1, 1) . " KILOMETERS AND *" . ltrim(substr($vismatch[0], 2, 3), "0") . " METERS .. ";
} else {
    $vis_data_m = "";
    $vis_data = "";
}

//Vertical Visibility
if (preg_match('/VV000/', $metar)) {
    $vv_data = 'VERTICAL VISIBILITY LESS THEN *50 FEET .. ';
    $vv_output = 'VERTICAL VISIBILITY LESS THEN *50 FEET .. ';
} else if (preg_match('/VV[0-9][0-9][0-9]/', $metar, $vvmatch)) {
    $vv_data = 'VERTICAL VISIBILITY *' . substr($vvmatch[0], 2, 3) . "00 FEET .. ";
    $vv_output = 'VERTICAL VISIBILITY *' . substr($vvmatch[0], 2, 3) . "00 FEET .. ";
} else {
    $vv_output = '';
}

//RVR Data
if (preg_match('/R[0-9][0-9]\/[0-9][0-9][0-9][0-9][DNU]|R[0-9][0-9]\/P[0-9][0-9][0-9][0-9][DNU]/', $metar)) {
    if ($ap === "EHGG") {
        $rvr_atc = 'RVR VALUES AVAILABLE ON ATC FREQUENCY .. ';
    } else {
        $rvr_atc = 'RVR AVAILABLE ON ATC FREQUENCY .. ';
    }
} else {
    $rvr_atc = '';
}

//Clouds
if (preg_match('/NSC/', $metar)) {
    $clouds = "NO SIGNIFICANT CLOUDS .. ";
} else if (preg_match('/NCD/', $metar)) {
    $clouds = "NO CLOUDS DETECTED .. ";
} else if (preg_match('/SKC/', $metar)) {
    $clouds = "SKY CLEAR .. ";
} else if (preg_match('/CAVOK/', $metar)) {
    $clouds = "CAV OKAY .. ";
} else {
    $clouds = '';
}

if (preg_match('/FEW[0-9]{3}|SCT[0-9]{3}|BKN[0-9]{3}|OVC[0-9]{3}/', $metar)) {
    $clouds = 'CLOUDS .. ';
} else {
    $clouds .= '';
}

foreach ($wxr as $metar) {
    if (preg_match('/FEW[0-9]{3}CB/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'FEW *' . $cldalt . '00 FEET .. CUMULONIMBUS .. ';
    } else if (preg_match('/FEW[0-9]{3}TCU/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'FEW *' . $cldalt . '00 FEET .. TOWERING CUMULUS .. ';
    } else if (preg_match('/FEW[0-9]{3}/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'FEW *' . $cldalt . '00 FEET .. ';
    } else {
        $clouds .= '';
    }
}
foreach ($wxr as $metar) {
    if (preg_match('/SCT[0-9]{3}CB/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'SCATTERED *' . $cldalt . '00 FEET .. CUMULONIMBUS .. ';
    } else if (preg_match('/SCT[0-9]{3}TCU/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'SCATTERED *' . $cldalt . '00 FEET .. TOWERING CUMULUS .. ';
    } else if (preg_match('/SCT[0-9]{3}/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'SCATTERED *' . $cldalt . '00 FEET .. ';
    } else {
        $clouds .= '';
    }
}
foreach ($wxr as $metar) {
    if (preg_match('/BKN[0-9]{3}CB/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'BROKEN *' . $cldalt . '00 FEET .. CUMULONIMBUS .. ';
    } else if (preg_match('/BKN[0-9]{3}TCU/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'BROKEN *' . $cldalt . '00 FEET .. TOWERING CUMULUS .. ';
    } else if (preg_match('/BKN[0-9]{3}/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'BROKEN *' . $cldalt . '00 FEET .. ';
    } else {
        $clouds .= '';
    }
}
foreach ($wxr as $metar) {
    if (preg_match('/OVC[0-9]{3}CB/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'OVERCAST *' . $cldalt . '00 FEET .. CUMULONIMBUS .. ';
    } else if (preg_match('/OVC[0-9]{3}TCU/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'OVERCAST *' . $cldalt . '00 FEET .. TOWERING CUMULUS .. ';
    } else if (preg_match('/OVC[0-9]{3}/', $metar, $cloudmatch)) {
        $cldalt = ltrim(substr($cloudmatch[0], 3, 3), "0");
        $clouds .= 'OVERCAST *' . $cldalt . '00 FEET .. ';
    } else {
        $clouds .= '';
    }
}

//Temperature/Dewpoint
if (preg_match('/M[0-9][0-9]\/M[0-9][0-9]/', $metar, $tempdpmatch)) {
    $temp = ltrim(substr($tempdpmatch[0], 1, 2), "0");
    $dp = ltrim(substr($tempdpmatch[0], 6, 2), "0");
    $temp_dp_output = 'TEMPERATURE MINUS ' . $temp . " .. DEW POINT MINUS " . $dp . " .. ";
    $temp_trl = $temp;
} else if (preg_match('/[0-9][0-9]\/[0-9][0-9]/', $metar, $tempdpmatch)) {
    $temp = ltrim(substr($tempdpmatch[0], 0, 2), "0");
    $dp = ltrim(substr($tempdpmatch[0], 3, 2), "0");
    $temp_dp_output = 'TEMPERATURE '. $temp . " .. DEW POINT " . $dp . " .. ";
    $temp_trl = "-" . $temp;
} else if (preg_match('/[0-9][0-9]\/M[0-9][0-9]/', $metar, $tempdpmatch)) {
    $temp = ltrim(substr($tempdpmatch[0], 0, 2), "0");
    $dp = ltrim(substr($tempdpmatch[0], 5, 2), "0");
    $temp_dp_output = 'TEMPERATURE ' . $temp . " .. DEW POINT MINUS " . $dp . " .. ";
    $temp_trl = $temp;
}

//QNH
if (preg_match('/Q[0-9][0-9][0-9][0-9]/', $metar, $qmatch)) {
    $q = ltrim(substr($qmatch[0], 1, 4), "0");
    if ($ap === "EHKD") {
        $qnh = 'QNH ' . $q;
    } else {
        $qnh = 'QNH ' . $q . ' HECTOPASCAL';
    }
}

//Phenomena
if ($ap === "EHKD") {
    $phenomena = "PRESENT WEATHER ";
} else if ($ap === "EHBK") {
    $phenomena = "WEATHER ";
} else {
    $phenomena = "";
}

if (str_contains($metar, '-')) {
    $phenomena .= 'LIGHT ';
}
if (str_contains($metar, '+')) {
    $phenomena .= 'HEAVY ';
}

if (preg_match('/[-+ ]FZRA/', $metar)) {
    $phenomena .= 'FREEZING RAIN ';
} else if (preg_match('/[-+ ]FZ/', $metar)) {
    $phenomena .= 'FREEZING ';
}

if (preg_match('/[-+ ]TS/', $metar)) {
    $phenomena .= 'THUNDERSTORM ';
}

if (preg_match('/[-+ ]MI/', $metar)) {
    $phenomena .= 'SHALLOW ';
}

if (preg_match('/[-+ ]BL/', $metar)) {
    $phenomena .= 'BLOWING ';
}

if (preg_match('/[-+ ]DR/', $metar)) {
    $phenomena .= 'LOW DRIFTING ';
}

if (preg_match('/[-+ ]PR/', $metar)) {
    $phenomena .= 'PARTIAL ';
}

if (preg_match('/[-+ ]BC/', $metar)) {
    $phenomena .= 'PATCHES OF ';
}

if (preg_match('/[-+ ]GR/', $metar)) {
    $phenomena .= 'HAIL ';
}

if (preg_match('/[-+ ]GS/', $metar)) {
    $phenomena .= 'SMALL HAIL ';
}

if (preg_match('/[-+ ]PL/', $metar)) {
    $phenomena .= 'ICE PELLETS ';
}

if (preg_match('/[-+ ]IC/', $metar)) {
    $phenomena .= 'ICE CRYSTALS ';
}

if (preg_match('/[-+ ]SG/', $metar)) {
    $phenomena .= 'SNOW GRAINS ';
}


// Rain
if (preg_match('/[-+ ]DZRA/', $metar)) {
    $phenomena .= 'DRIZZLE RAIN ';
} else if (preg_match('/[-+ ]RADZ/', $metar)) {
    $phenomena .= 'RAIN DRIZZLE ';
} else if (preg_match('/[-+ ]SHRA/', $metar)) {
    $phenomena .= 'SHOWERS OF RAIN ';
} else if (preg_match('/[-+ ]RA/', $metar)) {
    $phenomena .= 'RAIN ';
} else if (preg_match('/[-+ ]DZ/', $metar)) {
    $phenomena .= 'DRIZZLE ';
}

if (preg_match('/[-+ ]BR/', $metar)) {
    $phenomena .= 'MIST ';
}

if (preg_match('/[-+ ]DS/', $metar)) {
    $phenomena .= 'DUST STORM ';
}

if (preg_match('/[-+ ]DU/', $metar)) {
    $phenomena .= 'WIDESPREAD DUST ';
}

if (preg_match('/[-+ ]FC/', $metar)) {
    $phenomena .= 'FUNNEL CLOUD ';
}

if (preg_match('/[-+ ]FG/', $metar)) {
    $phenomena .= 'FOG ';
}

if (preg_match('/[-+ ]FU/', $metar)) {
    $phenomena .= 'SMOKE ';
} else {
    $phenomena .= '';
}
if (preg_match('/[-+ ]HZ/', $metar)) {
    $phenomena .= 'HAZE ';
}

if (preg_match('/[-+ ]PO/', $metar)) {
    $phenomena .= 'DUST ';
}

if (preg_match('/[-+ ]PY/', $metar)) {
    $phenomena .= 'SPRAY ';
}

if (preg_match('/[-+ ]SA/', $metar)) {
    $phenomena .= 'SAND ';
}

if (preg_match('/[-+ ]SN/', $metar)) {
    $phenomena .= 'SNOW ';
}

if (preg_match('/[-+ ]SS/', $metar)) {
    $phenomena .= 'SANDSTORM ';
}

if (preg_match('/[-+ ]SQ/', $metar)) {
    $phenomena .= 'SQUALL ';
}

if (preg_match('/[-+ ]UP/', $metar)) {
    $phenomena .= 'UNKNOWN PRECIPITATION ';
}

if (preg_match('/[-+ ]VA/', $metar)) {
    $phenomena .= 'VULCANIC ASH ';
}

if (preg_match('/VCSN/', $metar)) {
    $phenomena = 'VICINITY SNOW';
    $metar = str_replace('VCSN', ' ', $metar);
}

if (preg_match('/VCSHRA/', $metar)) {
    $phenomena = 'VICINITY SHOWERS OF RAIN ';
    $metar = str_replace('VCSHRA', ' ', $metar);
} else if (preg_match('/VCSH/', $metar)) {
    $phenomena = 'VICINITY SHOWERS ';
    $metar = str_replace('VCSH', ' ', $metar);
}

if (preg_match('/VCRA/', $metar)) {
    $phenomena = 'VICINITY RAIN';
    $metar = str_replace('VCRA', ' ', $metar);
}

if (preg_match('/VCDZ/', $metar)) {
    $phenomena = 'VICINITY DRIZZLE ';
    $metar = str_replace('VCDZ', ' ', $metar);
}

if (preg_match('/VCTS/', $metar)) {
    $phenomena = 'VICINITY THUNDERSTORMS ';
    $metar = str_replace('VCTS', ' ', $metar);
}

if (preg_match('/BC|BL|BR|DR|DS|DU|DZ|FC|FG|FU|FZ|GR|HZ|IC|MI|PL|PR|PY|RA|SA|SG|SH|SN|SS|SQ| TS|UP|VA/', $metar)) {
    $phenomena .= " .. ";
} else {
    $phenomena = "";
}
?>