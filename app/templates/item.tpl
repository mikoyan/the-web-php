{{ extends file="layout.tpl" }}

{{ block "title" }}{{ $gimme->item->headline|escape }}{{ /block }}

{{ block "content" }}
<p>{{ $gimme->item->slugline|escape }}<br />{{ $gimme->item->itemMeta->versionCreated|date_format:"r" }}</p>
<h1>{{ $gimme->item->headline|escape }}</h1>

{{ package }}
<div class="row">
    <article class="span8">
        {{ package_items group="main" class="icls:text" }}
        <div>{{ $gimme->item->content->inline->content|parse_body }}</div>
        {{ /package_items }}
        <footer>&copy; {{ $gimme->item->creditline|escape }}</footer>
    </article>

    <aside class="span4">
        {{ package_items class="icls:picture" group="main" }}
            {{ remote_content rendition="rend:viewImage" }}
            <figure style="margin-bottom:5px;">
                <img src="{{ media href=$content->href }}" width="{{ $content->width }}" height="{{ $content->height }}" alt="" title="{{ $gimme->item->description|escape }}" />
                <figcaption>&copy; {{ $gimme->item->creditline|escape }}</figcaption>
            </figure>
            {{ /remote_content }}
        {{ /package_items }}

        <h3>Related</h3>
        {{ package_items group="sidebars" }}
            <h4><a href="{{ item_url }}">{{ $gimme->item->headline|escape }}</a></h4>
        {{ /package_items }}
    </aside>
</div>
{{ /package }}

{{ text }}
    <div>{{ $content|parse_body }}</div>
{{ /text }}

{{ picture }}
<h3>Picture</h3>
{{ /picture }}

{{ /block }}
