<?php
$metar = $_GET['wx'];
$trendsplit = preg_split('/BEC|TEM|NOS/', $metar);

$currentweather = array(
    "WIND" => array(
        "DIR" => "",
        "SPD" => "",
        "GST" => "",
        "MINDIR" => "",
        "MAXDIR" => "",
    ),
    "CLOUDS" => array(),
    "PHENOMENA" => array(),
    "VISIBILITY" => "",
    "VV" => "",
    "QNH" => "",
    "TEMPERATURE" => "",
    "DEWPOINT" => "",
    "RVRVALUES" => array(
        "RWY" => "",
        "PLUS" => false,
        "LESS" => false,
        "VIS" => "",
        "TREND" => "",
    ),
    "RVR" => false,
    "NSC" => false,
    "NSW" => false,
    "NCD" => false,
    "SKC" => false,
    "CAVOK" => false,
    "AVAILABLE" => false,
);
$becmgweather = $currentweather;
$tempoweather = $currentweather;

$nosig = false;

foreach ($trendsplit as $items) {
    $items = explode(' ', $items);
    if ($items[0] === "MG") {
        $becmgweather["AVAILABLE"] = true;
        $trendtype = "BECOMING";
    } else if ($items[0] === "PO") {
        $tempoweather["AVAILABLE"] = true;
        $trendtype = "TEMPORARY";
    } else if ($items[0] ===  "IG") {
        $nosig = true;
    } else {
        $trendtype = "CURRENT";
    }

    foreach ($items as $item) {
        if (preg_match('/^[0-9]{5}KT$/', $item) || preg_match('/^VRB[0-9]{2}KT$/', $item) || preg_match('/^[0-9]{5}G[0-9]{2}KT$/', $item) || preg_match('/^VRB[0-9]{2}G[0-9]{2}KT$/', $item)) {
            if ($trendtype === "CURRENT") {
                if (str_starts_with($item, "VRB")) {
                    $currentweather["WIND"]["DIR"] = "VARIABLE";
                } else {
                    $currentweather["WIND"]["DIR"] = substr($item, 0, 3);
                }
                $currentweather["WIND"]["SPD"] = intval(substr($item, 3, 2));
                if (strlen($item) > 7) {
                    $currentweather["WIND"]["GST"] = intval(substr($item, 6, 2));
                }
            } else if ($trendtype === "BECOMING") {
                if (str_starts_with($item, "VRB")) {
                    $becmgweather["WIND"]["DIR"] = "VARIABLE";
                } else {
                    $becmgweather["WIND"]["DIR"] = substr($item, 0, 3);
                }
                $becmgweather["WIND"]["SPD"] = intval(substr($item, 3, 2));
                if (strlen($item) > 7) {
                    $becmgweather["WIND"]["GST"] = intval(substr($item, 6, 2));
                }
            } else if ($trendtype === "TEMPORARY") {
                if (str_starts_with($item, "VRB")) {
                    $tempoweather["WIND"]["DIR"] = "VARIABLE";
                } else {
                    $tempoweather["WIND"]["DIR"] = substr($item, 0, 3);
                }
                $tempoweather["WIND"]["SPD"] = intval(substr($item, 3, 2));
                if (strlen($item) > 7) {
                    $tempoweather["WIND"]["GST"] = intval(substr($item, 6, 2));
                }
            }
        } else if (preg_match('/^[0-9]{3}V[0-9]{3}$/', $item)) {
            if ($trendtype === "CURRENT") {
                $currentweather["WIND"]["MINDIR"] = substr($item, 0, 3);
                $currentweather["WIND"]["MAXDIR"] = substr($item, 4, 3);
            }
            if ($trendtype === "BECOMING") {
                $becmgweather["WIND"]["MINDIR"] = substr($item, 0, 3);
                $becmgweather["WIND"]["MAXDIR"] = substr($item, 4, 3);
            }
            if ($trendtype === "TEMPORARY") {
                $tempoweather["WIND"]["MINDIR"] = substr($item, 0, 3);
                $tempoweather["WIND"]["MAXDIR"] = substr($item, 4, 3);
            }
        } else if (preg_match('/^[0-9]{4}$/', $item)) {
            if ($trendtype === "CURRENT") {
                $currentweather["VISIBILITY"] = intval($item);
            } else if ($trendtype === "BECOMING") {
                $becmgweather["VISIBILITY"] = intval($item);
            } else if ($trendtype === "TEMPORARY") {
                $tempoweather["VISIBILITY"] = intval($item);
            }
        } else if (preg_match('/^Q[0-9]{4}$/', $item)) {
            $currentweather["QNH"] = intval(substr($item, 1, 4));
        } else if (preg_match('/[M]*[0-9]{2}\/[M]*[0-9]{2}/', $item)) {
            $tempdp = explode('/', $item);
            $currentweather["TEMPERATURE"] = intval(str_replace("M", "-", $tempdp[0]));
            $currentweather["DEWPOINT"] = intval(str_replace("M", "-", $tempdp[1]));
        } else if (preg_match('/R[0-9]{2}/', $item)) {
            $currentweather["RVR"] = true;
            if (preg_match('/\/[0-9]{4}/', $item)) {
                array_push($currentweather["RVRVALUES"], array(
                    "RWY" => substr($item, 1, strpos($item, '/')-1),
                    "PLUS" => false,
                    "LESS" => false,
                    "VIS" => intval(substr($item, strpos($item, '/')+1, 4)),
                    "TREND" => substr($item, strpos($item, '/')+5),
                ));
            } else if (preg_match('/\/M[0-9]{4}/', $item)) {
                array_push($currentweather["RVRVALUES"], array(
                    "RWY" => substr($item, 1, strpos($item, '/')-1),
                    "PLUS" => false,
                    "LESS" => true,
                    "VIS" => intval(substr($item, strpos($item, 'M')+1, 4)),
                    "TREND" => substr($item, strpos($item, 'M')+5),
                ));
            } else if (preg_match('/\/P[0-9]{4}/', $item)) {
                array_push($currentweather["RVRVALUES"], array(
                    "RWY" => substr($item, 1, strpos($item, '/')-1),
                    "PLUS" => true,
                    "LESS" => false,
                    "VIS" => intval(substr($item, strpos($item, 'P')+1, 4)),
                    "TREND" => substr($item, strpos($item, 'P')+5),
                ));
            }
        } else if (preg_match('/VV[0-9]{3}/', $item)) {
            $currentweather["VV"] = intval(substr($item, 2, 3)) * 100;
        } else if (preg_match('/NSC/', $item)) {
            if ($trendtype === "CURRENT") {
                $currentweather["NSC"] = true;
            } else if ($trendtype === "BECOMING") {
                $becmgweather["NSC"] = true;
            } else if ($trendtype === "TEMPORARY") {
                $tempoweather["NSC"] = true;
            }
        } else if (preg_match('/NSW/', $item)) {
            if ($trendtype === "CURRENT") {
                $currentweather["NSW"] = true;
            } else if ($trendtype === "BECOMING") {
                $becmgweather["NSW"] = true;
            } else if ($trendtype === "TEMPORARY") {
                $tempoweather["NSW"] = true;
            }
        } else if (preg_match('/SKC/', $item)) {
            if ($trendtype === "CURRENT") {
                $currentweather["SKC"] = true;
            } else if ($trendtype === "BECOMING") {
                $becmgweather["SKC"] = true;
            } else if ($trendtype === "TEMPORARY") {
                $tempoweather["SKC"] = true;
            }
        } else if (preg_match('/CAVOK/', $item)) {
            if ($trendtype === "CURRENT") {
                $currentweather["CAVOK"] = true;
            } else if ($trendtype === "BECOMING") {
                $becmgweather["CAVOK"] = true;
            } else if ($trendtype === "TEMPORARY") {
                $tempoweather["CAVOK"] = true;
            }
        } else if (preg_match('/NCD/', $item)) {
            if ($trendtype === "CURRENT") {
                $currentweather["NCD"] = true;
            } else if ($trendtype === "BECOMING") {
                $becmgweather["NCD"] = true;
            } else if ($trendtype === "TEMPORARY") {
                $tempoweather["NCD"] = true;
            }
        } else if (preg_match('/^[0-9]{6}Z$/', $item)) {
            $time = substr($item, 2, 4);
        } else if (preg_match('/^EH[A-Z]{2}$/', $item)) {
            $ap = $item;
        } else if (preg_match('/FEW|SCT|BKN|OVC/', $item)) {
            if ($trendtype === "CURRENT") {
                array_push($currentweather["CLOUDS"], array(
                    "TYPE" => substr($item, 0, 3),
                    "ALT" => intval(substr($item, 3, 3)) * 100,
                    "EXTRA" => substr($item, 6),
                ));
            } else if ($trendtype === "BECOMING") {
                array_push($becmgweather["CLOUDS"], array(
                    "TYPE" => substr($item, 0, 3),
                    "ALT" => intval(substr($item, 3, 3)) * 100,
                    "EXTRA" => substr($item, 6),
                ));
            } else if ($trendtype === "TEMPORARY") {
                array_push($tempoweather["CLOUDS"], array(
                    "TYPE" => substr($item, 0, 3),
                    "ALT" => intval(substr($item, 3, 3)) * 100,
                    "EXTRA" => substr($item, 6),
                ));
            }
        } else if (preg_match('/BLU|BLACK|AMB|GRN|WHT|YLO|RED/', $item)) {
        } else if (preg_match('/BC|BL|BR|DR|DS|DU|DZ|FC|FG|FU|FZ|GR|HZ|IC|MI|PL|PR|PY|RA|SA|SG|SH|SN|SS|SQ|TS|UP|VA/', $item)) {
            if ($trendtype === "CURRENT") {
                array_push($currentweather["PHENOMENA"], $item);
            } else if ($trendtype === "BECOMING") {
                array_push($becmgweather["PHENOMENA"], $item);
            } else if ($trendtype === "TEMPORARY") {
                array_push($tempoweather["PHENOMENA"], $item);
            }
        }
        //echo $item . '<br>';
    }
}

//print_r($currentweather["RVRVALUES"]);
//echo "<br>" . $currentweather["RVR"] . "<br>";
?>