<?php
$url = $_GET['server'] ?? '';
$apiEndpoint = '/api/speedtest/latest';

// Set the widget title and content type headers
header('Widget-Title: Speedtest');
header('Widget-Content-Type: html');


// Make API request
$ch = curl_init($url. $apiEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Check for errors
if ($response === FALSE) {
    die('Error performing request.');
}

// Decode JSON response
$decodedResponse = json_decode($response, true);

// Check for JSON decoding errors
if (!is_array($decodedResponse)) {
    die('JSON decode failed: '. htmlspecialchars(json_last_error_msg()). '. Response:'. htmlspecialchars($response));
}

// Extract statistics from response
$download = isset($decodedResponse['data']['download'])? $decodedResponse['data']['download'] : 0;
$upload = isset($decodedResponse['data']['upload'])? $decodedResponse['data']['upload'] : 0;
$ping = isset($decodedResponse['data']['ping'])? $decodedResponse['data']['ping'] : 0;
$time = isset($decodedResponse['data']['created_at'])? $decodedResponse['data']['created_at'] : 0;
if ($time) {
    $dateTime = new DateTime($time);
    $fixed = $dateTime->format('H:i');
} else {
    $fixed = 'N/A';
}
?>

<!-- CSS styles for the table -->
<style>
   .center-table {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        overflow: hidden;
        border: 1px solid;
        border-color: #222; /* change the border color to #222 (gray) */
    }

   .center-table th,
   .center-table td {
        padding: 5px 12px; /* 5px top and bottom padding, 12px left and right padding */
    }

    th img,
    th span {
        display: inline; /* Display the Icon and Text on the same line */
    }

   .right-align {
        text-align: right;
        padding: 3px
    }

    .time {
        font-style: italic;
        display: block;
        text-align: center;
        margin-top: 10px;
        color: #666;
    }


</style>

<!-- The table with statistics, wrapped in a link to the speedtest tracker instance -->
<a href=<?= htmlspecialchars($url)?> target="_blank">
    <table class="center-table" border="1" align="center">
        <tr>
            <th colspan="2" align="center">
                <img src=<?= htmlspecialchars($url). "/favicon.ico"?> alt="" loading="lazy" width="32">
                <span>Speedtest Tracker</span>
            </th>
        </tr>
        <tr>
            <td class="color-primary">Download:</td>
            <td class="right-align"><?= htmlspecialchars($download)?> Mbps</td>
        </tr>
        <tr>
            <td class="color-primary">Upload:</td>
            <td class="right-align"><?= htmlspecialchars($upload)?> Mbps</td>
        </tr>
        <tr>
            <td class="color-primary">Ping:</td>
            <td class="right-align"><?= htmlspecialchars($ping)?> ms</td>
        </tr>
    </table>
</a>
<span class="time">Last updated: <?= htmlspecialchars($fixed)?></span>
