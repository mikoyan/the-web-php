<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>NewsML G2</title>
</head>
<body>
    <h1>Total Items: {{ count($items) }}</h1>
    <ol>
        {{ foreach $items as $item }}
        <li>{{ $item->headline }}</li>
        {{ /foreach }}
    </ol>
</body>
</html>
