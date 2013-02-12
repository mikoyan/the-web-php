{{ extends file="layout.tpl" }}

{{ block "content" }}
<h1>by Reuters</h1>

<div class="row">

<section class="span8">
    {{ list_items type="news" length=21 }}
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
                    <img src="{{ media href=$content->href }}" width="{{ $content->width }}" height="{{ $content->height }}" alt="" title="{{ $item->description|escape }}" />
                </figure>
                {{ /if }}
            {{ /foreach }}
        {{ /if }}

        <footer>&copy; {{ $item->creditline|escape }}</footer>
    </article>
    {{ /list_items }}
</section>

<section class="span4 pictures">
    {{ list_items type="news" class="icls:picture" length=13 }}
        {{ foreach $item->content->remote as $content }}
        {{ if $content->rendition == 'rend:thumbnail' }}
        <figure style="width:{{ $content->width }}px">
            <img src="{{ media href=$content->href }}" width="{{ $content->width }}" height="{{ $content->height }}" alt="" title="{{ $item->contentMeta->description|escape }}" />
        </figure>
        {{ /if }}
        {{ /foreach }}
    {{ /list_items }}
</section>

</div><!-- /.row -->
{{ /block }}

{{ block "script" }}
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="{{ asset href="bootstrap/js/bootstrap.min.js" }}"></script>
<script>
$(function() {
    $('.pictures img').tooltip({'placement': 'bottom'});
});
</script>
{{ /block }}
