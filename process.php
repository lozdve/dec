<?php
include("include/session.php");

class Process {
    /* Class constructor */
    function Process() {
        global $session,$database;

        $config = $database->getConfigs();
        /* User submitted login form */
        if (isset($_POST['sublogin'])) {
            $this->procLogin();
        }
        /* User submitted registration form */
        else if (isset($_POST['subjoin'])) {
            $this->procRegister();
        }
        /* User submitted forgot password form */
        else if (isset($_POST['subforgot'])) {
            $this->procForgotPass();
        }
        /* User submitted edit account form */
        else if (isset($_POST['subedit'])) {
            $this->procEditAccount();
        } 
        else if (isset($_POST['newfamily'])) {
            $this->addNewFamily();
        }
        else if (isset($_POST['newclass'])) {
            $this->addNewClass();
        }
        else if (isset($_POST['new-payment'])) {
            $this->addNewPayment();
        }
        else if (isset($_POST['invterm'])) {
            $this->addNewTermInv();
        }
        else if (isset($_POST['fam_code'])) {
            $this->checkCode($_POST['fam_code']);
        }
        else if (isset($_POST['new_journal'])) {
            $this->addNewJournal();
        }
        else if (isset($_POST['acc_code'])) {
            $this->updateGL($_POST['acc_code'], $_POST['acc_print']);
        }
        else if (isset($_POST['curr_term'])) {
            $this->update_default_term($_POST['curr_term']);
        }
        else if (isset($_POST['saved_invno'])) {
            $this->updateInvidx($_POST['saved_invno']);
        }
        else if (isset($_POST['get_update_inv'])) {
            $this->getInvDate($_POST['invno']);
        }
        else if (isset($_POST['update_inv'])) {
            $this->updateInvDate($_POST['invno'], $_POST['date']);
        }
        else if (isset($_POST['del_banking'])) {
            $this->delAllBanking();
        }
        /**
         * The only other reason user should be directed here
         * is if he wants to logout, which means user is
         * logged in currently.
         */
        else if ($session->logged_in) {
            $this->procLogout();
        }
        /**
         * Should not get here, which means user is viewing this page
         * by mistake and therefore is redirected.
         */
        else {
            header("Location: " . $config['WEB_ROOT'] . $config['home_page']);
        }
    }
    
    /**
     * procLogin - Processes the user submitted login form, if errors
     * are found, the user is redirected to correct the information,
     * if not, the user is effectively logged in to the system.
     */
    function procLogin() {
        global $session, $form;
        /* Login attempt */
        $retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));
        
        /* Login successful */
        if ($retval==0) {
            header("Location: main");
        }
        /* Login failed */
        else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();

            header("Location: " . $session->referrer);
        }
    }
    
    /**
     * procLogout - Simply attempts to log the user out of the system
     * given that there is no logout form to process.
     */
    function procLogout() {
        global $database, $session;
        $config = $database->getConfigs();
        $retval = $session->logout();
        //header("Location: ".$config['WEB_ROOT']);
        header("Location: index");
    }
    
    /**
     * procRegister - Processes the user submitted registration form,
     * if errors are found, the user is redirected to correct the
     * information, if not, the user is effectively registered with
     * the system and an email is (optionally) sent to the newly
     * created user.
     */
    function procRegister() {
        global $database, $session, $form;
        $config = $database->getConfigs();
        
        /* Checks if registration is disabled */
        if ($config['ACCOUNT_ACTIVATION'] == 4) {
            $_SESSION['reguname']   = $_POST['email'];
            $_SESSION['regsuccess'] = 6;
            header("Location: " . $session->referrer);
        }
        
        /* Hidden form field captcha deisgned to catch out auto-fill spambots */
        if (!empty($_POST['killbill'])) {
            $retval = 2;
        } else {
            /* Registration attempt */
            
            $retval = $session->register($_POST['email'], $_POST['pass'], $_POST['conf_pass'], $_POST['firstname'], $_POST['lastname'], $_POST['phone']);
        }
        
        /* Registration Successful */
        if ($retval == 0) {
            $_SESSION['reguname']   = $_POST['firstname'] . " " . $_POST['lastname'];
            $_SESSION['regsuccess'] = 0;
            header("Location: " . $session->referrer);
        }
        /* E-mail Activation */
        else if ($retval == 3) {
            $_SESSION['reguname']   = $_POST['firstname'] . " " . $_POST['lastname'];
            $_SESSION['regsuccess'] = 3;
            header("Location: " . $session->referrer);
        }
        /* Admin Activation */
        else if ($retval == 4) {
            $_SESSION['reguname']   = $_POST['firstname'] . " " . $_POST['lastname'];
            $_SESSION['regsuccess'] = 4;
            header("Location: " . $session->referrer);
        }
        /* No Activation Needed but E-mail going out */
        else if ($retval == 5) {
            $_SESSION['reguname']   = $_POST['firstname'] . " " . $_POST['lastname'];
            $_SESSION['regsuccess'] = 5;
            header("Location: " . $session->referrer);
        }
        /* Error found with form */
        else if ($retval == 1) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
        /* Registration attempt failed */
        else if ($retval == 2) {
            $_SESSION['reguname']   = $_POST['firstname'] . " " . $_POST['lastname'];
            $_SESSION['regsuccess'] = 2;
            header("Location: " . $session->referrer);
        }
    }
    
    /**
     * procForgotPass - Validates the given username then if
     * everything is fine, a new password is generated and
     * emailed to the address the user gave on sign up.
     */
    function procForgotPass() {
        global $database, $session, $mailer, $form;
        $config   = $database->getConfigs();
        /* Username error checking */
        $subuser  = $_POST['user'];
        $subemail = $_POST['email'];
        $field    = "user"; //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Username not entered <br>");
        } else {
            /* Make sure username is in database */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < $config['min_user_chars'] || strlen($subuser) > $config['max_user_chars'] || !preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subuser) || (!$database->usernameTaken($subuser))) {
                $form->setError($field, "* Username does not exist <br> ");
            } else if ($database->checkUserEmailMatch($subuser, $subemail) == 0) {
                $form->setError($field, "* No Match <br> ");
            }
        }
        
        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
        }
        /* Generate new password and email it to user */
        else {
            /* Generate new password */
            $newpass = $session->generateRandStr(8);
            
            /* Get email of user */
            $usrinf = $database->getUserInfo($subuser);
            $email  = $usrinf['email'];
            
            /* Attempt to send the email with new password */
            if ($mailer->sendNewPass($subuser, $email, $newpass, $config)) {
                /* Email sent, update database */
                $usersalt = $session->generateRandStr(8);
                $newpass  = sha1($usersalt . $newpass);
                $database->updateUserField($subuser, "password", $newpass);
                $database->updateUserField($subuser, "usersalt", $usersalt);
                $_SESSION['forgotpass'] = true;
            }
            /* Email failure, do not change password */
            else {
                $_SESSION['forgotpass'] = false;
            }
        }
        
        header("Location: " . $session->referrer);
    }
    
    /**
     * procEditAccount - Attempts to edit the user's account
     * information, including the password, which must be verified
     * before a change is made.
     */
    function procEditAccount() {
        global $session, $form;
        /* Account edit attempt */
        $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['conf_newpass'], $_POST['email'], $_POST['userregion']);
        
        /* Account edit successful */
        if ($retval) {
            $_SESSION['useredit'] = true;
            header("Location: " . $session->referrer);
        }
        /* Error found with form */
        else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }
    
    function createThumbs($pathToImages, $pathToThumbs, $thumbWidth) {
        // open the directory
        $dir = opendir($pathToImages);
        
        // loop through it, looking for any/all JPG files:
        while (false !== ($fname = readdir($dir))) {
            // parse path for the extension
            $info = pathinfo($pathToImages . $fname);
            // continue only if this is a JPEG image
            if (strtolower($info['extension']) == 'jpg') {
                // load image and get image size
                $img    = imagecreatefromjpeg("{$pathToImages}{$fname}");
                $width  = imagesx($img);
                $height = imagesy($img);
                
                // calculate thumbnail size
                $new_width  = $thumbWidth;
                $new_height = floor($height * ($thumbWidth / $width));
                
                // create a new tempopary image
                $tmp_img = imagecreatetruecolor($new_width, $new_height);
                
                // copy and resize old image into new image 
                imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                
                // save thumbnail into a file
                imagejpeg($tmp_img, "{$pathToThumbs}{$fname}");
            }
        }
        // close the directory
        closedir($dir);
    }

    function addNewFamily() {        
        global $session,$database;
        $result = $database->addNewFamily($_POST['fam-code'], $_POST['fam-last'], $_POST['fam-phone'], $_POST['fam-phy1'], $_POST['fam-phy2'], $_POST['fam-phy3'], $_POST['fam-post1'], $_POST['fam-post2'], $_POST['fam-post3']);
        if ($result) {
            header("Location: /dec/edit_family?fam_id=" . $result);
        } else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function addNewClass() {        
        global $session,$database;
        $result = $database->addNewClass($_POST['class-code'], $_POST['class-name'], $_POST['class-session'], $_POST['class-term'], $_POST['class-exam'], $_POST['class-examass'], $_POST['class-cat'], $_POST['class-grade']);
        if ($result) {
            header("Location: /dec/edit_class?class_id=" . $result);
        } else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function addNewPayment() {
        global $session, $database, $form;
        $result = $database->addNewPaymentToInv($_POST['report-date'], $_POST['report-ref'], $_POST['report-amount'], $_POST['select-fam'], $_POST['report-method'], $_POST['report-term']);
        $result1 = $database->addNewPaymentToBank($_POST['report-date'], $_POST['report-ref'], $_POST['report-bank'], $_POST['report-branch'], $_POST['report-amount'], $_POST['report-method'], $_POST['select-fam']);

        if ($result && $result1) {
            header("Location: /dec/receive_payment");
        } else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function addNewJournal() {
        global $database, $form;
        $result = $database->addNewJournal($_POST['report-term'], $_POST['select-fam'], $_POST['report-date'], $_POST['report-desc'], $_POST['report-amount'], $_POST['report-acct']);
        if ($result) {
            header("Location: /dec/receive_journal");
        } else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function addNewTermInv() {
        global $session, $database, $form;
        $result = $database->addNewTermInvidx($_POST['saved_term'], $_POST['saved_date'],$_POST['saved_type']);
        $result1 = $database->addNewTermInvtrans($_POST['saved_term'], $_POST['saved_date'],$_POST['saved_type']);
        if ($result || $result1) {
            header("Location: /dec/saved_invoices");
        } else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function checkCode($famcode) {
        global $session, $database;
        $result = $database->checkCode($famcode);
        // var_dump($result);

        echo json_encode($result);
    }

    function updateGL($acc_code, $acc_print) {
        global $database, $form;
        $result = $database->updateGL($acc_code, $acc_print);
        if(!$result) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function update_default_term($val) {
        global $database, $form;
        $result = $database->update_default_term($val);
        if(!$result) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function updateInvidx($invno) {
        global $database, $form;
        $result = $database->updateInvidx($invno);
        if(!$result) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    function getInvDate($invno) {
        global $database, $form;
        $result = $database->getInvidxByInvNo($invno);
        if(!$result) {
            return false;
        }
        else
            echo json_encode($result);
    }

    function updateInvDate($invno, $date) {
        global $database, $form;
        $result = $database->updateInvDate($invno, $date);
        return $result;
    }

    function delAllBanking() {
        global $database, $form;
        $result = $database->delAllBanking();
        return $result;
    }
}
;


/* Initialize process */
$process = new Process;

?>