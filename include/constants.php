<?php
/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */

define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "new_dec_1");

/**
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", "users");
define("TBL_ACTIVE_USERS",  "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define("TBL_BANNED_USERS",  "banned_users");
define("TBL_CONFIGURATION", "configuration");
define("TBL_FAMILY", "family");
define("TBL_PEOPLE", "people");
define("TBL_TERMS", "terms");
define("TBL_ATTENDANCE", "attendance");
define("TBL_CLASS", "class");
define("TBL_CLASS_CAT", "classcat");
define("TBL_STUDIO", "studio");
define("TBL_TIMES", "times");
define("TBL_INVIDX", "invidx");
define("TBL_INVTRANS", "invtrans");
define("TBL_BANKING", "banking");
define("TBL_GL", "gl");
define("TBL_FLAGS", "flags");
define("TBL_USER_SESSION", "user_session");

/**
 * Special Names and Level Constants - the admin
 * page will only be accessible to the user with
 * the admin name and also to those users at the
 * admin user level. Feel free to change the names
 * and level constants as you see fit, you may
 * also add additional level specifications.
 * Levels must be digits between 0-9.
 */
define("ADMIN_NAME", "admin");
define("GUEST_NAME", "Guest");
define("ADMIN_LEVEL", 9);
define("REGUSER_LEVEL", 3);
define("ADMIN_ACT", 2);
define("ACT_EMAIL", 1);
define("GUEST_LEVEL", 0);

/**
 * Timeout Constants - these constants refer to
 * the maximum amount of time (in minutes) after
 * their last page fresh that a user and guest
 * are still considered active visitors.
 */
define("USER_TIMEOUT", 2);
define("GUEST_TIMEOUT", 5);
date_default_timezone_set('Pacific/Auckland');

?>
