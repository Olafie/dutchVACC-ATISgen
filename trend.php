<?php
$metar = $_GET['wx'];

if (str_starts_with($metar, 'METAR')) {
    $ap = substr($metar, 6, 4);
} else {
    $ap = substr($metar, 0, 4);
}

if ($ap === "EHBK" || $ap === "EHGG") {
    $trend = " .. TREND";
} else {
    $trend = "";
}

if (str_contains($metar, 'BECMG')) {
    $metar = substr($metar, strpos($metar, "BECMG"));
    $trend_data = $metar;
    $trend .= ' .. BECOMING .. ';
} else if (str_contains($metar, 'TEMPO')) {
    $metar = substr($metar, strpos($metar, "TEMPO"));
    $trend_data = $metar;
    $trend .= ' .. TEMPORARY .. ';
} else if (str_contains($metar, 'NOSIG')) {
    $metar = substr($metar, strpos($metar, "NOSIG"));
    $trend_data = $metar;
    if ($ap === "EHGG" || $ap === "EHBK") {
        $trend = " .. NOSIG";
    } else {
        $trend = " .. NO SIGNIFICANT CHANGE";
    }
}

$trend_data_wxr = explode('%20', $trend_data);

    if (preg_match('/00000KT/', $trend_data)) {
        $trend_wind_output = " .. WIND CALM .. ";
    } else if (preg_match('/000[0-9][0-9]KT/', $trend_data, $windmatchtrend)) {
        $wind_spd = substr($windmatchtrend[0], 4, 1);
        $trend_wind_output = " .. WIND 0 DEGREES " . $wind_spd . " KNOTS .. ";
    } else if (preg_match('/[0-9][0-9][0-9]01KT/', $trend_data, $windmatchtrend)) {
        $wind_deg = substr($windmatchtrend[0], 0, 3);
        $wind_spd = substr($windmatchtrend[0], 4, 1);
        $trend_wind_output = " .. WIND " . $wind_deg . " DEGREES .. " . $wind_spd . " KNOT .. ";
    } else if (preg_match('/[0-9][0-9][0-9][1-9][0-9]KT/', $trend_data, $windmatchtrend)) {
        $wind_deg = substr($windmatchtrend[0], 0, 3);
        $wind_spd = substr($windmatchtrend[0], 3, 2);
        $trend_wind_output = " .. WIND " . $wind_deg . " DEGREES .. " . $wind_spd . " KNOTS .. ";
    } else if (preg_match('/[0-9][0-9][0-9]0[0-9]KT/', $trend_data, $windmatchtrend)) {
        $wind_deg = substr($windmatchtrend[0], 0, 3);
        $wind_spd = substr($windmatchtrend[0], 4, 1);
        $trend_wind_output = " .. WIND " . $wind_deg . " DEGREES .. " . $wind_spd . " KNOTS .. ";
    } else if (preg_match('/VRB[1-9][0-9]KT/', $trend_data, $windmatch)) {
        $wind_spd = substr($windmatch[0], 3, 2);
        $trend_wind_output = " .. WIND VARIABLE .. " . $wind_spd . " KNOTS .. ";
    } else if (preg_match('/VRB0[2-9]KT|VRB00KT/', $trend_data, $windmatch)) {
        $wind_spd = substr($windmatch[0], 4, 1);
        $trend_wind_output = " .. WIND VARIABLE .. " . $wind_spd . " KNOTS .. ";
    } else if (preg_match('/VRB01KT/', $trend_data, $windmatch)) {
        $wind_spd = substr($windmatch[0], 4, 1);
        $trend_wind_output = " .. WIND VARIABLE .. 1 KNOT .. ";
    } else if (preg_match('/[0-9][0-9][0-9][0-9][0-9]G[0-9][0-9]KT/', $trend_data, $windmatch)) {
        $trend_wind_deg = substr($windmatch[0], 0, 3);
        $trend_wind_spd = substr($windmatch[0], 3, 2);
        $trend_wind_gst = substr($windmatch[0], 6, 2);
        $trend_wind_output = " .. WIND " . $trend_wind_deg . " DEGREES .. " . $trend_wind_spd . " KNOTS .. GUST " . $trend_wind_gst . " KNOTS";
    } else {
        $trend_wind_output = "";
    }

    //Variable winds
    if (preg_match('/[0-3][0-6]0V[0-3][0-6]0/', $trend_data, $vrbmatch)) {
        $trend_vrb1 = substr($vrbmatch[0], 0, 3);
        $trend_vrb2 = substr($vrbmatch[0], 4, 3);
        $trend_vrb_output = " .. VARIABLE BETWEEN " . $trend_vrb1 . " AND " . $trend_vrb2 . " DEGREES";
    } else {
        $trend_vrb_output = '';
    }

    //Visibility
    if (preg_match('/ 9999 /', $metar)) {
        $vis_trend_m = "VISIBILITY 1 0 KILOMETERS OR MORE .. ";
        $vis_trend = "VISIBILITY 10 KILOMETERS OR MORE .. ";
    } else if (preg_match('/ [0-9]000 /', $metar, $vismatch)) {
        $vis_trend_m = "VISIBILITY *" . substr($vismatch[0], 1, 4) . " METERS .. ";
        $vis_trend  = "VISIBILITY " . substr($vismatch[0], 1, 1) . " KILOMETERS .. ";
    } else if (preg_match('/ [0-9]{4}/', $metar, $vismatch)) {
        $vis_trend_m = "VISIBILITY *" . ltrim(substr($vismatch[0], 1, 4), "0") . " METERS .. ";
        $vis_trend  = "VISIBILITY " . substr($vismatch[0], 1, 1) . " KILOMETERS AND *" . ltrim(substr($vismatch[0], 2, 3), "0") . " METERS .. ";
    } else {
        $vis_trend_m = '';
        $vis_trend = '';
    }
    
    //Vertical Visibility
    if (preg_match('/VV[1-9][0-9][0-9]/', $trend_data, $vvmatch)) {
        $vv_data = substr($vvmatch[0], 2, 1);
        $trend_vv_output = ' .. VERTICAL VISIBILITY ' . $vv_data . ' HUNDRED FEET';
    } else if (preg_match('/VV0[0-9][0-9]/', $trend_data, $vvmatch)) {
        $vv_data = substr($vvmatch[0], 2, 1);
        $trend_vv_output = ' .. VERTICAL VISIBILITY ' . $vv_data . ' FEET';
    } else {
        $trend_vv_output = '';
    }

    //Clouds
    if (preg_match('/NSC/', $trend_data)) {
        $trend_cldstat = " .. NO SIGNIFICANT CLOUDS";
    } else if (preg_match('/NCD/', $trend_data)) {
        $trend_cldstat = " .. NO CLOUDS DETECTED";
    } else if (preg_match('/SKC/', $trend_data)) {
        $trend_cldstat = " .. SKY CLEAR";
    } else if (preg_match('/CAVOK/', $trend_data)) {
        $trend_cldstat = " .. CAV OKAY";
    } else if (preg_match('/NSW/', $trend_data)) {
        $trend_cldstat = " .. NO SIGNIFICANT WEATHER";
    } else {
        $trend_cldstat = '';
    }
    
    foreach ($trend_data_wxr as $metar) {
        if (preg_match('/FEW[0-9]{3}|SCT[0-9]{3}|BKN[0-9]{3}|OVC[0-9]{3}/', $metar)) {
            $trend_cld_output = 'CLOUDS';
        } else {
            $trend_cld_output = '';
        }
        if (preg_match('/FEW[0-9]{3}/', $metar, $cloudmatch)) {
            $trend_cld_output .= ' .. FEW *' . ltrim(substr($cloudmatch[0], 3, 3), "0") . '00 FEET .. ';
        } else {
            $trend_cld_output .= '';
        }
        if (preg_match('/SCT[0-9]{3}/', $metar, $cloudmatch)) {
            $trend_cld_output .= ' .. SCATTERED *' . ltrim(substr($cloudmatch[0], 3, 3), "0") . '00 FEET .. ';
        } else {
            $trend_cld_output .= '';
        }
        if (preg_match('/BKN[0-9]{3}/', $metar, $cloudmatch)) {
            $trend_cld_output .= ' .. BROKEN *' . ltrim(substr($cloudmatch[0], 3, 3), "0") . '00 FEET .. ';
        } else {
            $trend_cld_output .= '';
        }
        if (preg_match('/OVC[0-9]{3}/', $metar, $cloudmatch)) {
            $trend_cld_output .= ' .. OVERCAST *' . ltrim(substr($cloudmatch[0], 3, 3), "0") . '00 FEET .. ';
        } else {
            $trend_cld_output .= '';
        }
    }

    //Phenomena
    if (preg_match('/BC|BL|BR|DR|DS|DU|DZ|FC|FG|FU|FZ|GR|HZ|IC|MI|PL|PR|PY|RA|SA|SG|SH|SN|SS|SQ|TS|UP|VA/', $trend_data)) {
        $trend_phenomena = '';
    } else {
        $trend_phenomena = '';
    }
    if (str_contains($trend_data, '-')) {
        $trend_phenomena .= 'LIGHT ';
    }
    if (str_contains($trend_data, '+')) {
        $trend_phenomena .= 'HEAVY ';
    }
    
    if (preg_match('/FZ/', $trend_data)) {
        $trend_phenomena .= 'FREEZING ';
    }

    if (preg_match('/TS/', $trend_data)) {
        $trend_phenomena .= 'THUNDERSTORMS ';
    }
    
    if (preg_match('/MI/', $trend_data)) {
        $trend_phenomena .= 'SHALLOW ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/BL/', $trend_data)) {
        $trend_phenomena .= 'BLOWING ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/DR/', $trend_data)) {
        $trend_phenomena .= 'LOW DRIFTING ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/PR/', $trend_data)) {
        $trend_phenomena .= 'PARTIAL ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/BC/', $trend_data)) {
        $trend_phenomena .= 'PATCHES OF ';
    } else {
        $trend_phenomena .= '';
    }
    
    if (preg_match('/GR/', $trend_data)) {
        $trend_phenomena .= 'HAIL ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/GS/', $trend_data)) {
        $trend_phenomena .= 'SMALL HAIL ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/PL/', $trend_data)) {
        $trend_phenomena .= 'ICE PELLETS ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/IC/', $trend_data)) {
        $trend_phenomena .= 'ICE CRYSTALS ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/SG/', $trend_data)) {
        $trend_phenomena .= 'SNOW GRAINS ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/DZRA/', $trend_data)) {
        $trend_phenomena .= 'DRIZZLE RAIN ';
    } else if (preg_match('/RADZ/', $trend_data)) {
        $trend_phenomena .= 'RAIN DRIZZLE ';
    } else if (preg_match('/SHRA/', $trend_data)) {
        $trend_phenomena .= 'SHOWERS OF RAIN';
    } else if (preg_match('/SH/', $trend_data)) {
        $trend_phenomena .= 'SHOWERS ';
    } else if (preg_match('/RA/', $trend_data)) {
        $trend_phenomena .= 'RAIN ';
    } else if (preg_match('/DZ/', $trend_data)) {
        $trend_phenomena .= 'DRIZZLE ';
    }
    
    if (preg_match('/BR/', $trend_data)) {
        $trend_phenomena .= 'MIST ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/DS/', $trend_data)) {
        $trend_phenomena .= 'DUST STORM ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/DU/', $trend_data)) {
        $trend_phenomena .= 'WIDESPREAD DUST ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/FC/', $trend_data)) {
        $trend_phenomena .= 'FUNNEL CLOUD ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/FG/', $trend_data)) {
        $trend_phenomena .= 'FOG ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/FU/', $trend_data)) {
        $trend_phenomena .= 'SMOKE ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/HZ/', $trend_data)) {
        $trend_phenomena .= 'HAZE ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/PY/', $trend_data)) {
        $trend_phenomena .= 'SPRAY ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/SA/', $trend_data)) {
        $trend_phenomena .= 'SAND ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/SN/', $trend_data)) {
        $trend_phenomena .= 'SNOW ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/SS/', $trend_data)) {
        $trend_phenomena .= 'SANDSTORM ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/SQ/', $trend_data)) {
        $trend_phenomena .= 'SQUALL ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/UP/', $trend_data)) {
        $trend_phenomena .= 'UNKNOWN PRECIPITATION ';
    } else {
        $trend_phenomena .= '';
    }
    if (preg_match('/VA/', $trend_data)) {
        $trend_phenomena .= 'VULCANIC ASH ';
    } else {
        $trend_phenomena .= '';
    }

    //TREND OUTPUT
    return $trend .= $trend_wind_output . $trend_vrb_output . $vis_trend . $trend_vv_output . $trend_cldstat . $trend_cld_output . $trend_phenomena;