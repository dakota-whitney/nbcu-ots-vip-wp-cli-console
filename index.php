<?php
include './functions.php';
$environment = isset($_POST["env"]) ? $_POST["env"] : "";
function display_output($output){
    if(preg_match('/\n/',$output)){
        $output = explode("\n",$output);
        $success = '/\[32;1mSuccess:.\[0m/';
        $error = '/\[31;1mError:.\[0m/';
        $warning = '/Warning:/';
        $export_success = '/Personal\sdata\sprocessed/';
        $syndication_success = '/Syndication\scomplete/';
        foreach($output as $line){
            if($line){
                if(preg_match($success,$line)){
                    $line = preg_replace($success,"<span style=\"color:green;\">Success:</span>",$line);
                }elseif(preg_match($error,$line)){
                    $line = preg_replace($error,"<span style=\"color:red;\">Error:</span>",$line);
                }elseif(preg_match($warning,$line)){
                    $line = preg_replace($warning,"<span style=\"color:darkorange;\">Warning:</span>",$line);
                }elseif(preg_match($export_success,$line)){
                    $line = preg_replace($export_success,"<span style=\"color:green;\">Personal data processed</style>",$line);
                }elseif(preg_match($syndication_success,$line)){
                    $line = preg_replace($syndication_success,"<span style=\"color:green;\">Syndication complete</style>",$line);
                };
                echo "<p class=\"cmd-output\">$line</p>";
            };
        };
    }else{
        echo $output;
    };
};
?>
<html>
    <title>NBC OTS - VIP CLI Web Console</title>
    <link rel="icon" type="image/x-icon" href="./terminal-icon.png">
    <link rel="stylesheet" href="./styles.css">
    <!-- <script src="./index.js"></script> -->
<body>
    <h1 id="title">NBC OTS VIP CLI Web Console</h1>
    <form method="post">
        <label for="env">Environment:</label>
        <select id="env-dropdown" name="env">
            <option value="stage" <?=isset($_POST["env"]) && $_POST["env"] === "stage" ? "selected" : "";?>>Stage</option>
            <option value="preprod" <?=isset($_POST["env"]) && $_POST["env"] === "preprod" ? "selected" : "";?>>UAT</option>
            <option value="production" <?=isset($_POST["env"]) && $_POST["env"] === "production" ? "selected" : "";?>>Prod</option>
        </select>
        <main id="main">
            <div id="clear-post-cache" class="cmd-card" <?=$_SERVER["REQUEST_METHOD"] === "POST" ? "" : "style=\"animation: fadeIn 1s ease-in-out;\"";?>>
                <label for="post_url" class="cmd-title">Clear Post Cache:</label>
                <br />
                <input class="cmd-input" type="url" name="post_url" placeholder="Post URL"></input>
                <button type="submit">Clear</button>
                <?php
                if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["post_url"]){
                    $output = execute_post_clear($_POST["post_url"],$environment);
                    display_output($output);
                };
                ?>
            </div>
            <div id="clear-hp-cache" class="cmd-card" <?=$_SERVER["REQUEST_METHOD"] === "POST" ? "" : "style=\"animation: fadeIn 1.5s ease-in-out;\"";?>>
                <label for="homepage_url" class="cmd-title">Clear Homepage Cache:</label>
                <br />
                <input class="cmd-input" type="url" name="homepage_url" placeholder="Homepage URL"></input>
                <button type="submit">Clear</button>
                <?php
                if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["homepage_url"]){
                    $output = execute_homepage_clear($_POST["homepage_url"],$environment);
                    display_output($output);
                };
                ?>
            </div>
            <div id="trigger-syndication" class="cmd-card" <?=$_SERVER["REQUEST_METHOD"] === "POST" ? "" : "style=\"animation: fadeIn 2s ease-in-out;\"";?>>
                <label for="originating_url" class="cmd-title">Trigger Syndication:</label>
                <br />
                <input class="cmd-input" type="url" name="originating_url" placeholder="Originating post URL"></input>
                <button type="submit">Syndicate</button>
                <?php
                if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["originating_url"]){
                    $output = execute_trigger_syndication($_POST["originating_url"],$environment);
                    display_output($output);
                };
                ?>
            </div>
            <div id="export-personal-data" class="cmd-card" <?=$_SERVER["REQUEST_METHOD"] === "POST" ? "" : "style=\"animation: fadeIn 2.5s ease-in-out;\"";?>>
                <label for="users-to-export" class="cmd-title">Export personal data:</label>
                <br />
                <input class="cmd-input" type="text" name="users-to-export" placeholder="User emails (space-separated)"></input>
                <button type="submit">Export</button>
                <?php
                if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["users-to-export"]){
                    $output = execute_export_personal_data($_POST["users-to-export"],$environment);
                    display_output($output);
                };
                ?>
            </div>
        </main>
    </form>
</body>
</html>