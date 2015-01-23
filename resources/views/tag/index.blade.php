<article>
    <header>
        <h1></h1>
    </header>

    <section>
        <ul>
            @foreach($tags as $tag)
            <li>
                <a href="{{ URL::route('tag.show', $tag->slug) }}">{{ $tag->name }}</a>
            </li>
            @endforeach
        </ul>
    </section>
</article>