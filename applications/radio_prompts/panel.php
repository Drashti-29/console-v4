<?php

$filename = basename(__FILE__);

$length = radio_length($filename);

$prompt = 'Write a script for a '.$length.' minute radio segment. 

The radio station name is Lively Radio. 
There is one host for the radio station.'; 

// get host
$prompt .= host_prompt($_GET['key'], $filename);

$prompt .=' Only include the words the host will say, no instructions, no music or sounds. Please generate an audio based on the topic: panel.
';

// get panel
$query="SELECT `cartridge`, GROUP_CONCAT(port) AS ports
        FROM panels 
        WHERE VALUE <> 'OFF' 
        GROUP BY `cartridge`";


    $result = mysqli_query($connect, $query);
    $cartridgeNum = mysqli_num_rows($result);

    while ($panels = mysqli_fetch_assoc($result)) {  
        $prompt .='There are ' . $cartridgeNum . ' cartridges in the city';
        $prompt .= $panels['cartridge'].' covers ports '. $panels['ports'];
    }

    $prompt .= 'Create a script based on the color distribution of the panels';