<?php
function validate_ots_site($url){
    $site_regex = '/(www|uat|stage|ots)\.(nbc[a-zA-Z]+|telemundo[a-zA-Z]+\d{0,2}|lx|cleartheshelters|cozitv)\.com(\/qa|\/telemundo)?/';
    if(preg_match($site_regex,$url)){
        preg_match($site_regex,$url,$matches);
        return $matches[0];
    }else{
        return false;
    };
};
function validate_post($url){
    $post_regex = '/(?<==|\/)\d+/';
    if(preg_match($post_regex,$url)){
        preg_match($post_regex,$url,$matches);
        return $matches[0];
    }else{
        return false;
    };
};
function validate_environment($site,$environment){
    $env_regex = ["stage" => '/stage\./',"preprod" => '/uat\./',"production" => '/(www|ots)\./'];
    if(preg_match($env_regex[$environment],$site)){
        return true;
    }else{
        return false;
    };
};
function execute_post_clear($input,$environment){
    if(filter_var($input,FILTER_VALIDATE_URL)){
        $url = $input;
        if(validate_ots_site($url)){
            $site = validate_ots_site($url);
            if(validate_environment($site,$environment)){
                if(validate_post($url)){
                    $post_id = validate_post($url);
                    return shell_exec("vip @nbcots.$environment -y wp -- --url=$site nbc purge_post_cache $post_id");
                }else{
                    return "Error: Could not identify post ID in URL";
                };
            }else{
                return "Error: Site $site doesn't match the selected environment '$environment'";
            };
        }else{
            return "Error: Not a valid OTS site";
        };
    }else{
        return "Error: Not a valid URL";
    };
};
function execute_homepage_clear($input,$environment){
    if(filter_var($input,FILTER_VALIDATE_URL)){
        $url = $input;
        if(validate_ots_site($url)){
            $site = validate_ots_site($url);
            if(validate_environment($site,$environment)){
                return shell_exec("vip @nbcots.$environment -y wp nbc flush_homepage_cache -- --url=$site");
            }else{
                return "Error: Site $site doesn't match the selected environment '$environment'"; 
            };
        }else{
            return "Error: Not a valid OTS site";
        };
    }else{
        return "Error: Not a valid URL";
    };
};
function execute_trigger_syndication($input,$environment){
    if(filter_var($input,FILTER_VALIDATE_URL)){
        $url = $input;
        if(validate_ots_site($url)){
            $site = validate_ots_site($url);
            if(validate_environment($site,$environment)){
                if(validate_post($url)){
                    $post_id = validate_post($url);
                    return shell_exec("vip @nbcots.$environment -y wp -- --url=$site --user=feed-consumer@nbc.local nbc trigger_syndication $post_id");
                }else{
                    return "Error: Could not identify post ID in URL";
                };
            }else{
                return "Error: Site $site doesn't match the selected environment '$environment'"; 
            };
        }else{
            return "Error: Not a valid OTS site";
        };
    }else{
        return "Error: Not a valid URL";
    };
};
function execute_export_personal_data($input,$environment){
    if(preg_match('/\s/',$input)){
        $emails = explode(" ",$input);
        $output = [];
        foreach($emails as $email){
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                array_push($output,shell_exec("vip @nbcots.$environment -y wp -- nbc export_user_personal_data --type=export --email='$email'"));
            }else{
                array_push($output,"\nError: $email is not a valid email address\n");
            };
        };
        return implode("",$output);
    }else{
        if(filter_var($input,FILTER_VALIDATE_EMAIL)){
            return shell_exec("vip @nbcots.$environment -y wp -- nbc export_user_personal_data --type=export --email='$input'");
        }else{
            return "Error: $input is not a valid email address";
        };
    };
};
?>