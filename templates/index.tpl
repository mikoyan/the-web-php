<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

    <title>NewsML G2</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="http://localhost/reuters-php/web/assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="http://localhost/reuters-php/web/assets/stylesheet.css" />
</head>
<body>
<div class="container">
    <h1>by Reuters</h1>

    <div class="row">
    <section class="span8">
        {{ list_items type="news" length=130 }}
        <article class="item">
            <header>
                <strong>{{ $item->slugline|escape }}</strong>
                <h2>{{ $item->headline|escape }}</h2>
                <address>{{ if $item->byline }}by {{ $item->byline|escape }} {{ /if }}on {{ $item->itemMeta->versionCreated|date_format:"H:i d/m/y" }}</address>
            </header>

            {{ if isset($item->content) && isset($item->content->inline) }}
            <pre>{{ $item->content->inline->content|parse_body }}</pre>
            {{ elseif isset($item->content) && !empty($item->content->remote) }}
                {{ foreach $item->content->remote as $content }}
                    {{ if $content->rendition == 'rend:viewImage' }}
                    <figure>
                        <img src="{{ media href=$content->href }}" width="{{ $content->width }}" height="{{ $content->height }}" alt="" />
                    </figure>
                    {{ /if }}
                {{ /foreach }}
            {{ /if }}

            <footer>&copy; {{ $item->creditline|escape }}</footer>
        </article>
        {{ /list_items }}
    </section>

    <section class="span4 pictures">
        {{ list_items type="news" class="icls:picture" length=210 }}
            {{ foreach $item->content->remote as $content }}
            {{ if $content->rendition == 'rend:thumbnail' }}
            <figure style="width:{{ $content->width }}px">
                <img src="{{ media href=$content->href token="Et1dUihyQQPjHGpsRaoFrhgsgWDjIfhuyfbfQ+HdRaQ=" }}" width="{{ $content->width }}" height="{{ $content->height }}" alt="" title="{{ $item->contentMeta->description|escape }}" />
            </figure>
            {{ /if }}
            {{ /foreach }}
        {{ /list_items }}
    </section>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="http://localhost/reuters-php/web/assets/bootstrap/js/bootstrap.min.js"></script>
<script>
$(function() {
    $('.pictures img').tooltip({'placement': 'bottom'});
});
</script>

</body>
</html>
