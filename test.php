<?php

$apikey = "zQSlnuU9epzy9qUtH92f"; // NOTE: replace test_only with your own key 
$word = "cat"; // any word 
$language = "en_US"; // you can use: en_US, es_ES, de_DE, fr_FR, it_IT 
$endpoint = "http://thesaurus.altervista.org/thesaurus/v1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$endpoint?word=" . urlencode($word) . "&language=$language&key=$apikey&output=json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($info['http_code'] == 200) {
    $result = json_decode($data, true);
    foreach ($result["response"] as $value) {
        foreach (explode("|", $value["list"]["synonyms"]) as $synonym) {
            $synonyms[] = $synonym;
        }
    }
}


print_r($synonyms);