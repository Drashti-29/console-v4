<?php

security_check();
admin_check();

define('APP_NAME', 'Road View');

define('PAGE_TITLE','Edit Road');
define('PAGE_SELECTED_SECTION', 'geography');
define('PAGE_SELECTED_SUB_PAGE', '/roadview/roads');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$road = road_fetch($_GET['key']);
$squares = squares_fetch_all($_city['id']);
$width = round(100/$_city['width'],2);

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/roadview.png"
        height="50"
        style="vertical-align: top"
    />
    Road View
</h1>
<p>
    <a href="/city/dashboard">Dashboard</a> / 
    <a href="/roadview/dashboard">Road View</a> / 
    <a href="/roadview/roads">Roads</a> / 
    Road Squares
</p>

<hr />

<h2>Road Squares: <?=$road['name']?></h2>

<?php for($row = 0; $row < $_city['height']; $row ++): ?>

<div class="w3-cell-row">

    <?php for($col = 0; $col < $_city['width']; $col ++): ?>

        <div class="w3-cell w3-border w3-<?=square_colour($squares[$row][$col]['id'], array('road_id'=> $_GET['key']))?>" 
            style="width: <?=$width?>%; height: 35px; cursor: pointer;"
            data-id="<?=$squares[$row][$col]['id']?>"
            data-type="<?=$squares[$row][$col]['type']?>"
            data-road="<?=(is_array($squares[$row][$col]['roads']) and in_array($_GET['key'], $squares[$row][$col]['roads'])) ? 'true' : 'false'?>"
            onclick="editSquareType(this);">
        </div>

    <?php endfor; ?>    

</div>

<?php endfor; ?>

<script>

function editSquareType(target)
{
    let id = target.dataset.id;
    let type = target.dataset.type;
    let road = target.dataset.road;
    let key = <?=$_GET['key']?>;

    if(type == "ground")
    {

        if(road == "true")
        {
            target.classList.remove("w3-red");
            target.classList.add("w3-brown");
            target.dataset.road = "false";
            road = "false";
        }
        else
        {
            target.classList.remove("w3-brown");
            target.classList.add("w3-red");
            target.dataset.road = "true";
            road = "true";
        }        
        
        fetch('/ajax/square/road',{
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({id: id, road_id: key, road: road})
        });
    }
}

</script>
    

<?php

include('../templates/modal_city.php');

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
