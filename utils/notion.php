<?php

function insertDataToNotion($databaseId, $apiKey, $domain, $expiration, $email)
{
    // Set the API endpoint URL
    $url = "https://api.notion.com/v1/pages";

    // Set the request headers
    $headers = array(
        "Authorization: Bearer {$apiKey}",
        "Content-Type: application/json",
        "Notion-Version: 2021-05-13",
    );

    $expirationDate = date('Y-m-d', strtotime($expiration));

    // Set the data to be inserted
    $data = array(
        "parent" => array("database_id" => $databaseId),
        "properties" => array(
            "Domain" => array("title" => array(array("text" => array("content" => $domain)))),
            "Expiration" => array("date" => array("start" => $expirationDate)),
            "Email" => array("email" => $email),

        ),
    );

    // Convert the data to JSON
    $jsonData = json_encode($data);

    // Set cURL options
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

    // Send the cURL request
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        return "Error: {$error}";
    }

    // Close cURL
    curl_close($curl);

    // Return the response
    return $response;
}


