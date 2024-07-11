<?php
$url = $_GET['server'] ?? '';
$zone = $_GET['timezone'] ?? '';
date_default_timezone_set($zone);

// Set the widget title and content type headers
header('Widget-Title: Prayer Times');
header('Widget-Content-Type: html');


// Make API request
$ch = curl_init($url);
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

//function to format time from UNIX to HH:MM
function formatTime($time) {
    return date('H:i', $time);
}

// Extract times from response
$place = isset($decodedResponse['response']['place']) ? $decodedResponse['response']['place'] : 0;

$subuh = isset($decodedResponse['response']['times'][0]) ? $decodedResponse['response']['times'][0] : 0;
$fixedSubuh = formatTime($subuh);

$zohor = isset($decodedResponse['response']['times'][2]) ? $decodedResponse['response']['times'][2] : 0;
$fixedZohor = formatTime($zohor);

$asar = isset($decodedResponse['response']['times'][3]) ? $decodedResponse['response']['times'][3] : 0;
$fixedAsar = formatTime($asar);

$maghrib = isset($decodedResponse['response']['times'][4]) ? $decodedResponse['response']['times'][4] : 0;
$fixedMaghrib = formatTime($maghrib);

$isyak = isset($decodedResponse['response']['times'][5]) ? $decodedResponse['response']['times'][5] : 0;
$fixedIsyak = formatTime($isyak);
?>

<!-- CSS styles for the table -->
<style>
   .center-table-prayer {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        border: 1px solid;
        border-color: #222; /* change the border color to #222 (gray) */

    }

   .center-table-prayer th,
   .center-table-prayer td {
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
        padding: 5px 12px;
        font-style: italic;
    }


</style>

<!-- The table with place and times -->
    <table class="center-table-prayer" border="1" align="center">
        <tr>
            <th colspan="2" align="center">
                <span><?= htmlspecialchars($place)?></span>
            </th>
        </tr>
        <tr>
            <td class="color-primary">Subuh</td>
            <td class="right-align"><?= htmlspecialchars($fixedSubuh)?></td>
        </tr>
        <tr>
            <td class="color-primary">Zohor</td>
            <td class="right-align"><?= htmlspecialchars($fixedZohor)?></td>
        </tr>
        <tr>
            <td class="color-primary">Asar</td>
            <td class="right-align"><?= htmlspecialchars($fixedAsar)?></td>
        </tr>
        <tr>
            <td class="color-primary">Maghrib</td>
            <td class="right-align"><?= htmlspecialchars($fixedMaghrib)?></td>
        </tr>
        <tr>
            <td class="color-primary">Isyak</td>
            <td class="right-align"><?= htmlspecialchars($fixedIsyak)?></td>
        </tr>
    </table>

