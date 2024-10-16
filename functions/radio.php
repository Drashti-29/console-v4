<?php

// Fetch panel data based on city_id
function get_segments_data_by_schedule_5()
{
    global $connect;
    $query = "SELECT s.time, sg.name AS title
              FROM Schedules s
              JOIN Segments sg ON s.segment_id = sg.id
              ORDER BY s.time ASC LIMIT 5";

    $result = mysqli_query($connect, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Fetch panel data based on city_id
function get_broadcast_logs()
{
    global $connect;
    $query = "SELECT content FROM `broadcast_logs`";

    $result = mysqli_query($connect, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

function get_broadcast_list()
{
    global $connect;
    $query = "SELECT s.id,s.segment_id,s.time, sg.name AS title 
              FROM Schedules s 
              JOIN Segments sg ON s.segment_id = sg.id 
              ORDER BY s.time ASC";

    $result = mysqli_query($connect, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Function to call ChatGPT API
function generateContent($segmentId)
{
    global $connect;

    $query = "SELECT name FROM Segments WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $segmentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $segmentName = $row ? $row['name'] : 'Unknown Segment';

    $apiKey = OPENAI_API_KEY;
    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'system', 'content' => "Write a detailed script"],
            ['role' => 'user', 'content' => "Write a detailed, engaging LEGO based script for a 5-minute radio segment on " . $segmentName]
        ],
        'max_tokens' => 1000,
        'temperature' => 0,
        "top_p" => 0,
        "frequency_penalty" => 0,
        "presence_penalty" => 0,
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $apiKey, 'Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    // return $result;
    return $result['choices'][0]['message']['content'] ?? 'Default content due to API failure.';
}


function textToSpeech($text) {
    $curl = curl_init();
    $postData = array(
        'model' => 'aura-asteria-en',
        'text' => $text
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.deepgram.com/v1/speak",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Token 9d0c3ab95080fa2626f616111cc1379b25a95016",
            "Content-Type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return false;
    } else {
        return $response;
    }
}
