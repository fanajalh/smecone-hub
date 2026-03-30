<?php
$ch = curl_init('http://127.0.0.1:8000/forum/1/message');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
// Send valid CSRF ? We don't have it. We'll get 419.
// But wait, we can bypass CSRF by hitting the route directly via Laravel testing or just disabling CSRF for this route temporarily.
// Let's just create a route in web.php that dumps Validate request!
