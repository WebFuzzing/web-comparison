<?php
require("./init.php");
$action = getArrayVal($_GET, "action");

$cleanPost = cleanArray($_POST);

if ($action == "addtpl") {
    if (!$userpermissions["projects"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    if (!$end) {
        $end = 0;
    }

    $hasTpl = getArrayVal($_POST, "thetpl");
    $tplobj = new projectTemplates();

    if ($hasTpl > 0) {
        $add = $tplobj->addFromTemplate($cleanPost["thetpl"], $cleanPost["desc"], $cleanPost["name"], $cleanPost["end"], $cleanPost["budget"], $cleanPost["priority"]);
    } else {
        $add = $tplobj->add($cleanPost["name"], $cleanPost["desc"], $cleanPost["end"], $cleanPost["budget"], $cleanPost["priority"], 0);
    }

    if ($add) {
        $tplobj->setTemplate($add);

        if ($hasTpl < 1) {
            $userobj = new user();
            $allusers = $userobj->getAllUsers(100000);
            foreach ($allusers as $usr) {
                if ($usr["role"]["admin"]["add"]) {
                    $tplobj->assign($usr["ID"], $add);
                }
            }
        }

        if ($company > 0) {
            $companyObj->assign($company, $add);
        }

        header("Location: manageproject.php?action=showproject&id=$add");
    }

} elseif ($action == "addpro") {
    if (!$userpermissions["projects"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    if (!$end) {
        $end = 0;
    }

    // START PLUGIN: PROJECT TEMPLATES
    $hasTpl = getArrayVal($_POST, "thetpl");
    if ($hasTpl > 0) {
        $tplobj = new projectTemplates();
        $companyObj = new company();
        $tplCompany = $companyObj->getProjectCompany($hasTpl);

        // Set company to that of the template, if it has one, unless the user actively selected another company
        if ($tplCompany["ID"] > 0 && !($company > 0)) {
            $company = $tplCompany["ID"];
        }
        //add the project
        $add = $tplobj->addFromTemplate($cleanPost["thetpl"], $cleanPost["desc"], $cleanPost["name"], $cleanPost["end"], $cleanPost["budget"], $cleanPost["priority"]);
    } else {
        // END PLUGIN: PROJECT TEMPLATES
        //add the project
        $projectObj = new project();
        $add = $projectObj->add($cleanPost["name"], $cleanPost["desc"], $cleanPost["end"], $cleanPost["budget"], $cleanPost["priority"], 0); // PLUGIN: PRIORITIZATION
    }
    // PLUGIN: PROJECT TEMPLATES
    //project has been added
    if ($add) {
        foreach ($cleanPost["assignto"] as $member) {
            // START PLUGIN: PROJECT TEMPLATES
            // Combine members of project template with users selected in project add form to avoid duplicate assignments
            $members = $projectObj->getProjectMembers($add, 100000, false);
            $isMember = false;
            foreach ($members as $memb) {
                if (!$isMember && $memb["ID"] == $member) {
                    $isMember = true;
                }
            }
            if (!$isMember) {
                $projectObj->assign($member, $add);
            }
            // END PLUGIN: PROJECT TEMPLATES

            if ($settings["mailnotify"]) {
                $usr = (object)new user();
                $user = $usr->getProfile($member);

                if (!empty($user["email"])) {
                    $userlang = readLangfile($user['locale']);

                    $subject = $userlang["projectassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';
                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                        $userlang["projectassignedtext"] .
                        " <a href = \"" . $url . "manageproject.php?action=showproject&id=$add\">" . $url . "manageproject.php?action=showproject&id=$add</a>";
                    // send email
                    $themail = new emailer($settings);
                    $themail->send_mail($user["email"], $subject, $mailcontent);
                }
            }
        }
        if ($cleanPost["company"] > 0) {
            $companyObj->assign($cleanPost["company"], $add);
        }
        echo "ok";
    }
// START PLUGIN: PROJECT TEMPLATES
}