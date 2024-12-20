<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open App</title>
</head>
<body>
<h1>Open HumanOp App</h1>
<p>If the app is installed, clicking the link below will open the app:</p>
<a href="humanOp://open/">Open App</a>

<script>
    // Fallback in case the app is not installed, redirect to Play Store (Android)
    setTimeout(function() {
        window.location.href = "https://play.google.com/store/apps/details?id=com.yourpackage.name";
    }, 2000);  // Wait 2 seconds to allow app to open
</script>
</body>
</html>
