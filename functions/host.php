<?php

function host_fetch($identifier)
{

    if(!$identifier) return false;

    global $connect;

    $query = 'SELECT *
        FROM hosts
        WHERE id = "'.addslashes($identifier).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result)) return mysqli_fetch_assoc($result);
    else return false;

}