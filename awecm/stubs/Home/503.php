<?php
header("HTTP/1.1 503 Service Unavailable");
header("Status: 503 Service Unavailable");
header("Retry-After: 3600");
?>
<!DOCTYPE html>
<html>
<head>
<title>Temporarily Unavailable</title>
<meta name="robots" content="none" />
</head>
<body>
   Temporarily Unavailable
</body>
</html>