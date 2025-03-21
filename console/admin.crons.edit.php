<?php

security_check();
admin_check();

if(
    !isset($_GET['key']) || 
    !is_numeric($_GET['key']) || 
    !cron_fetch($_GET['key']))
{
    message_set('Road Error', 'There was an error with the provided cron.');
    header_redirect('/admin/crons/dashboard');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['name']))
    {

        message_set('Road Error', 'There was an error with the provided road.', 'red');
        header_redirect('/admin/crons/dashboard');
    }
    
    $query = 'UPDATE crons SET
        `name` = "'.addslashes($_POST['name']).'",
        `when` = "'.addslashes($_POST['when']).'",
        `url` = "'.addslashes($_POST['url']).'"
        WHERE id = '.$_GET['key'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Cron Job Success', 'Your cron job has been edited.');
    header_redirect('/admin/crons/dashboard');
    
}

define('APP_NAME', 'Cron Jobs');

define('PAGE_TITLE','Edit Cron Job');
define('PAGE_SELECTED_SECTION', 'admin-settings');
define('PAGE_SELECTED_SUB_PAGE', '/admin/crons/dashboard');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$cron = cron_fetch($_GET['key']);

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/roadview.png"
        height="50"
        style="vertical-align: top"
    />
    Cron Job
</h1>
<p>
    <a href="/city/dashboard">Dashboard</a> / 
    <a href="/admin/crons/dashboard">Cron Jobs</a> / 
    Edit Cron Job
</p>

<hr />

<h2>Edit Cron Job: <?=$cron['name']?></h2>

<form
    method="post"
    novalidate
    id="main-form"
>

    <input  
        name="name" 
        class="w3-input w3-border" 
        type="text" 
        id="name" 
        autocomplete="off"
        value="<?=$cron['name']?>"
    />
    <label for="name" class="w3-text-gray">
        Name <span id="name-error" class="w3-text-red"></span>
    </label>

    <input  
        name="when" 
        class="w3-input w3-border" 
        type="text" 
        id="when" 
        autocomplete="off"
        value="<?=$cron['when']?>"
    />
    <label for="when" class="w3-text-gray">
        When <span id="when-error" class="w3-text-red"></span>
    </label>

    <input  
        name="url" 
        class="w3-input w3-border" 
        type="text" 
        id="url" 
        autocomplete="off"
        value="<?=$cron['url']?>"
    />
    <label for="url" class="w3-text-gray">
        URL <span id="url-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-tag fa-padding-right"></i>
        Edit Road
    </button>
</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let name = document.getElementById("name");
        let name_error = document.getElementById("name-error");
        name_error.innerHTML = "";
        if (name.value == "") {
            name_error.innerHTML = "(name is required)";
            errors++;
        }

        let when = document.getElementById("when");
        let when_error = document.getElementById("when-error");
        when_error.innerHTML = "";
        if (when.value == "") {
            when_error.innerHTML = "(when is required)";
            errors++;
        }        

        let url = document.getElementById("url");
        let url_error = document.getElementById("url-error");
        url_error.innerHTML = "";
        if (url.value == "") {
            url_error.innerHTML = "(url is required)";
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
