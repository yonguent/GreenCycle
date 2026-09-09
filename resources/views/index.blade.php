<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Arboles disponibles</h1>
    <div>
        @foreach ($trees as $tree)
            <p>level: {{ $tree->level }}</p>
            <p>health: {{ $tree->health }}</p>
            <p>progress: {{ $tree->progress }}</p>
            <p>status: {{ $tree->status }}</p>
            <p>planted_at: {{ $tree->planted_at }}</p>
            <p>last_cared_at: {{ $tree->last_cared_at }}</p>
            <p>next_care_at: {{ $tree->next_care_at }}</p>
            <p>last_decay_at: {{ $tree->last_decay_at }}</p>
            <p>harvested_at: {{ $tree->harvested_at }}</p>
            <p>user_id: {{ $tree->user_id }}</p>
            <hr>
        @endforeach
    </div>
</body>
</html>