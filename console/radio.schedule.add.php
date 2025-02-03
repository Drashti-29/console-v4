<?php

security_check();
admin_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    if (!validate_blank($_POST['minute']) || 
        !validate_blank($_POST['type_id']) ||
        !validate_blank($_POST['host_id']))
    {
        message_set('Schedule Error', 'There was an error with the provided schedule.', 'red');
        header_redirect('/radio/schedule/add');
    }
    
    $query = 'INSERT INTO schedules (
            minute,
            type_id,
            city_id,
            host_id,
            created_at,
            updated_at
        ) VALUES (
            "'.addslashes($_POST['minute']).'",
            "'.addslashes($_POST['type_id']).'",
            "'.addslashes($_POST['host_id']).'",
            "'.$_city['id'].'",
            NOW(),
            NOW()
        )';
    mysqli_query($connect, $query);

    message_set('Schedule Success', 'Your schedule has been added.');
    header_redirect('/radio/schedule');
    
}

define('APP_NAME', 'Radio');
define('PAGE_TITLE', 'Add Schedule');
define('PAGE_SELECTED_SECTION', 'admin-content');
define('PAGE_SELECTED_SUB_PAGE', '/radio/schedule');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/roadview.png"
        height="50"
        style="vertical-align: top"
    />
    Radio
</h1>
<p>
    <a href="/city/dashboard">Dashboard</a> / 
    <a href="/radio/dashboard">Radio</a> / 
    <a href="/radio/schedule">Schedule</a> / 
    Add Schedule
</p>

<hr />

<h2>Add Schedule</h2>

<form
    method="post"
    novalidate
    id="main-form"
>

    <input  
        name="minute" 
        class="w3-input w3-border w3-margin-top" 
        type="text" 
        id="minute" 
        autocomplete="off"
    />
    <label for="minute" class="w3-text-gray">
        Minute <span id="minute_error" class="w3-text-red"></span>
    </label>

    <?=form_select_table('type_id', 'schedule_types', 'id', 'name', array('empty_key' => ''))?>
    <label for="type_id" class="w3-text-gray">
        Type <span id="type_id_error" class="w3-text-red"></span>
    </label>

    <?=form_select_table('host_id', 'hosts', 'id', 'name', array('empty_key' => ''))?>
    <label for="host_id" class="w3-text-gray">
        Host <span id="host_id_error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-tag fa-padding-right"></i>
        Add Schedule
    </button>
</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let minute = document.getElementById("minute");
        let minute_error = document.getElementById("minute_error");
        minute_error.innerHTML = "";
        if (minute.value == "") {
            minute_error.innerHTML = "(minute is required)";
            errors++;
        }else if (minute.value.length < 2) {
            minute_error.innerHTML = "(minute must be two numbers)";
            errors++;
        }else if (!/^[0-5][0-9]$/.test(minute.value)) {
            minute_error.innerHTML = "(minute must be between 00 and 59)";
            errors++;
        }

        // why it's name here
        let type_id = document.getElementById("type_id");
        let type_id_error = document.getElementById("type_id_error");
        type_id_error.innerHTML = "";
        if (type_id.value == "") {
            type_id_error.innerHTML = "(type is required)";
            errors++;
        }

        let host_id = document.getElementById("host_id");
        let host_id_error = document.getElementById("host_id_error");
        host_id_error.innerHTML = "";
        if (host_id.value == "") {
            host_id_error.innerHTML = "(host is required)";
            errors++;
        }

        if (errors) return false;
    }

</script>
    

<?php

include('../templates/modal_city.php');

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
