<?php
echo "PHP version: ". phpversion();

echo "\n";

echo "Test DB connection: \n";
$link = mysqli_connect("mysql", "root", "killcovid", null);

/* check connection */
if (mysqli_connect_errno()) {
    printf("MySQL connection failed: %s \n", mysqli_connect_error());
} else {
    /* print server version */
    printf("Connected: MySQL Server %s \n", mysqli_get_server_info($link));
}
/* close connection */
mysqli_close($link);
echo "\n";
