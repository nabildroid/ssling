<?php



function makeGoogleCalenderLink($title, $date)
{


    $eventTime = "09:00:00";
    $eventDateTime = $date . "T" . $eventTime . "Z";
    $eventDateTime = date("c", strtotime($eventDateTime));

    // Encode the event name for the URL
    $encodedEventName = urlencode($title);

    // Create the Google Calendar event URL
    $googleCalendarURL = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . $encodedEventName . "&dates=" . $eventDateTime;


    return $googleCalendarURL;
}


function formatURL($url) {
    // Check if the URL starts with "http://" or "https://"
    if (strpos($url, "http://") === 0 || strpos($url, "https://") === 0) {
        // Parse the URL
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'];

        // Remove www. if present
        $host = preg_replace('/^www\./', '', $host);

        return $host;
    } else {
        // For IP addresses or other types of links, return the input as is
        return $url;
    }
}
