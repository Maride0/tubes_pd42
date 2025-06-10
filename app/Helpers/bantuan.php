<?php
 
// fungsi untuk mengembalikan format rupiah dari suatu nominal tertentu
// dengan pemisah ribuan 
function rupiah($nominal) {
    return "Rp ".number_format($nominal);
}

function dolar($nominal) {
    return "USD ".number_format($nominal);
}

function motivasi(){
    $apiKey = env('GEMINI_API_KEY');
        $model = 'gemini-2.0-flash'; // atau model lain yang tersedia
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        $question = "buatkan satu kalimat fun fact tentang soto";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $question],
                    ],
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        } else {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $answer = $result['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $answer = 'Respons tidak valid: ' . $response;
            }
            return $answer;
        }
}