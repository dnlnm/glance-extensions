<?php
$url = $_GET['server'] ?? '';
$apiKey = $_GET['token'] ?? '';
$apiEndpoint = '/api/server-info/statistics';

// Set the widget title and content type headers
header('Widget-Title: Immich');
header('Widget-Content-Type: html');


// Make API request with obtained API key
$ch = curl_init($url. $apiEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', "x-api-key: $apiKey"]);
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
$photos = isset($decodedResponse['photos'])? $decodedResponse['photos'] : 0;
$videos = isset($decodedResponse['videos'])? $decodedResponse['videos'] : 0;
$usageBytes = isset($decodedResponse['usage'])? $decodedResponse['usage'] : 0;
$usageMB = number_format($usageBytes / 1048576, 2);
$usageGB = number_format($usageBytes / 1073741824, 2);
?>

<!-- CSS styles for the table -->
<style>
   .center-table {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        border: 1px solid;
        border-color: #222; /* change the border color to #222 (gray) */
    }

   .center-table th,
   .center-table td {
        padding: 5px 12px; /* 3px top and bottom padding, 10px left and right padding */
    }

    th img,
    th span {
        display: inline; /* Display the Icon and Text on the same line */
    }

   .right-align {
        text-align: right;
        padding: 3px
    }
</style>

<!-- The table with statistics, wrapped in a link to the Immich instance -->
<a href=<?= htmlspecialchars($url)?> target="_blank">
    <table class="center-table" border="1" align="center">
        <tr>
            <th colspan="2" align="center">
                <img src=<?= htmlspecialchars($url). "/favicon.ico"?> alt="" loading="lazy" width="32">
                <span>Immich Stats</span>
            </th>
        </tr>
        <tr>
            <td class="color-primary">Photos:</td>
            <td class="right-align"><?= htmlspecialchars($photos)?></td>
        </tr>
        <tr>
            <td class="color-primary">Videos:</td>
            <td class="right-align"><?= htmlspecialchars($videos)?></td>
        </tr>
        <tr>
            <td class="color-primary">Usage:</td>
            <td class="right-align"><?= htmlspecialchars($usageGB)?> GB</td>
        </tr>
    </table>
</a>
