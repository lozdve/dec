<?php
/**
 * Database.php
 * 
 * The Database class is meant to simplify the task of accessing
 * information from the website's database.
 *
 */
include("constants.php");

class MySQLDB {
    public $connection; //The MySQL database connection
    public $num_active_users; //Number of active users viewing site
    public $num_active_guests; //Number of active guests viewing site
    public $num_members; //Number of signed-up users
    /* Note: call getNumMembers() to access $num_members! */
    
    /* Class constructor */
    function MySQLDB() {
        /* Make connection to database */
        try {
            # MySQL with PDO_MYSQL
            $this->connection = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            echo "Error connecting to database.";
        }
        
        /**
         * Only query database to find out number of members
         * when getNumMembers() is called for the first time,
         * until then, default value set.
         */
        $this->num_members = -1;
        $config            = $this->getConfigs();
        if ($config['TRACK_VISITORS']) {
            /* Calculate number of users at site */
            $this->calcNumActiveUsers();
            /* Calculate number of guests at site */
            $this->calcNumActiveGuests();
        }
    } // MySQLDB function
    
    /**
     * Gather together the configs from the database configuration table.
     */
    function getConfigs() {
        $config = array();
        $sql    = $this->connection->query("SELECT * FROM " . TBL_CONFIGURATION);
        while ($row = $sql->fetch()) {
            $config[$row['config_name']] = $row['config_value'];
        }
        return $config;
    }
    
    /**
     * Update Configs - updates the configuration table in the database
     * 
     */
    function updateConfigs($value, $configname) {
        $query = "UPDATE " . TBL_CONFIGURATION . " SET config_value = :value WHERE config_name = :configname";
        $stmt  = $this->connection->prepare($query);
        return $stmt->execute(array(
            ':value' => $value,
            ':configname' => $configname
        ));
    }
    
    /**
     * confirmUserPass - Checks whether or not the given username is in the database, 
     * if so it checks if the given password is the same password in the database
     * for that user. If the user doesn't exist or if the passwords don't match up, 
     * it returns an error code (1 or 2). On success it returns 0.
     */
    function confirmUserPass($username, $password) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        
        /* Verify that user is in database */
        $query = "SELECT password, usersalt FROM " . TBL_USERS . " WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username
        ));
        $count = $stmt->rowCount();
        
        if (!$stmt || $count < 1) {
            return 1; //Indicates username failure
        }
        
        /* Retrieve password and userlevel from result, strip slashes */
        $dbarray = $stmt->fetch();
        
        $dbarray['usersalt']  = stripslashes($dbarray['usersalt']);
        $password             = stripslashes($password);
        
        $sqlpass = sha1($dbarray['usersalt'] . $password);
        
        /* Validate that password matches and check if userlevel is equal to 1 
        if (($dbarray['password'] == $sqlpass) && ($dbarray['userlevel'] == 1)) {
            return 3; //Indicates account has not been activated
        }*/
        
        /* Validate that password matches and check if userlevel is equal to 2 */
        
        /* Validate that password is correct */
        if ($dbarray['password'] == $sqlpass) {
            return 0; //Success! Username and password confirmed
        } else {
            return 2; //Indicates password failure
        }
    }
    
    /**
     * confirmUserID - Checks whether or not the given username is in the database, 
     * if so it checks if the given userid is the same userid in the database
     * for that user. If the user doesn't exist or if the userids don't match up, 
     * it returns an error code (1 or 2). On success it returns 0.
     */
    function confirmUserID($username, $userid) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        
        /* Verify that user is in database */
        $query = "SELECT userid FROM " . TBL_USERS . " WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username
        ));
        $count = $stmt->rowCount();
        
        if (!$stmt || $count < 1) {
            return 1; //Indicates username failure
        }
        
        $dbarray = $stmt->fetch();
        
        /* Retrieve userid from result, strip slashes */
        $dbarray['userid'] = stripslashes($dbarray['userid']);
        $userid            = stripslashes($userid);
        
        /* Validate that userid is correct */
        if ($userid == $dbarray['userid']) {
            return 0; //Success! Username and userid confirmed
        } else {
            return 2; //Indicates userid invalid
        }
    }
    
    /**
     * usernameTaken - Returns true if the username has been taken by another user, false otherwise.
     */
    function usernameTaken($username) {
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $query = "SELECT username FROM " . TBL_USERS . " WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username
        ));
        $count = $stmt->rowCount();
        return ($count > 0);
    }
    
    /**
     * usernameBanned - Returns true if the username has been banned by the administrator.
     
    function usernameBanned($username) {
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $query = "SELECT username FROM " . TBL_BANNED_USERS . " WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username
        ));
        $count = $stmt->rowCount();
        return ($count > 0);
    }*/
    
    /**
     * addNewUser - Inserts the given (username, password, email) info into the database. 
     * Appropriate user level is set. Returns true on success, false otherwise.
     */
    function addNewUser($email, $password, $token, $usersalt, $firstname, $lastname, $phone) {
        $time   = time();
        $config = $this->getConfigs();
        /* If admin sign up, give admin user level */
        /*
        if (strcasecmp($username, ADMIN_NAME) == 0) {
            $ulevel = ADMIN_LEVEL;
        } else */
        if ($config['ACCOUNT_ACTIVATION'] == 1) {
            $ulevel = REGUSER_LEVEL;
        } else if ($config['ACCOUNT_ACTIVATION'] == 2) {
            $ulevel = ACT_EMAIL;
        } else if ($config['ACCOUNT_ACTIVATION'] == 3) {
            $ulevel = ADMIN_ACT;
        }
        $password = sha1($usersalt . $password);
        $userip   = $_SERVER['REMOTE_ADDR'];
        $query = "INSERT INTO " . TBL_USERS . " SET username = :email, password = :password, usersalt = :usersalt, userid = 0, email = :email, timestamp = $time, actkey = :token, ip = '$userip', regdate = $time, firstname = '$firstname', lastname = '$lastname', phone = '$phone'";
        
        
        $stmt  = $this->connection->prepare($query);
        return $stmt->execute(array(
            ':username' => $email,
            ':password' => $password,
            ':usersalt' => $usersalt,
            ':email' => $email,
            ':token' => $token
        ));
    }
    
    /**
     * updateUserField - Updates a field, specified by the field
     * parameter, in the user's row of the database.
     */
    function updateUserField($username, $field, $value) {
        $query = "UPDATE " . TBL_USERS . " SET " . $field . " = :value WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        return $stmt->execute(array(
            ':username' => $username,
            ':value' => $value
        ));
    }
    
    /**
     * getUserInfo - Returns the result array from a mysql
     * query asking for all information stored regarding
     * the given username. If query fails, NULL is returned.
     */
    function getUserInfo($username) {
        $query = "SELECT * FROM " . TBL_USERS . " WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username
        ));
        $dbarray = $stmt->fetch();
        /* Error occurred, return given name by default */
        $result  = count($dbarray);
        if (!$dbarray || $result < 1) {
            return NULL;
        }
        /* Return result array */
        return $dbarray;
    }

    function displayUsersOptions() {
        global $database, $session;
        
        $query = "SELECT * FROM " . TBL_USERS . " ORDER BY id ASC";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute();
        // foreach($stmt as $row) {
            
        //       echo "<option value=\"".$row['id']."\">".$row['firstname']." ".$row['lastname']."</option>\n";
            
        // }
            $result = $stmt->fetchAll();
        return $result;
    }
    
    function checkUserAccess($access_email) {
        global $database, $session;
        
        $query = "SELECT importable FROM " . TBL_USERS . " WHERE email = '$access_email'";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;

    }

    /**
     * checkUserEmailMatch - Checks whether username
     * and email match in forget password form.
     */
    function checkUserEmailMatch($username, $email) {
        
        $query = "SELECT username FROM " . TBL_USERS . " WHERE username = :username AND email = :email";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username,
            ':email' => $email
        ));
        $number_of_rows = $stmt->rowCount();
        
        if (!$stmt || $number_of_rows < 1) {
            return 0;
        } else {
            return 1;
        }
    }
    
    /**
     * getNumMembers - Returns the number of signed-up users
     * of the website, banned members not included. The first
     * time the function is called on page load, the database
     * is queried, on subsequent calls, the stored result
     * is returned. This is to improve efficiency, effectively
     * not querying the database when no call is made.
     */
    function getNumMembers() {
        if ($this->num_members < 0) {
            $result            = $this->connection->query("SELECT username FROM " . TBL_USERS);
            $this->num_members = $result->rowCount();
        }
        return $this->num_members;
    }
    
    /**
     * getLastUserRegistered - Returns the username of the last
     * member to sign up and the date.
     */
    function getLastUserRegisteredName() {
        $result             = $this->connection->query("SELECT username, regdate FROM " . TBL_USERS . " ORDER BY regdate DESC LIMIT 0,1");
        $this->lastuser_reg = $result->fetchColumn();
        return $this->lastuser_reg;
    }
    
    /**
     * getLastUserRegistered - Returns the username of the last
     * member to sign up and the date.
     */
    function getLastUserRegisteredDate() {
        $result             = $this->connection->query("SELECT username, regdate FROM " . TBL_USERS . " ORDER BY regdate DESC LIMIT 0,1");
        $this->lastuser_reg = $result->fetchColumn(1);
        return $this->lastuser_reg;
    }
    
    /**
     * calcNumActiveUsers - Finds out how many active users
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveUsers() {
        /* Calculate number of USERS at site */
        $sql                    = $this->connection->query("SELECT * FROM " . TBL_ACTIVE_USERS);
        $this->num_active_users = $sql->rowCount();
    }
    
    /**
     * calcNumActiveGuests - Finds out how many active guests
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveGuests() {
        /* Calculate number of GUESTS at site */
        $sql                     = $this->connection->query("SELECT * FROM " . TBL_ACTIVE_GUESTS);
        $this->num_active_guests = $sql->rowCount();
    }
    
    /**
     * addActiveUser - Updates username's last active timestamp
     * in the database, and also adds him to the table of
     * active users, or updates timestamp if already there.
     */
    function addActiveUser($username, $time) {
        $config = $this->getConfigs();
        
        // new - this checks how long someone has been inactive and logs them off if neccessary unless
        // they have cookies (remember me) set.
        
        $query = "SELECT * FROM " . TBL_USERS . " WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username
        ));
        
        $dbarray      = $stmt->fetch();
        $db_timestamp = $dbarray['timestamp'];
        $timeout      = time() - $config['USER_TIMEOUT'] * 60;
        if ($db_timestamp < $timeout && !isset($_COOKIE['cookname']) && !isset($_COOKIE['cookid']))
            header("Location:" . $config['WEB_ROOT'] . "process.php");
        
        $query = "UPDATE " . TBL_USERS . " SET timestamp = :time WHERE username = :username";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username,
            ':time' => $time
        ));
        
        if (!$config['TRACK_VISITORS'])
            return;
        $query = "REPLACE INTO " . TBL_ACTIVE_USERS . " VALUES (:username, :time)";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute(array(
            ':username' => $username,
            ':time' => $time
        ));
        
        $this->calcNumActiveUsers();
    }
    
    /* addActiveGuest - Adds guest to active guests table */
    function addActiveGuest($ip, $time) {
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS'])
            return;
        $sql = $this->connection->prepare("REPLACE INTO " . TBL_ACTIVE_GUESTS . " VALUES ('$ip', '$time')");
        $sql->execute();
        $this->calcNumActiveGuests();
    }
    
    /* These functions are self explanatory, no need for comments */
    
    /* removeActiveUser */
    function removeActiveUser($username) {
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS'])
            return;
        $sql = $this->connection->prepare("DELETE FROM " . TBL_ACTIVE_USERS . " WHERE username = '$username'");
        $sql->execute();
        $this->calcNumActiveUsers();
    }
    
    /* removeActiveGuest */
    function removeActiveGuest($ip) {
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS'])
            return;
        $sql = $this->connection->prepare("DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE ip = '$ip'");
        $sql->execute();
        $this->calcNumActiveGuests();
    }
    
    /* removeInactiveUsers */
    function removeInactiveUsers() {
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS'])
            return;
        $timeout = time() - $config['USER_TIMEOUT'] * 60;
        $stmt    = $this->connection->prepare("DELETE FROM " . TBL_ACTIVE_USERS . " WHERE timestamp < $timeout");
        $stmt->execute();
        $this->calcNumActiveUsers();
    }
    
    /* removeInactiveGuests */
    function removeInactiveGuests() {
        $config = $this->getConfigs();
        if (!$config['TRACK_VISITORS'])
            return;
        $timeout = time() - $config['GUEST_TIMEOUT'] * 60;
        $stmt    = $this->connection->prepare("DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE timestamp < $timeout");
        $stmt->execute();
        $this->calcNumActiveGuests();
    }
    
    
    
    
    function userIdToName($user_id) {
        $query = "SELECT firstname, lastname FROM " . TBL_USERS . " WHERE id = $user_id";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute();
        foreach ($stmt as $row) {
            return $row['firstname'] . " " . $row['lastname'];
        }
    }
    
    function userIdToLicense($user_id) {
        $query = "SELECT license FROM " . TBL_USERS . " WHERE id = $user_id";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute();
        foreach ($stmt as $row) {
            return $row['license'];
        }
    }
    
    function userIdToSupervised($user_id) {
        $query = "SELECT supervised FROM " . TBL_USERS . " WHERE id = $user_id";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute();
        foreach ($stmt as $row) {
            return $row['supervised'];
        }
    }
    
    function userIdToSignature($user_id) {
        $query = "SELECT signature FROM " . TBL_USERS . " WHERE id = $user_id";
        $stmt  = $this->connection->prepare($query);
        $stmt->execute();
        foreach ($stmt as $row) {
            return $row['signature'];
        }
    }
    
    /****** new DEC function ********/

    function editFamily() {
        global $database, $session;
        ?>

        <?php
    }

   
    function getFamilyDetailbyID($fam_id) {
        $query = "SELECT * FROM " . TBL_FAMILY . " WHERE FamilyID = '$fam_id' ";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getPeopleDetailbyID($fam_id) {
        $query = "SELECT * FROM " . TBL_PEOPLE . " WHERE FamilyID = '$fam_id' ";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getTermInfo($fam_id) {
        $query = "SELECT terms.TermID, terms.Term, terms.Year FROM " . TBL_PEOPLE . " 
                    INNER JOIN " . TBL_ATTENDANCE . " ON people.PersonID = attendance.StudentID AND people.familyID = $fam_id
                    INNER JOIN " . TBL_TERMS . " ON terms.TermID = attendance.TermID 
                    GROUP BY attendance.TermID 
                    ORDER BY Year DESC, TermID DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function addNewFamily($fam_code, $fam_last, $fam_phone, $fam_phy1, $fam_phy2, $fam_phy3, $fam_post1, $fam_post2, $fam_post3) {
        $query = "INSERT INTO " . TBL_FAMILY . " (Code, Last, Phone, Physical1, Physical2, Physical3, Post1, Post2, Post3) VALUES ('$fam_code', '$fam_last', '$fam_phone', '$fam_phy1', '$fam_phy2', '$fam_phy3', '$fam_post1', '$fam_post2', '$fam_post3')";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        //return $result;
        return $this->connection->lastInsertId();
    }

    function addNewClass($cls_code, $cls_name, $cls_session, $cls_term, $cls_exam, $cls_examass, $cls_cat, $cls_grade) {
        $query = "INSERT INTO " . TBL_CLASS . " (Code, Class, Session, Term, Exam, ExamAss, Cat, NextGrade) VALUES ('$cls_code', '$cls_name', '$cls_session', '$cls_term', '$cls_exam', '$cls_examass', '$cls_cat', '$cls_grade')";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        //return $result;
        return $this->connection->lastInsertId();
    }

    function getClassDetail($class_id) {
        $query = "SELECT * FROM " . TBL_CLASS . " WHERE ClassID = '$class_id' ";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getClassCat() {
        $query = "SELECT * FROM " . TBL_CLASS_CAT;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getAllClass() {
        $query = "SELECT * FROM " . TBL_CLASS;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getAllStudio(){
        $query = "SELECT * FROM " . TBL_STUDIO;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getAllFamily() {
        $query = "SELECT * FROM " . TBL_FAMILY;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getAllTerms() {
        $query = "SELECT * FROM " . TBL_TERMS ." ORDER BY Year DESC, Term DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getTermByClass($cls_id) {
        $query = "SELECT Year, Term, Day, Time, TimeID, times.TermID, Studio FROM " . TBL_TERMS . ", " . TBL_TIMES . ", " .TBL_STUDIO . " WHERE times.ClassID= '$cls_id' AND times.TermID = terms.TermID GROUP BY times.TimeID ORDER BY terms.Year DESC, terms.Term DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getInvidxByFam($fam_id) {
        $query = "SELECT * FROM " . TBL_INVIDX . " WHERE FamID='$fam_id' ORDER BY TermID DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getInvidxByFamAndTerm($fam_id,$term_id) {
        $query = "SELECT * FROM " . TBL_INVIDX . " WHERE FamID='$fam_id' AND TermID='$term_id' ORDER BY TermID DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getTermByTermID($term_id) {
        $query = "SELECT * FROM " . TBL_TERMS . " WHERE TermID='$term_id'";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getJournalSumByDate($dstart,$dend) {
        $query = "SELECT gl.GL, gl.Description, SUM(Amount) FROM " . TBL_INVIDX . " INNER JOIN gl ON DATE BETWEEN '$dstart' AND '$dend' AND Type='J' AND gl.Print=1 AND invidx.gl = gl.gl GROUP BY gl.GL ORDER BY gl.GL ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getJournalDetailByDate($dstart,$dend) {
        $query = "SELECT gl.GL, gl.Description, family.Last, family.Code, invidx.Amount, invidx.Description, invidx.Date FROM " . TBL_INVIDX . " INNER JOIN gl ON DATE BETWEEN '$dstart' AND '$dend' AND Type='J' AND gl.Print=1 AND invidx.gl = gl.gl INNER JOIN family ON family.FamilyID = invidx.FamID GROUP BY invidx.TransID ORDER BY gl.GL ASC, invidx.Date DESC, family.Code ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getJournalDetailSum($dstart,$dend) {
        $query = "SELECT gl.GL, gl.Description, SUM(Amount) FROM " . TBL_INVIDX . " INNER JOIN gl ON DATE BETWEEN '$dstart' AND '$dend' AND Type='J' AND gl.Print=1 AND invidx.gl = gl.gl";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function getJournalDetailByGL($dstart,$dend) {
        $query = "SELECT gl.GL, gl.Description, SUM(Amount) FROM " . TBL_INVIDX . " INNER JOIN gl ON DATE BETWEEN '$dstart' AND '$dend' AND Type='J' AND gl.Print=1 AND invidx.gl = gl.gl GROUP BY gl.GL ORDER BY gl.GL ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_GROUP);
        return $result;
    }

    function moneyOwingReport() {
        $query = "SELECT Code, Last, SUM(Amount), Post1, Post2, Post3, Physical1, Physical2, Physical3, Phone from ".TBL_INVIDX." INNER JOIN family on family.FamilyID = FamID GROUP BY FamID HAVING SUM(Amount)!=0 ORDER BY CODE";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function debtorsReport($date) {
        $query = "SELECT SUM(Amount) FROM ".TBL_INVIDX." WHERE Date < '$date'";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function debtorsFamilyReport($date) {
        $query = "SELECT Code, Last, SUM(Amount), Post1, Post2, Post3, Physical1, Physical2, Physical3, Phone, Date from ".TBL_INVIDX." INNER JOIN family on family.FamilyID = FamID GROUP BY FamID HAVING SUM(Amount)!=0 AND Date<'$date' ORDER BY CODE";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function updateFamilyCode($fam_id, $key, $fam_value) {
        if($key == 'code') {
            $query = "UPDATE " . TBL_FAMILY . " SET Code='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'last') {
            $query = "UPDATE " . TBL_FAMILY . " SET Last='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'phone') {
            $query = "UPDATE " . TBL_FAMILY . " SET Phone='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'phy1') {
            $query = "UPDATE " . TBL_FAMILY . " SET Physical1='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'phy2') {
            $query = "UPDATE " . TBL_FAMILY . " SET Physical2='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'phy3') {
            $query = "UPDATE " . TBL_FAMILY . " SET Physical3='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'post1') {
            $query = "UPDATE " . TBL_FAMILY . " SET Post1='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'post2') {
            $query = "UPDATE " . TBL_FAMILY . " SET Post2='$fam_value' WHERE FamilyID='$fam_id'";
        } else if ($key == 'post3') {
            $query = "UPDATE " . TBL_FAMILY . " SET Post3='$fam_value' WHERE FamilyID='$fam_id'";
        }
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $fam_id;
    }
    function updateClass($cls_id, $key, $cls_value) {
        if($key == 'code') {
            $query = "UPDATE " . TBL_CLASS . " SET Code='$cls_value' WHERE ClassID='$cls_id'";
        } else if ($key == 'name') {
            $query = "UPDATE " . TBL_CLASS . " SET Class='$cls_value' WHERE ClassID='$cls_id'";
        } else if ($key == 'session') {
            $query = "UPDATE " . TBL_CLASS . " SET Session='$cls_value' WHERE ClassID='$cls_id'";
        } else if ($key == 'term') {
            $query = "UPDATE " . TBL_CLASS . " SET Term='$cls_value' WHERE ClassID='$cls_id'";
        } else if ($key == 'exam') {
            $query = "UPDATE " . TBL_CLASS . " SET Exam='$cls_value' WHERE ClassID='$cls_id'";
        } else if ($key == 'examass') {
            $query = "UPDATE " . TBL_CLASS . " SET ExamAss='$cls_value' WHERE ClassID='$cls_id'";
        } else if ($key == 'classcat') {
            $query = "UPDATE " . TBL_CLASS . " SET Cat='$cls_value' WHERE ClassID='$cls_id'";
        } else if ($key == 'classgrade') {
            $cls_value = intval($cls_value);
            $query = "UPDATE " . TBL_CLASS . " SET NextGrade='$cls_value' WHERE ClassID='$cls_id'";
        } 
        $stmt = $this->connection->prepare($query);
        $result = $stmt->execute();
        return $result;
    }

    function addNewPaymentToInv($date, $ref, $amount, $famid, $method, $termid) {
        $query = "INSERT INTO " .TBL_INVIDX. " (Date, Reference, Description, Amount, FamID, PayType, TermID, Type, Posted) VALUES ('$date', '$ref', '$ref', '$amount', '$famid', '$method', '$termid', 'P', '1')";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        //return $result;
        return $this->connection->lastInsertId();
    }

    function addNewPaymentToBank($date, $ref, $bank, $branch, $amount, $method, $famid) {
        $lastname = $this->getFamilyDetailbyID($famid)[0]['Last'];
        $query = "INSERT INTO " .TBL_BANKING. " (Date, Reference, Description, Amount, Bank, PaymentID, Branch) VALUES ('$date', '$ref', '$lastname', '$amount', '$bank', '$method', '$branch')";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        //return $result;
        return $this->connection->lastInsertId();
    }
}
;

/* Create database connection */
$database = new MySQLDB;

?>