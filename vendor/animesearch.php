<?php
$apiBaseUrl = "https://api.anilibria.tv/v2";

function getAnimeDetails($animeId) {
    global $apiBaseUrl;
    
    try {
        $url = "{$apiBaseUrl}/getTitle?id=" . urlencode($animeId);
        error_log("Requesting anime details from: " . $url);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'AnilibriaClient',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ]
        ]);
        
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        
        error_log("CURL Info: " . print_r($info, true));
        
        if ($errno) {
            error_log("CURL Error ({$errno}): {$error}");
            curl_close($ch);
            return null;
        }
        
        $httpCode = $info['http_code'];
        curl_close($ch);
        
        error_log("Raw API Response: " . $response);
        
        if ($httpCode !== 200) {
            error_log("API returned non-200 status code: " . $httpCode);
            return null;
        }

        $result = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            return null;
        }
        
        error_log("Successfully retrieved anime details: " . print_r($result, true));
        return $result;
    } catch (Exception $e) {
        error_log("Exception in getAnimeDetails: " . $e->getMessage());
        return null;
    }
}