<article>
    <header>
        <h1>{{ $tag->name }}</h1>
    </header>

    <section>
        <ul>
            @foreach($tag->selections as $selection)
            <li>
                <a href="{{ URL::route('selection.show', $selection->slug) }}">{{ $selection->title }}</a>
            </li>
            @endforeach
        </ul>
    </section>
</article>