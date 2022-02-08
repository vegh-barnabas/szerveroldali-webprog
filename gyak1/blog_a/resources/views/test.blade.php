<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Hello</h1>

    <?php
        echo 'alma';
    ?>
    <?= 'alma' ?>

    @php
        $fruit = 'apple';
    @endphp

    <h1>{{ $fruit }}</h1>

    @isset($fruit)
        <h2>Van gyümölcs</h2>
    @endisset

    <ul>
    @for ($i = 0; $i < 5; $i++)
        <li>{{ $i }}</li>
    @endfor
    </ul>

    @php
        $fruits = ['apple', 'pear', 'strawberry'];
    @endphp

    <ul>
        @foreach ($fruits as $fruit)
            <li>{{ $loop->iteration }}. {{ $fruit }}</li>
        @endforeach
    </ul>

    @php
        $fruits_empty = [];
    @endphp

    <ul>
        @forelse ($fruits_empty as $fruit)
            <li>{{ $loop->iteration }}. {{ $fruit }}</li>
        @empty
            <h2>A lista üres</h2>
        @endforelse
    </ul>

</body>
</html>
