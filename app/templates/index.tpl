{{ extends file="layout.tpl" }}

{{ block "title" }}Top News{{ /block }}

{{ block "content" }}
<section>
    <h2>Top News</h2>

    {{ list_items type="package" length=8 }}
    <article class="package">
        {{ $package = $gimme->item }}
        {{ package_items class="icls:picture" group="main" length=1 }}
            {{ remote_content rendition="rend:viewImage" }}
            <figure class="package-fig">
                <a href="{{ item_url item=$package }}"><img src="{{ media href=$content->href }}" width="{{ $content->width }}" height="{{ $content->height }}" alt="" title="{{ $gimme->item->description|escape }}" /></a>
            </figure>
            {{ /remote_content }}
        {{ /package_items }}

        <p>{{ $gimme->item->slugline|escape }}<br />{{ $gimme->item->itemMeta->versionCreated|date_format:"r" }}</p>
        <h1><a href="{{ item_url item=$package }}">{{ $gimme->item->headline|escape }}</a></h1>

        <footer>&copy; {{ $gimme->item->creditline|escape }}</footer>
    </article>
    {{ /list_items }}
</section>

<section class="ticker">
    <h2>Ticker</h2>
    
    {{ list_items type="news" length=21 }}
    <article class="news">
        <header>
            <p>{{ $gimme->item->slugline|escape }}</p>
            <h4>{{ $gimme->item->headline|escape }}</h3>
            <address>{{ if $gimme->item->byline|escape }}by {{ $gimme->item->byline|escape }} {{ /if }}on {{ $gimme->item->itemMeta->versionCreated|date_format:"H:i d/m/y" }}</address>
        </header>

        {{ text }}
        <pre>{{ $gimme->item->description|escape }}</pre>
        {{ /text }}

        {{ picture }}
            {{ remote_content rendition="rend:viewImage" }}
            <figure>
                <img src="{{ media href=$content->href }}" width="{{ $content->width }}" height="{{ $content->height }}" alt="" title="{{ $gimme->item->description|escape }}" />
            </figure>
            {{ /remote_content }}
        {{ /picture }}

        <footer>&copy; {{ $gimme->item->creditline|escape }}</footer>
    </article>
    {{ /list_items }}
</section>
{{ /block }}
