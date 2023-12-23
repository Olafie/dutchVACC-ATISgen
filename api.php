<?php
$json = 'https://api.vatsim.net/v2/atc/online';
$jsondecode = json_decode(file_get_contents($json, true), true);

$countryicao = "EH";

$onlineATC = array();
foreach ($jsondecode as $i => $atc) {
    array_push($onlineATC, $atc['callsign']);
}