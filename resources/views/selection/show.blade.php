<article>
    <header>
        <h1>{{ $selection['title'] }}</h1>
        <ul>
            @foreach($selection['tags'] as $tag)
                <li><a href="{{ URL::route('tag.show', $tag['slug']) }}">{{ $tag['name'] }}</a></li>
            @endforeach
        </ul>
    </header>

    <section>

        <ol>
            @foreach($selection['products'] as $product)
            <li>
                <div>
                    <h2>{{ $product['brand']['name'] }} {{ $product['name'] }}</h2>
                    <p>{{ $product['description'] }}</p>

                    <ul>
                        @foreach($product['tags'] as $tag)
                        <li><a href="{{ URL::route('tag.show', $tag['slug']) }}">{{ $tag['name'] }}</a></li>
                        @endforeach
                    </ul>

                    <ol>
                        @foreach($product['offers'] as $offer)
                        <li>
                            <div>
                                <h4>{{ $offer['shop']['name'] }}</h4>
                                {{ $offer['price'] }}
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </li>
            @endforeach
        </ol>
    </section>
</article>