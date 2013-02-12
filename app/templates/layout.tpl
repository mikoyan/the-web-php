<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

    <title>{{ block "title" }}NewsML G2{{ /block }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="{{ asset href="bootstrap/css/bootstrap.min.css" }}" />
    <link rel="stylesheet" href="{{ asset href="stylesheet.css" }}" />
    {{ block "stylesheet" }}{{ /block }}
</head>
<body>
    <div class="container">
        {{ block "content" }}{{ /block }}
    </div>
</div>

{{ block "script" }}{{ /block }}

</body>
</html>
