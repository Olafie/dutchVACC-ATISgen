<?php
include 'metarv2.php';
include 'api.php';

//Weather
$weather = "";

if ($currentweather["WIND"]["SPD"] == "0") {
    $weather .= " .. WIND CALM";
} else if ($currentweather["WIND"]["DIR"] == "VARIABLE") {
    $weather .= " .. WIND " . $currentweather["WIND"]["DIR"];
} else {
    $weather .= " .. WIND " . $currentweather["WIND"]["DIR"] . " DEGREES";
}

if ($currentweather["WIND"]["SPD"] == "1") {
    $weather .= " .. " . $currentweather["WIND"]["SPD"] . " KNOT";
} else if ($currentweather["WIND"]["SPD"] > "1") {
    $weather .= " .. " . $currentweather["WIND"]["SPD"] . " KNOTS";
}

if (($ap == "EHGG" || $ap == "EHBK") && $currentweather["WIND"]["GST"]) {
    $weather .= " .. GUST " . $currentweather["WIND"]["GST"] . " KNOTS";
}

if (!empty($currentweather["WIND"]["MAXDIR"]) && $ap == "EHKD") {
    $weather .= " .. VARYING BETWEEN " . $currentweather["WIND"]["MINDIR"] . " AND " . $currentweather["WIND"]["MAXDIR"] . " DEGREES";
} else if (!empty($currentweather["WIND"]["MAXDIR"])) {
    $weather .= " .. VARIABLE BETWEEN " . $currentweather["WIND"]["MINDIR"] . " AND " . $currentweather["WIND"]["MAXDIR"] . " DEGREES";
}

if ($currentweather["VISIBILITY"] == "") {
} else if ($currentweather["VISIBILITY"] < "800") {
    $weather .= " .. VISIBILITY " . $currentweather["VISIBILITY"] . " METERS";
} else if ($currentweather["VISIBILITY"] < "5000" || (($ap == "EHEH" || $ap == "EHKD") && $currentweather["VISIBILITY"] < "9999")) {
    $weather .= " .. VISIBILITY *" . $currentweather["VISIBILITY"] .  " METERS";
} else if ($currentweather["VISIBILITY"] == "9999") {
    $weather .= " .. VISIBILITY 10 KILOMETERS OR MORE";
} else if ($currentweather["VISIBILITY"] < "9999") {
    $weather .= " .. VISIBILITY " . substr($currentweather["VISIBILITY"], 0, 1) . " KILOMETERS";
}

if ($currentweather["RVR"] && ($ap == "EHBK" || $ap == "EHGG")) {
    $weather .= " .. RVR VALUES AVAILABLE ON ATC FREQUENCY";
} else if ($currentweather["RVR"] && (str_starts_with($ap, "EH"))) {
    $weather .= " .. RVR AVAILABLE ON ATC FREQUENCY";
}

if ($currentweather["VV"] == "") {
} else if ($currentweather["VV"] == "000") {
    $weather .= " .. VERTICAL VISIBILITY BELOW *100 FEET";
} else {
    $weather .= " .. VERTICAL VISIBILITY *" . $currentweather["VV"] . " FEET";
}

if (count($currentweather["PHENOMENA"]) > 0) {
    if ($ap == "EHKD") {
        $weather .= " .. PRESENT WEATHER";
    } else if ($ap == "EHBK" || $ap == "EHGG") {
        $weather .= " .. WEATHER";
    }
}

foreach ($currentweather["PHENOMENA"] as $phenomenon) {
    $weather .= " .. ";

    if (str_contains($phenomenon, '-')) {
        $weather .= 'LIGHT ';
    }

    if (str_contains($phenomenon, '+')) {
        $weather .= 'HEAVY ';
    }

    if (preg_match('/FZ/', $phenomenon)) {
        $weather .= 'FREEZING ';
    }

    if (preg_match('/TS/', $phenomenon)) {
        $weather .= 'THUNDERSTORM ';
    }

    if (preg_match('/MI/', $phenomenon)) {
        $weather .= 'SHALLOW ';
    }

    if (preg_match('/BL/', $phenomenon)) {
        $weather .= 'BLOWING ';
    }

    if (preg_match('/DR/', $phenomenon)) {
        $weather .= 'LOW DRIFTING ';
    }

    if (preg_match('/PR/', $phenomenon)) {
        $weather .= 'PARTIAL ';
    }

    if (preg_match('/BC/', $phenomenon)) {
        $weather .= 'PATCHES OF ';
    }

    if (preg_match('/GR/', $phenomenon)) {
        $weather .= 'HAIL ';
    }

    if (preg_match('/GS/', $phenomenon)) {
        $weather .= 'SMALL HAIL ';
    }

    if (preg_match('/PL/', $phenomenon)) {
        $weather .= 'ICE PELLETS ';
    }

    if (preg_match('/IC/', $phenomenon)) {
        $weather .= 'ICE CRYSTALS ';
    }

    if (preg_match('/SG/', $phenomenon)) {
        $weather .= 'SNOW GRAINS ';
    }

    if (preg_match('/DZRA/', $phenomenon)) {
        $weather .= 'DRIZZLE RAIN ';
    } else if (preg_match('/RADZ/', $phenomenon)) {
        $weather .= 'RAIN DRIZZLE ';
    } else if (preg_match('/SHRA/', $phenomenon)) {
        $weather .= 'SHOWERS OF RAIN ';
    } else if (preg_match('/RA/', $phenomenon)) {
        $weather .= 'RAIN ';
    } else if (preg_match('/DZ/', $phenomenon)) {
        $weather .= 'DRIZZLE ';
    }

    if (preg_match('/BR/', $phenomenon)) {
        $weather .= 'MIST ';
    }

    if (preg_match('/DS/', $phenomenon)) {
        $weather .= 'DUST STORM ';
    }

    if (preg_match('/DU/', $phenomenon)) {
        $weather .= 'WIDESPREAD DUST ';
    }

    if (preg_match('/FC/', $phenomenon)) {
        $weather .= 'FUNNEL CLOUD ';
    }

    if (preg_match('/FG/', $phenomenon)) {
        $weather .= 'FOG ';
    }

    if (preg_match('/FU/', $phenomenon)) {
        $weather .= 'SMOKE ';
    }

    if (preg_match('/HZ/', $phenomenon)) {
        $weather .= 'HAZE ';
    }

    if (preg_match('/PO/', $phenomenon)) {
        $weather .= 'DUST ';
    }

    if (preg_match('/PY/', $phenomenon)) {
        $weather .= 'SPRAY ';
    }

    if (preg_match('/SA/', $phenomenon)) {
        $weather .= 'SAND ';
    }

    if (preg_match('/SN/', $phenomenon)) {
        $weather .= 'SNOW ';
    }

    if (preg_match('/SS/', $phenomenon)) {
        $weather .= 'SANDSTORM ';
    }

    if (preg_match('/SQ/', $phenomenon)) {
        $weather .= 'SQUALL ';
    }

    if (preg_match('/UP/', $phenomenon)) {
        $weather .= 'UNKNOWN PRECIPITATION ';
    }
}

if ($currentweather["CAVOK"]) {
    $weather .= " .. CAVOK";
}

if ($currentweather["NSC"]) {
    $weather .= " .. NO SIGNIFICANT CLOUDS";
}

if ($currentweather["NCD"]) {
    $weather .= " .. NO CLOUDS DETECTED";
}

if ($currentweather["SKC"]) {
    $weather .= " .. SKY CLEAR";
}

if (count($currentweather["CLOUDS"]) > 0) {
    $weather .= " .. CLOUDS";
}

foreach ($currentweather["CLOUDS"] as $cloud) {
    $weather .= " .. ";

    if ($cloud["TYPE"] == "FEW") {
        $weather .= "FEW";
    } else if ($cloud["TYPE"] == "SCT") {
        $weather .= "SCATTERED";
    } else if ($cloud["TYPE"] == "BKN") {
        $weather .= "BROKEN";
    } else if ($cloud["TYPE"] == "OVC") {
        $weather .= "OVERCAST";
    }

    if ($ap == "EHKD") {
        $weather .= " AT ";
    }

    $weather .= " *" . $cloud["ALT"] . " FEET";

    if ($cloud["EXTRA"] == 'TCU') {
        $weather .= " .. TOWERING CUMULUS";
    } else if ($cloud["EXTRA"] == "CB") {
        $weather .= " .. CUMULONIMBUS";
    }
}

if ($currentweather["TEMPERATURE"] == "") {
} else if ($currentweather["TEMPERATURE"] < '0') {
    $weather .= " .. TEMPERATURE MINUS " . -$currentweather["TEMPERATURE"];
} else {
    $weather .= " .. TEMPERATURE " . $currentweather["TEMPERATURE"];
}

if ($currentweather["DEWPOINT"] == "") {
} else if ($currentweather["DEWPOINT"] < 0) {
    $weather .= " .. DEWPOINT MINUS " . -$currentweather["DEWPOINT"];
} else {
    $weather .= " .. DEWPOINT " . $currentweather["DEWPOINT"];
}

if ($currentweather["QNH"] == "") {
} else {
    $weather .= " .. QNH " . $currentweather["QNH"];
}

if ($ap !== "EHKD") {
    $weather .= " HECTOPASCAL";
}

$trend = "";

if ($tempoweather["AVAILABLE"]) {
    if ($ap == "EHGG" || $ap == "EHBK" || $ap == "EHAM") {
        $trend .= " .. TREND ";
    } else {
        $trend .= " .. ";
    }

    $trend .= "TEMPORARY";
    if (!empty($tempoweather["WIND"]["DIR"])) {
        if ($tempoweather["WIND"]["SPD"] == "0") {
            $trend .= " .. WIND CALM";
        } else if ($tempoweather["WIND"]["DIR"] == "VARIABLE") {
            $trend .= " .. WIND " . $tempoweather["WIND"]["DIR"];
        } else {
            $trend .= " .. WIND " . $tempoweather["WIND"]["DIR"] . " DEGREES";
        }

        if ($tempoweather["WIND"]["SPD"] == "1") {
            $trend .= " .. " . $tempoweather["WIND"]["SPD"]  ." KNOT";
        } else if ($tempoweather["WIND"]["SPD"] > "1"){
            $trend .= " .. " . $tempoweather["WIND"]["SPD"]  ." KNOTS";
        }

        if (($ap == "EHGG" || $ap == "EHBK") && $tempoweather["WIND"]["GST"]) {
            $trend .= " .. GUST " . $tempoweather["WIND"]["GST"] . " KNOTS";
        }

        if (!empty($tempoweather["WIND"]["MAXDIR"]) && $ap == "EHKD") {
            $trend .= " .. VARYING BETWEEN " . $currentweather["WIND"]["MINDIR"] . " AND " . $currentweather["WIND"]["MAXDIR"] . " DEGREES";
        } else if (!empty($tempoweather["WIND"]["MINDIR"])) {
            $trend .= " .. VARIABLE BETWEEN " . $tempoweather["WIND"]["MINDIR"] . " AND " . $tempoweather["WIND"]["MAXDIR"] . " DEGREES";
        }
    }

    if ($tempoweather["VISIBILITY"] == "") {
    } else if ($tempoweather["VISIBILITY"] < "800") {
        $trend .= " .. VISIBILITY " . $tempoweather["VISIBILITY"] . " METERS";
    } else if ($tempoweather["VISIBILITY"] < "5000") {
        $trend .= " .. VISIBILITY *" . $tempoweather["VISIBILITY"] .  " METERS";
    } else if ($tempoweather["VISIBILITY"] < "9999") {
        $trend .= " .. VISIBILITY " . substr($tempoweather["VISIBILITY"], 0, 1) . " KILOMETERS";
    } else if ($tempoweather["VISIBILITY"] == "9999") {
        $trend .= " .. VISIBILITY 10 KILOMETERS OR MORE";
    }

    if ($tempoweather["VV"] == "") {
    } else if ($tempoweather["VV"] == "000") {
        $trend .= " .. VERTICAL VISIBILITY BELOW *100 FEET";
    } else {
        $trend .= " .. VERTICAL VISIBILITY *" . $tempoweather["VV"] . " FEET";
    }    

    if ($tempoweather["CAVOK"]) {
        $trend .= " .. CAVOK";
    }

    if ($tempoweather["NSC"]) {
        $trend .= " .. NO SIGNIFICANT CLOUDS";
    }

    foreach ($tempoweather["PHENOMENA"] as $tempophenomenon) {
        $trend .= " .. ";
    
        if (str_contains($tempophenomenon, '-')) {
            $trend .= 'LIGHT ';
        }
        if (str_contains($tempophenomenon, '+')) {
            $trend .= 'HEAVY ';
        }
    
        if (preg_match('/FZ/', $tempophenomenon)) {
            $trend .= 'FREEZING ';
        }
    
        if (preg_match('/TS/', $tempophenomenon)) {
            $trend .= 'THUNDERSTORM ';
        }
    
        if (preg_match('/MI/', $tempophenomenon)) {
            $trend .= 'SHALLOW ';
        }
    
        if (preg_match('/BL/', $tempophenomenon)) {
            $trend .= 'BLOWING ';
        }
    
        if (preg_match('/DR/', $tempophenomenon)) {
            $trend .= 'LOW DRIFTING ';
        }
    
        if (preg_match('/PR/', $tempophenomenon)) {
            $trend .= 'PARTIAL ';
        }
    
        if (preg_match('/BC/', $tempophenomenon)) {
            $trend .= 'PATCHES OF ';
        }
    
        if (preg_match('/GR/', $tempophenomenon)) {
            $trend .= 'HAIL ';
        }
    
        if (preg_match('/GS/', $tempophenomenon)) {
            $trend .= 'SMALL HAIL ';
        }
    
        if (preg_match('/PL/', $tempophenomenon)) {
            $trend .= 'ICE PELLETS ';
        }
    
        if (preg_match('/IC/', $tempophenomenon)) {
            $trend .= 'ICE CRYSTALS ';
        }
    
        if (preg_match('/SG/', $tempophenomenon)) {
            $trend .= 'SNOW GRAINS ';
        }
        
        if (preg_match('/DZRA/', $tempophenomenon)) {
            $trend .= 'DRIZZLE RAIN ';
        } else if (preg_match('/RADZ/', $tempophenomenon)) {
            $trend .= 'RAIN DRIZZLE ';
        } else if (preg_match('/SHRA/', $tempophenomenon)) {
            $trend .= 'SHOWERS OF RAIN ';
        } else if (preg_match('/RA/', $tempophenomenon)) {
            $trend .= 'RAIN ';
        } else if (preg_match('/DZ/', $tempophenomenon)) {
            $trend .= 'DRIZZLE ';
        }
    
        if (preg_match('/BR/', $tempophenomenon)) {
            $trend .= 'MIST ';
        }
    
        if (preg_match('/DS/', $tempophenomenon)) {
            $trend .= 'DUST STORM ';
        }
    
        if (preg_match('/DU/', $tempophenomenon)) {
            $trend .= 'WIDESPREAD DUST ';
        }
    
        if (preg_match('/FC/', $tempophenomenon)) {
            $trend .= 'FUNNEL CLOUD ';
        }
    
        if (preg_match('/FG/', $tempophenomenon)) {
            $trend .= 'FOG ';
        }

        if (preg_match('/FU/', $tempophenomenon)) {
            $trend .= 'SMOKE ';
        }

        if (preg_match('/HZ/', $tempophenomenon)) {
            $trend .= 'HAZE ';
        }

        if (preg_match('/PY/', $tempophenomenon)) {
            $trend .= 'SPRAY ';
        }
    
        if (preg_match('/SA/', $tempophenomenon)) {
            $trend .= 'SAND ';
        }
    
        if (preg_match('/SN/', $tempophenomenon)) {
            $trend .= 'SNOW ';
        }
    
        if (preg_match('/SS/', $tempophenomenon)) {
            $trend .= 'SANDSTORM ';
        }
    
        if (preg_match('/SQ/', $tempophenomenon)) {
            $trend .= 'SQUALL ';
        }
    
        if (preg_match('/UP/', $tempophenomenon)) {
            $trend .= 'UNKNOWN PRECIPITATION ';
        }
    }
    
    if ($tempoweather["NSW"]) {
        $trend .= " .. NO SIGNIFICANT WEATHER";
    }

    if (count($tempoweather["CLOUDS"]) > 0) {
        $trend .= " .. CLOUDS";
    }
    foreach ($tempoweather["CLOUDS"] as $cloud) {
        $trend .= " .. ";

        if ($cloud["TYPE"] == "FEW") {
            $trend .= "FEW";
        } else if ($cloud["TYPE"] == "SCT") {
            $trend .= "SCATTERED";
        } else if ($cloud["TYPE"] == "BKN") {
            $trend .= "BROKEN";
        } else if ($cloud["TYPE"] == "OVC") {
            $trend .= "OVERCAST";
        }

        $trend .= " *" . $cloud["ALT"] . " FEET";

        if ($cloud["EXTRA"] == 'TCU') {
            $trend .= " .. TOWERING CUMULUS";
        } else if ($cloud["EXTRA"] == "CB") {
            $trend .= " .. CUMULONIMBUS";
        }
    }
}

if ($becmgweather["AVAILABLE"]) {
    if (($ap == "EHGG" || $ap == "EHBK" || $ap == "EHAM") && $tempoweather["AVAILABLE"] != true) {
        $trend .= " .. TREND ";
    } else {
        $trend .= " .. ";
    }
    $trend .= "BECOMING";
        if (!empty($becmgweather["WIND"]["DIR"])) {
            if ($becmgweather["WIND"]["SPD"] == "0") {
            $trend .= " .. WIND CALM";
        } else if ($becmgweather["WIND"]["DIR"] == "VARIABLE") {
            $trend .= " .. WIND " . $becmgweather["WIND"]["DIR"];
        } else {
            $trend .= " .. WIND " . $becmgweather["WIND"]["DIR"] . " DEGREES";
        }

        if (($ap == "EHGG" || $ap == "EHBK") && $becmgweather["WIND"]["GST"]) {
            $trend .= " .. GUST " . $becmgweather["WIND"]["GST"] . " KNOTS";
        }

        if ($becmgweather["WIND"]["SPD"] == "1") {
            $trend .= " .. " . $becmgweather["WIND"]["SPD"]  ." KNOT";
        } else if ($becmgweather["WIND"]["SPD"] > "1"){
            $trend .= " .. " . $becmgweather["WIND"]["SPD"]  ." KNOTS";
        }

        if (!empty($becmgweather["WIND"]["MAXDIR"]) && ($ap == "EHKD")) {
            $trend .= " .. VARYING BETWEEN " . $currentweather["WIND"]["MINDIR"] . " AND " . $currentweather["WIND"]["MAXDIR"] . " DEGREES";
        } else if (!empty($becmgweather["WIND"]["MINDIR"])) {
            $trend .= " .. VARIABLE BETWEEN " . $becmgweather["WIND"]["MINDIR"] . " AND " . $becmgweather["WIND"]["MAXDIR"] . " DEGREES";
        }
    }

    if ($becmgweather["VISIBILITY"] == "") {
    } else if ($becmgweather["VISIBILITY"] < "800") {
        $trend .= " .. VISIBILITY " . $becmgweather["VISIBILITY"] . " METERS";
    } else if ($becmgweather["VISIBILITY"] < "5000") {
       $trend .= " .. VISIBILITY *" . $becmgweather["VISIBILITY"] .  " METERS";
    } else if ($becmgweather["VISIBILITY"] < "9999") {
        $trend .= " .. VISIBILITY " . substr($currentweather["VISIBILITY"], 0, 1) . " KILOMETERS";
    } else if ($becmgweather["VISIBILITY"] == "9999") {
        $trend .= " .. VISIBILITY 10 KILOMETERS OR MORE";
    } 

    if ($becmgweather["VV"] == "") {
    } else if ($becmgweather["VV"] == "000") {
        $trend .= " .. VERTICAL VISIBILITY BELOW *100 FEET";
    } else {
        $trend .= " .. VERTICAL VISIBILITY *" . $becmgweather["VV"] . " FEET";
    }
    

    if ($becmgweather["CAVOK"]) {
        $trend .= " .. CAVOK";
    }

    if ($becmgweather["NSC"]) {
        $trend .= " .. NO SIGNIFICANT CLOUDS";
    }

foreach ($becmgweather["PHENOMENA"] as $becmgphenomenon) {
    $trend .= " .. ";

    if (str_contains($becmgphenomenon, '-')) {
        $trend .= 'LIGHT ';
    }
    if (str_contains($becmgphenomenon, '+')) {
        $trend .= 'HEAVY ';
    }
    
    if (preg_match('/FZ/', $becmgphenomenon)) {
        $trend .= 'FREEZING ';
    }

    if (preg_match('/TS/', $becmgphenomenon)) {
        $trend .= 'THUNDERSTORM ';
    }

    if (preg_match('/MI/', $becmgphenomenon)) {
        $trend .= 'SHALLOW ';
    }

    if (preg_match('/BL/', $becmgphenomenon)) {
        $trend .= 'BLOWING ';
    }

    if (preg_match('/DR/', $becmgphenomenon)) {
        $trend .= 'LOW DRIFTING ';
    }

    if (preg_match('/PR/', $becmgphenomenon)) {
        $trend .= 'PARTIAL ';
    }

    if (preg_match('/BC/', $becmgphenomenon)) {
        $trend .= 'PATCHES OF ';
    }

    if (preg_match('/GR/', $becmgphenomenon)) {
        $trend .= 'HAIL ';
    }

    if (preg_match('/GS/', $becmgphenomenon)) {
        $trend .= 'SMALL HAIL ';
    }

    if (preg_match('/PL/', $becmgphenomenon)) {
        $trend .= 'ICE PELLETS ';
    }

    if (preg_match('/IC/', $becmgphenomenon)) {
        $trend .= 'ICE CRYSTALS ';
    }

    if (preg_match('/SG/', $becmgphenomenon)) {
        $trend .= 'SNOW GRAINS ';
    }

    if (preg_match('/DZRA/', $becmgphenomenon)) {
        $trend .= 'DRIZZLE RAIN ';
    } else if (preg_match('/RADZ/', $becmgphenomenon)) {
        $trend .= 'RAIN DRIZZLE ';
    } else if (preg_match('/SHRA/', $becmgphenomenon)) {
        $trend .= 'SHOWERS OF RAIN ';
    } else if (preg_match('/RA/', $becmgphenomenon)) {
        $trend .= 'RAIN ';
    } else if (preg_match('/DZ/', $becmgphenomenon)) {
        $trend .= 'DRIZZLE ';
    }

    if (preg_match('/BR/', $becmgphenomenon)) {
        $trend .= 'MIST ';
    }

    if (preg_match('/DS/', $becmgphenomenon)) {
        $trend .= 'DUST STORM ';
    }

    if (preg_match('/DU/', $becmgphenomenon)) {
        $trend .= 'WIDESPREAD DUST ';
    }

    if (preg_match('/FC/', $becmgphenomenon)) {
        $trend .= 'FUNNEL CLOUD ';
    }

    if (preg_match('/FG/', $becmgphenomenon)) {
        $trend .= 'FOG ';
    }

    if (preg_match('/FU/', $becmgphenomenon)) {
        $trend .= 'SMOKE ';
    }

    if (preg_match('/HZ/', $becmgphenomenon)) {
        $trend .= 'HAZE ';
    }

    if (preg_match('/PO/', $becmgphenomenon)) {
        $trend .= 'DUST ';
    }

    if (preg_match('/PY/', $becmgphenomenon)) {
        $trend .= 'SPRAY ';
    }

    if (preg_match('/SA/', $becmgphenomenon)) {
        $trend .= 'SAND ';
    }

    if (preg_match('/SN/', $becmgphenomenon)) {
        $trend .= 'SNOW ';
    }

    if (preg_match('/SS/', $becmgphenomenon)) {
        $trend .= 'SANDSTORM ';
    }

    if (preg_match('/SQ/', $becmgphenomenon)) {
        $trend .= 'SQUALL ';
    }

    if (preg_match('/UP/', $becmgphenomenon)) {
        $trend .= 'UNKNOWN PRECIPITATION ';
    }
}

    if ($becmgweather["NSW"]) {
        $trend .= " .. NO SIGNIFICANT WEATHER";
    }

    if (count($becmgweather["CLOUDS"]) > 0) {
        $trend .= " .. CLOUDS";
    }

    foreach ($becmgweather["CLOUDS"] as $cloud) {
        $trend .= " .. ";
    
        if ($cloud["TYPE"] == "FEW") {
            $trend .= "FEW";
        } else if ($cloud["TYPE"] == "SCT") {
            $trend .= "SCATTERED";
        } else if ($cloud["TYPE"] == "BKN") {
            $trend .= "BROKEN";
        } else if ($cloud["TYPE"] == "OVC") {
            $trend .= "OVERCAST";
        }
    
        $trend .= " *" . $cloud["ALT"] . " FEET";
    
        if ($cloud["EXTRA"] == 'TCU') {
            $trend .= " .. TOWERING CUMULUS";
        } else if ($cloud["EXTRA"] == "CB") {
            $trend .= " .. CUMULONIMBUS";
        }
    }
}

if ($nosig && ($ap == "EHBK" || $ap == "EHGG" || $ap == "EHKD")) {
    $trend = " .. NOSIG";
} else if ($nosig) {
    $trend = " .. NO SIGNIFICANT CHANGE";
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

$version = $_GET['version'];

$ownremarks = $_GET['own'];
if (preg_match('/./', $ownremarks)) {
    $own = " .. " . strtoupper($ownremarks);
} else {
    $own = "";
}

$t = gmdate("Gi");
$timestamp = sprintf('%04d', $t);

if (preg_match('/[0-9][0-9][0-9][0-9][DNU]/', $metar, $rvrmatch)) {
    $rvr = substr($rvrmatch[0], 0, 4);
} else {
    $rvr = '9999';
}

if (array_key_exists(3, $currentweather["RVRVALUES"])) {
    $rvr = min($currentweather["RVRVALUES"][3]["VIS"],$currentweather["RVRVALUES"][2]["VIS"],$currentweather["RVRVALUES"][1]["VIS"],$currentweather["RVRVALUES"][0]["VIS"]);
} else if (array_key_exists(2, $currentweather["RVRVALUES"])) {
    $rvr = min($currentweather["RVRVALUES"][2]["VIS"],$currentweather["RVRVALUES"][1]["VIS"],$currentweather["RVRVALUES"][0]["VIS"]);
} else if (array_key_exists(1, $currentweather["RVRVALUES"])) {
    $rvr = min($currentweather["RVRVALUES"][1]["VIS"],$currentweather["RVRVALUES"][0]["VIS"]);
} else if (array_key_exists(0, $currentweather["RVRVALUES"])) {
    $rvr = $currentweather["RVRVALUES"][0]["VIS"];
} else {
    $rvr = "9999";
}

if (str_starts_with($ap, "EH")) {
    $trl = intval(ceil((307.8 - 0.13986 * $currentweather["TEMPERATURE"] - 0.26224 * $currentweather["QNH"]) / 5) * 5);

    $arrrwy = $_GET['arr'];

    // Runway Condition Reports
    if ($ap == "EHEH") {
        $rwycondition = "RUNWAY CONDITION IS DRY";
    } else {
        $rwycondition = "";
    }
    foreach ($currentweather["PHENOMENA"] as $phenomenon) {
        if ($ap == "EHEH") {
            if (preg_match('/DZ/', $phenomenon)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. 75 PERCENT WET";
            } else if (preg_match('/RA|SH/', $phenomenon)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT WET";
            } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT, FROST";
            } else if (preg_match('/FZFG/', $phenomenon)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT, FROST";
            } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. 75 PERCENT, FROST";
            } else if (preg_match('/SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. *100 PERCENT DRY SNOW 10 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT DRY SNOW 5 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. *100 PERCENT DRY SNOW 15 MILLIMETERS";
            } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. *100 PERCENT WET SNOW 8 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT WET SNOW 4 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = "RUNWAY CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. *100 PERCENT WET SNOW 12 MILLIMETERS";
            }
        } else if ($ap == "EHKD") {
            if (preg_match('/DZ/', $phenomenon)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. 75 PERCENT WET";
            } else if (preg_match('/RA|SH/', $phenomenon)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT WET";
            } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT, FROST";
            } else if (preg_match('/FZFG/', $phenomenon)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT, FROST";
            } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. 75 PERCENT, FROST";
            } else if (preg_match('/SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. *100 PERCENT DRY SNOW 10 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT DRY SNOW 5 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. *100 PERCENT DRY SNOW 15 MILLIMETERS";
            } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. *100 PERCENT WET SNOW 8 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. *100 PERCENT WET SNOW 4 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. *100 PERCENT WET SNOW 12 MILLIMETERS";
            }
        } else {
            if (preg_match('/DZ/', $phenomenon)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
            } else if (preg_match('/RA|SH/', $phenomenon)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
            } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/FZFG/', $phenomenon)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
            } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
            } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $rwycondition = " .. RUNWAY " . $arrrwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
            }
        }
    }

    if ($ap === "EHAM" && $version == "A") {
        define("app", [
            'ils' => ' .. ILS APPROACH',
            'sra' => ' .. SURVEILLANCE RADAR APPROACH',
            'rnp' => " .. RNP APPROACH",
            'vis' => " .. VISUAL APPORACH",
            '' => '',
        ]);
        $app1 = app[strtolower($_GET['apt1'])];

        //Preferential Runway System - Arrivals
        $arr = $_GET['arr'];
        $arrrwys = explode(',', $arr);
        if (in_array('18R', $arrrwys)) {
            $arr_pri = '18R';
        } else if (in_array('06', $arrrwys)) {
            $arr_pri = '06';
        } else if (in_array('36R', $arrrwys)) {
            $arr_pri = '36R';
        } else if (in_array('18C', $arrrwys)) {
            $arr_pri = '18C';
        } else if (in_array('36C', $arrrwys)) {
            $arr_pri = '36C';
        } else if (in_array('27', $arrrwys)) {
            $arr_pri = '27';
        } else {
            $arr_pri = $arrrwys[0];
        }

        if (!empty($arr_pri)) {
            $arrrwy = " .. MAIN LANDING RUNWAY " . $arr_pri . $app1;
        }

        $ldgrwycondition = "";
        
        foreach ($currentweather["PHENOMENA"] as $phenomenon) {
            if (preg_match('/DZ/', $phenomenon)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
            } else if (preg_match('/RA|SH/', $phenomenon)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
            } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/FZFG/', $phenomenon)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
            } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
            } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
            }
        }

        foreach ($arrrwys as $rwy) {
            if ($rwy == $arr_pri) {
            } else if ($rwy == "22" || $rwy == "04") {
            } else {
                $app2 = app[strtolower($_GET['apt2'])];
                $arrrwy .= " .. SECONDARY LANDING RUNWAY " . $rwy . $app2;
                foreach ($currentweather["PHENOMENA"] as $phenomenon) {
                    if (preg_match('/DZ/', $phenomenon)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
                    } else if (preg_match('/RA|SH/', $phenomenon)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
                    } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/FZFG/', $phenomenon)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
                    } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
                    } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
                    }
                }
            }
        }

        $convergingapproach = false;
        if (in_array('36R', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('27', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('09', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('18C', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('18R', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('09', $arrrwys) && in_array('04', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('06', $arrrwys) && in_array('04', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('18C', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('18R', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('36C', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('36R', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        }

        if ($convergingapproach) {
            $convergingapproaches = "CONVERGING APPROACHES IN PROGRESS .. ";
        } else {
            $convergingapproaches = "";
        }

        $parallelapproach = false;
        if (in_array('18R', $arrrwys) && in_array('18C', $arrrwys)) {
            $parallelapproach = true;
        } else if (in_array('18R', $arrrwys) && in_array('36C', $arrrwys)) {
            $parallelapproach = true;
        } else if (in_array('36C', $arrrwys) && in_array('36R', $arrrwys)) {
            $parallelapproach = true;
        } else if (in_array('18R', $arrrwys) && in_array('36R', $arrrwys)) {
            $parallelapproach = true;
        }

        if ($parallelapproach) {
            $parallelapproaches = "INDEPENDENT PARALLEL APPROACHES IN PROGRESS .. ";
        } else {
            $parallelapproaches = "";
        }

        //Operational Reports
        // BZO
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] < "550" || ($rvr < "1500")) {
            $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }

        //CONTACT APP/ARR CALLSIGN ONLY
        if (in_array("EHAM_A_APP", $onlineATC) || in_array("EHAM__A_APP", $onlineATC) || in_array("EHAM_A__APP", $onlineATC) || in_array("EHAM_S_APP", $onlineATC) || in_array("EHAM__S_APP", $onlineATC) || in_array("EHAM_S__APP", $onlineATC)) {
            $cs_only = "CONTACT APPROACH AND ARRIVAL WITH CALLSIGN ONLY .. ";
        } else {
            $cs_only = "CONTACT APPROACH WITH CALLSIGN ONLY .. ";
        }

        //Output
        echo "THIS IS SCHIPHOL ARRIVAL INFORMATION " . $cycle . $arrrwy . " .. TRANSITION LEVEL " . $trl . $ldgrwycondition . $weather . $trend . " .. OPERATIONAL REPORT .. " . $bzo . $parallelapproaches . $convergingapproaches . $cs_only . $own . "CONFIRM INFORMATION " . $cycle . " ON FIRST CONTACT";

        //EUROSCOPE Link: https://olafblom.nl/atis/atis.php?version=A&arr=$arrrwy(EHAM)&dep=$deprwy(EHAM)&wx=$metar(EHAM)&atis=$atiscode&apt1=&apt2=&own=
    } else if ($ap === "EHAM" && $version == "D") {
        //Preferential Runway System - Departures
        $dep = $_GET['dep'];
        $deprwys = explode(',', $dep);
        if (in_array('36L', $deprwys)) {
            $dep_pri = '36L';
        } else if (in_array('24', $deprwys)) {
            $dep_pri = '24';
        } else if (in_array('36C', $arrrwys)) {
            $dep_pri = '36C';
        } else if (in_array('18L', $deprwys)) {
            $dep_pri = '18L';
        } else if (in_array('18C', $deprwys)) {
            $dep_pri = '18C';
        } else if (in_array('09', $deprwys)) {
            $dep_pri = '09';
        } else {
            $dep_pri = $deprwys[0];
        }

        $deprwycondition = "";

        foreach ($currentweather["PHENOMENA"] as $phenomenon) {
            if (preg_match('/DZ/', $phenomenon)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
            } else if (preg_match('/RA|SH/', $phenomenon)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
            } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/FZFG/', $phenomenon)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
            } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
            } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $deprwycondition .= " .. RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
            }
        }

        if (!empty($dep_pri)) {
            $deprwy = " .. MAIN DEPARTING RUNWAY " . $dep_pri;
        }
        foreach ($deprwys as $rwy) {
            if ($rwy == $dep_pri) {
            } else if ($rwy == "22" || $rwy == "04") {
            } else {
                $deprwy .= " .. SECONDARY DEPARTING RUNWAY " . $rwy;
                foreach ($currentweather["PHENOMENA"] as $phenomenon) {
                    if (preg_match('/DZ/', $phenomenon)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
                    } else if (preg_match('/RA|SH/', $phenomenon)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
                    } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/FZFG/', $phenomenon)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
                    } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
                    } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $deprwycondition .= " .. SECONDARY RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
                    }
                }
            }
        }

        $paralleldep = false;
        if (in_array('36L', $deprwys) && in_array('36C', $deprwys)) {
            $paralleldep = true;
        } else if (in_array('36L', $deprwys) && in_array('18L', $deprwys)) {
            $paralleldep = true;
        } else if (in_array('36C', $deprwys) && in_array('18L', $deprwys)) {
            $paralleldep = true;
        } else if (in_array('18C', $deprwys) && in_array('18L', $deprwys)) {
            $paralleldep = true;
        }

        if ($paralleldep) {
            $paralleldeps = "INDEPENDENT PARALLEL DEPARTURES IN PROGRESS .. ";
        } else {
            $paralleldeps = "";
        }

        //Preferential Runway System - Arrivals
        $arr = $_GET['arr'];
        $arrrwys = explode(',', $arr);
        if (in_array('18R', $arrrwys)) {
            $arr_pri = '18R';
        } else if (in_array('06', $arrrwys)) {
            $arr_pri = '06';
        } else if (in_array('36R', $arrrwys)) {
            $arr_pri = '36R';
        } else if (in_array('18C', $arrrwys)) {
            $arr_pri = '18C';
        } else if (in_array('36C', $arrrwys)) {
            $arr_pri = '36C';
        } else if (in_array('27', $arrrwys)) {
            $arr_pri = '27';
        } else {
            $arr_pri = $arrrwys[0];
        }

        if (!empty($arr_pri)) {
            $arrrwy = " .. MAIN LANDING RUNWAY " . $arr_pri;
        }

        //Operational Reports
        // BZO
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] < "550" || ($rvr < "1500")) {
            $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if ($currentweather["VISIBILITY"] <= "1500" || ($rvr < "1500")) {
            $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "1500") {
                $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
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
        echo "THIS IS SCHIPHOL DEPARTURE INFORMATION " . $cycle . $deprwy . $arrrwy . $deprwycondition . $weather . $trend . " .. OPERATIONAL REPORT .. " . $paralleldeps . $bzo . "NOISE ABATEMENT N-A-D-P 2 PROCEDURES SHALL BE APPLIED" . $own . " .. CONFIRM INFORMATION " . $cycle . " ON FIRST CONTACT";

        //EUROSCOPE Link: https://olafblom.nl/atis/atis.php?version=D&arr=$arrrwy(EHAM)&dep=$deprwy(EHAM)&wx=$metar(EHAM)&atis=$atiscode&apt1=&apt2=&own=
    } else if ($ap === "EHAM") {
        define("app", [
            'ils' => ' .. ILS APPROACH',
            'sra' => ' .. SURVEILLANCE RADAR APPROACH',
            'rnp' => " .. RNP APPROACH",
            'vis' => " .. VISUAL APPORACH",
            '' => '',
        ]);
        $app1 = app[strtolower($_GET['apt1'])];

        //Preferential Runway System - Arrivals
        $arr = $_GET['arr'];
        $arrrwys = explode(',', $arr);
        if (in_array('18R', $arrrwys)) {
            $arr_pri = '18R';
        } else if (in_array('06', $arrrwys)) {
            $arr_pri = '06';
        } else if (in_array('36R', $arrrwys)) {
            $arr_pri = '36R';
        } else if (in_array('18C', $arrrwys)) {
            $arr_pri = '18C';
        } else if (in_array('36C', $arrrwys)) {
            $arr_pri = '36C';
        } else if (in_array('27', $arrrwys)) {
            $arr_pri = '27';
        } else {
            $arr_pri = $arrrwys[0];
        }

        if (!empty($arr_pri)) {
            $arrrwy = " .. MAIN LANDING RUNWAY " . $arr_pri . $app1;
        }

        $ldgrwycondition = "";
        foreach ($currentweather["PHENOMENA"] as $phenomenon) {
            if (preg_match('/DZ/', $phenomenon)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
            } else if (preg_match('/RA|SH/', $phenomenon)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
            } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/FZFG/', $phenomenon)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
            } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
            } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $ldgrwycondition .= " .. LANDING RUNWAY " . $arr_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
            }
        }

        foreach ($arrrwys as $rwy) {
            if ($rwy == $arr_pri) {
            } else if ($rwy == "22" || $rwy == "04") {
            } else {
                $app2 = app[strtolower($_GET['apt2'])];
                $arrrwy .= " .. SECONDARY LANDING RUNWAY " . $rwy . $app2;
                
                foreach ($currentweather["PHENOMENA"] as $phenomenon) {
                    if (preg_match('/DZ/', $phenomenon)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
                    } else if (preg_match('/RA|SH/', $phenomenon)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
                    } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/FZFG/', $phenomenon)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
                    } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
                    } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $ldgrwycondition .= " .. SECONDARY LANDING RUNWAY " . $rwy . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
                    }
                }
            }
        }

        $convergingapproach = false;
        if (in_array('36R', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('27', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('09', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('18C', $arrrwys) && in_array('06', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('09', $arrrwys) && in_array('04', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('06', $arrrwys) && in_array('04', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('18C', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('18R', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('36C', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        } else if (in_array('36R', $arrrwys) && in_array('27', $arrrwys)) {
            $convergingapproach = true;
        } else if ((in_array('22', $arrrwys)) && (in_array('27', $arrrwys))) {
            $convergingapproach = true;
        }

        if ($convergingapproach) {
            $convergingapproaches = "CONVERGING APPROACHES IN PROGRESS .. ";
        } else {
            $convergingapproaches = "";
        }

        $parallelapproach = false;
        if (in_array('18R', $arrrwys) && in_array('18C', $arrrwys)) {
            $parallelapproach = true;
        } else if (in_array('18R', $arrrwys) && in_array('36C', $arrrwys)) {
            $parallelapproach = true;
        } else if (in_array('36C', $arrrwys) && in_array('36R', $arrrwys)) {
            $parallelapproach = true;
        } else if (in_array('18R', $arrrwys) && in_array('36R', $arrrwys)) {
            $parallelapproach = true;
        }

        if ($parallelapproach) {
            $parallelapproaches = "INDEPENDENT PARALLEL APPROACHES IN PROGRESS .. ";
        } else {
            $parallelapproaches = "";
        }

        //Preferential Runway System - Departures
        $dep = $_GET['dep'];
        $deprwys = explode(',', $dep);
        if (in_array('36L', $deprwys)) {
            $dep_pri = '36L';
        } else if (in_array('24', $deprwys)) {
            $dep_pri = '24';
        } else if (in_array('36C', $deprwys)) {
            $dep_pri = '36C';
        } else if (in_array('18L', $deprwys)) {
            $dep_pri = '18L';
        } else if (in_array('18C', $deprwys)) {
            $dep_pri = '18C';
        } else if (in_array('09', $deprwys)) {
            $dep_pri = '09';
        } else {
            $dep_pri = $deprwys[0];
        }

        $deprwycondition = "";
        
        foreach ($currentweather["PHENOMENA"] as $phenomenon) {
            if (preg_match('/DZ/', $phenomenon)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
            } else if (preg_match('/RA|SH/', $phenomenon)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
            } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/FZFG/', $phenomenon)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
            } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
            } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
            } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
            } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
            } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                $deprwycondition .= " .. DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
            }
        }

        if (!empty($dep_pri)) {
            $deprwy = " .. MAIN DEPARTING RUNWAY " . $dep_pri;
        }
        foreach ($deprwys as $rwy) {
            if ($rwy == $dep_pri) {
            } else if ($rwy == "22" || $rwy == "04") {
            } else {
                $deprwy .= " .. SECONDARY DEPARTING RUNWAY " . $rwy;
                foreach ($currentweather["PHENOMENA"] as $phenomenon) {
                    if (preg_match('/DZ/', $phenomenon)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET .. SECOND PART 75 PERCENT WET .. THIRD PART 75 PERCENT WET";
                    } else if (preg_match('/RA|SH/', $phenomenon)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT WET .. SECOND PART *100 PERCENT WET .. THIRD PART *100 PERCENT WET";
                    } else if (preg_match('/FG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/FZFG/', $phenomenon)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART *100 PERCENT, FROST .. SECOND PART *100 PERCENT, FROST .. THIRD PART *100 PERCENT, FROST";
                    } else if (preg_match('/BR/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT, FROST .. SECOND PART 75 PERCENT, FROST .. THIRD PART 75 PERCENT, FROST";
                    } else if (preg_match('/SN|SG/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 10 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 10 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. SECOND PART 75 PERCENT DRY SNOW 5 MILLIMETERS .. THIRD PART 75 PERCENT DRY SNOW 5 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && preg_match('/M[0-9]{2}\//', $metar)) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. SECOND PART *100 PERCENT DRY SNOW 15 MILLIMETERS .. THIRD PART *100 PERCENT DRY SNOW 15 MILLIMETERS";
                    } else if (preg_match('/SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 4 4 4 .. FIRST PART *100 PERCENT WET SNOW 8 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 8 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 8 MILLIMETERS";
                    } else if (preg_match('/\-SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 5 5 5 .. FIRST PART 75 PERCENT WET SNOW 4 MILLIMETERS .. SECOND PART 75 PERCENT WET SNOW 4 MILLIMETERS .. THIRD PART 75 PERCENT DWETY SNOW 4 MILLIMETERS";
                    } else if (preg_match('/\+SN/', $phenomenon) && (preg_match('/[0-9]{2}\//', $metar) || preg_match('/RA/', $phenomenon))) {
                        $deprwycondition .= " .. SECONDARY DEPARTING RUNWAY " . $dep_pri . " CONDITION REPORT AT TIME " . $timestamp . " .. RUNWAY CONDITION CODE 3 3 3 .. FIRST PART *100 PERCENT WET SNOW 12 MILLIMETERS .. SECOND PART *100 PERCENT WET SNOW 12 MILLIMETERS .. THIRD PART *100 PERCENT DWETY SNOW 12 MILLIMETERS";
                    }
                }
            }
        }

        $paralleldep = false;
        if (in_array('36L', $deprwys) && in_array('36C', $deprwys)) {
            $paralleldep = true;
        } else if (in_array('36L', $deprwys) && in_array('18L', $deprwys)) {
            $paralleldep = true;
        } else if (in_array('36C', $deprwys) && in_array('18L', $deprwys)) {
            $paralleldep = true;
        } else if (in_array('18C', $deprwys) && in_array('18L', $deprwys)) {
            $paralleldep = true;
        }

        if ($paralleldep) {
            $paralleldeps = "INDEPENDENT PARALLEL DEPARTURES IN PROGRESS .. ";
        } else {
            $paralleldeps = "";
        }

        $rwycondition = $ldgrwycondition . $deprwycondition;

        //Operational Reports
        // BZO
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] < "550" || ($rvr < "1500")) {
            $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling < "002") {
                $bzo = 'LOW VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if ($currentweather["VISIBILITY"] <= "1500" || ($rvr < "1500")) {
            $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "1500") {
                $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = 'REDUCED VISIBILITY PROCEDURES IN PROGRESS .. ';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }

        //CONTACT APP/ARR CALLSIGN ONLY
        if (in_array("EHAM_A_APP", $onlineATC) || in_array("EHAM__A_APP", $onlineATC) || in_array("EHAM_A__APP", $onlineATC) || in_array("EHAM_S_APP", $onlineATC) || in_array("EHAM__S_APP", $onlineATC) || in_array("EHAM_S__APP", $onlineATC)) {
            $cs_only = "CONTACT APPROACH AND ARRIVAL WITH CALLSIGN ONLY .. ";
        } else {
            $cs_only = "CONTACT APPROACH WITH CALLSIGN ONLY .. ";
        }

        //Output
        echo "THIS IS SCHIPHOL INFORMATION " . $cycle . $arrrwy . $deprwy . " .. TRANSITION LEVEL " . $trl . $rwycondition . $weather . $trend . " .. OPERATIONAL REPORT .. " . $bzo . $parallelapproaches . $convergingapproaches . $paralleldeps . $cs_only . "NOISE ABATEMENT N-A-D-P 2 PROCEDURES SHALL BE APPLIED" . $own . " .. CONFIRM INFORMATION " . $cycle . " ON FIRST CONTACT";

        //EUROSCOPE Link: https://olafblom.nl/atis/atis.php?version=B&arr=$arrrwy(EHAM)&dep=$deprwy(EHAM)&wx=$metar(EHAM)&atis=$atiscode&apt1=&apt2=&own=
    } else if ($ap === "EHBK") {
        define("app", [
            'ilsx' => ' .. ILS X RAY APPROACH',
            'ilsy' => ' .. RNP APPROACH',
            'ndb' => ' .. N D B APPROACH',
            'vis' => ' .. VISUAL APPROACH',
            '' => '',
        ]);
        $app_type = app[strtolower($_GET['apt1'])];

        //Operational Reports
        // First Frequency
        if (in_array("EHBK_DEL", $onlineATC) || in_array("EHBK__DEL", $onlineATC)) {
            $first_freq = "";
        } else if (in_array("EHBK_TWR", $onlineATC) || in_array("EHBK__TWR", $onlineATC)) {
            $first_freq = " .. FOR STARTUP CONTACT BEEK TOWER ON FREQUENCY 118.480";
        } else if (in_array("EHBK_APP", $onlineATC) || in_array("EHBK__APP", $onlineATC)) {
            $first_freq = " .. FOR STARTUP CONTACT BEEK APPROACH ON FREQUENCY 123.980";
        } else if (in_array("EHAA_S_CTR", $onlineATC) || in_array("EHAA_S__CTR", $onlineATC) || in_array("EHAA__S_CTR", $onlineATC)) {
            $first_freq = " .. FOR STARTUP CONTACT AMSTERDAM RADAR ON FREQUENCY 123.850";
        } else if (in_array("EHAA_E_CTR", $onlineATC) || in_array("EHAA_E__CTR", $onlineATC) || in_array("EHAA__E_CTR", $onlineATC)) {
            $first_freq = " .. FOR STARTUP CONTACT AMSTERDAM RADAR ON FREQUENCY 124.880";
        } else if (in_array("EHAA_W_CTR", $onlineATC) || in_array("EHAA_W__CTR", $onlineATC) || in_array("EHAA__W_CTR", $onlineATC)) {
            $first_freq = " .. FOR STARTUP CONTACT AMSTERDAM RADAR ON FREQUENCY 125.750";
        } else {
            $first_freq = "";
        }

        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $metar, $visibilitymatch);

        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] <= "2000" || $rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }

        if (str_contains($bzo, 'LOW VISIBILITY') || str_contains($first_freq, ' .. FOR STARTUP') || preg_match('/[.]/', $own)) {
            $sitrep = ' .. OPERATIONAL REPORT';
        } else {
            $sitrep = '';
        }

        //Output
        echo "THIS IS MAASTRICHT AACHEN INFORMATION " . $cycle . " .. MAIN LANDING RUNWAY " . $arrrwy . $app_type . " .. TRANSITION LEVEL " . $trl . $rwycondition . $weather . $trend . $sitrep . $bzo . $first_freq . $own . " .. ACKNOWLEDGE INFORMATION " . $cycle;

        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?version=B&arr=$arrrwy(EHBK)&wx=$metar(EHBK)&atis=$atiscode&apt1=&own=
    } else if ($ap === "EHEH") {
        define("app", [
            'ilsx' => 'EXPECT C D O APPROACH TO ILS X RAY .. ',
            'ilsy' => 'EXPECT RADAR VECTORING TO ILS YANKEE APPROACH .. ',
            'vis' => 'EXPECT VISUAL APPROACH .. ',
            '' => 'EXPECT C D O APPROACH TO ILS X RAY .. ',
        ]);
        $app_type = app[strtolower($_GET['apt1'])];

        //Operational Reports
        // BZO
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] <= "1500" || $rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if (preg_match('/BKN[0-9]{3}/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9]{3}/', $metar, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else {
            $bzo = '';
        }
        
        if (str_contains($bzo, 'LOW VISIBILITY')) {
            $sitrep = ' .. OPERATIONAL REPORT';
        } else {
            $sitrep = '';
        }

        //Output
        echo  "THIS IS EINDHOVEN INFORMATION " . $cycle . " .. " . $time . " .. " . $app_type . "RUNWAY " . $arrrwy . " .. FOR ARRIVAL, AND DEPARTURE .. " . $rwycondition .  " .. TRANSITION LEVEL " . $trl . $own . $weather . $sitrep . $bzo . " .. CONFIRM EINDHOVEN INFORMATION " . $cycle . " ON FIRST CONTACT";

        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?version=B&arr=$arrrwy(EHEH)&wx=$metar(EHEH)&atis=$atiscode&apt1=&own=
    } else if ($ap === "EHGG") {
        define("app", [
            'ils' => 'ILS APPROACH .. ',
            'rnp' => 'RNP APPROACH .. ',
            'vor' => 'VOR APPROACH .. ',
            'vis' => 'VISUAL APPROACH .. ',
            '' => '',
        ]);
        $app_type = app[strtolower($_GET['apt1'])];

        $arrrwy = $_GET['arr'];

        //Operational Reports
        //LOW QNH
        if ($currentweather["QNH"] < "1000") {
            $lowqnh = " .. CAUTION LOW QNH .. CHECK I F R TRANSITION ALTITUDE IS *3000 FEET";
        } else {
            $lowqnh = "";
        }

        // BZO
        preg_match('/ [0-9][0-9][0-9][0-9] /', $metar, $visibilitymatch);
        
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] <= "2000" || $rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN OPERATION ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN OPERATION ';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
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
        echo "THIS IS EELDE INFORMATION " . $cycle . " .. MAIN LANDING RUNWAY " . $arrrwy . " .. " . $app_type . "TRANSITION LEVEL " . $trl . $rwycondition . $weather . $trend . $sitrep . $bzo . $own . " .. ACKNOWLEDGE INFORMATION " . $cycle;
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?version=B&arr=$arrrwy(EHGG)&wx=$metar(EHGG)&atis=$atiscode&apt1=&own=
    } else if ($ap === "EHKD") {
        define("app", [
            'ils' => ' .. EXPECT ILS APPROACH .. ',
            'rnp' => ' .. EXPECT RNP APPROACH .. ',
            '' => ' .. EXPECT ILS APPROACH .. ',
        ]);
        $app_type = app[strtolower($_GET['apt1'])];

        $arrrwy = $_GET['arr'];

        //Operational Reports
        // BZO
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] <= "1500" || $rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
            $ceiling = substr($ovcmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/LVP/', $metar)) {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
        }
        else {
            $bzo = '';
        }

        //Output
        echo "THIS IS DE KOOY INFORMATION " . $cycle . " .. " . $time . $app_type . "RUNWAY " . $arrrwy . " .. FOR ARRIVAL, AND DEPARTURE" . $rwycondition . " .. TRANSITION LEVEL " . $trl . $own . " .. BIRD STATUS ALERT" . $bzo . $weather . " .. ACKNOWLEDGE INFORMATION " . $cycle . " ON FIRST CONTACT";

        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?version=B&arr=$arrrwy(EHKD)&wx=$metar(EHKD)&atis=$atiscode&apt1=&own=
    } else if ($ap === "EHLE") {
        define("app", [
            'ils' => 'EXPECT ILS APPROACH .. ',
            'rnp' => 'EXPECT RNP APPROACH .. ',
            '' => '',
        ]);
        $app_type = app[strtolower($_GET['apt1'])];

        $arrrwy = $_GET['arr'];
        
        //Operational Reports
        // BZO
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] <= "2000" || $rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "002") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
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
        echo "THIS IS LELYSTAD INFORMATION " . $cycle . " .. MAIN LANDING RUNWAY " . $arrrwy . " .. " . $app_type . " REPORT FULL INTENTIONS AT START UP .. TRANSITION LEVEL " . $trl . $weather . $trend . $sitrep . $bzo . $own . " .. ACKNOWLEDGE INFORMATION " . $cycle;
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?version=B&arr=$arrrwy(EHLE)&wx=$metar(EHLE)&atis=$atiscode&apt1=&own=
    } else if ($ap === "EHRD") {
        define("app", [
            'ils' => 'ILS APPROACH .. ',
            'rnp' => 'RNP APPROACH .. ',
            'vor' => 'VOR APPROACH .. ',
            'visual' => 'VISUAL APPROACH .. ',
            '' => '',
        ]);
        $app_type = app[strtolower($_GET['apt1'])];
        
        $arrrwy = $_GET['arr'];
        
        //LOW QNH
        if ($currentweather["QNH"] < "1000") {
            $lowqnh = " .. CAUTION LOW QNH .. CHECK I F R TRANSITION ALTITUDE IS *3000 FEET";
        } else {
            $lowqnh = '';
        }
        
        //Operational Reports
        // BZO
        if ($currentweather["CAVOK"]) {
            $bzo = "";
        } else if ($currentweather["VISIBILITY"] <= "1500" || $rvr <= "1500") {
            $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS ';
        } else if (preg_match('/BKN[0-9][0-9][0-9]/', $metar, $bknmatch)) {
            $ceiling = substr($bknmatch[0], 3, 3);
            if ($ceiling <= "003") {
                $bzo = ' .. LOW VISIBILITY PROCEDURES IN PROGRESS';
            } else {
                $bzo = '';
            }
        } else if (preg_match('/OVC[0-9][0-9][0-9]/', $metar, $ovcmatch)) {
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
        echo "THIS IS ROTTERDAM INFORMATION " . $cycle . " .. MAIN LANDING RUNWAY " . $arrrwy . " .. " . $app_type . "TRANSITION LEVEL " . $trl . $lowqnh . $rwycondition . $weather . $trend . $sitrep . $bzo . $own . " .. ACKNOWLEDGE INFORMATION " . $cycle;
        
        //EUROSCOPE Link: http://www.olafblom.nl/atis/atis.php?version=B&arr=$arrrwy(EHRD)&wx=$metar(EHRD)&atis=$atiscode&apt1=&own=
    } else {
        echo "NO ATIS AVAILABLE FOR THIS AIRPORT";
    }
} else {
    echo "NO ATIS AVAILABLE FOR THIS AIRPORT";
}