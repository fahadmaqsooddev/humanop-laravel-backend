<!DOCTYPE html>
<html>
<body>

<script>
    window.opener.postMessage(
        { type: "GOOGLE_CALENDAR_CONNECTED" },
        "{{ config('app.frontend_url') }}"
    );

    window.close();
</script>

</body>
</html>
