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
