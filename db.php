<?php

define('DB_HOST', 'helmi.cs.colostate.edu');
define('DB_USER', ''); // Replace with your actual database username
define('DB_PASS', ''); // Replace with your actual database password
define('DB_NAME', ''); // Replace with your actual database name


define('SSL_CERT', '/usr/local/ssl/server-cert.pem');
define('SSL_CA',   '/usr/local/ssl/ca-cert.pem');


/* -------------------------------------------------------
 * SECTION 1 — Database Connection
 * -------------------------------------------------------
 * The connection is set up for you below, do not modify this section.
 * If your credentials above are correct, this will connect you
 * to your database on helmi automatically.
 */

$conn = mysqli_init();
if (!$conn) {
    die('mysqli_init failed.');
}
$conn->ssl_set(SSL_CERT, NULL, SSL_CA, NULL, NULL);
mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
if (!mysqli_real_connect($conn, DB_HOST, DB_USER, DB_PASS, DB_NAME)) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>