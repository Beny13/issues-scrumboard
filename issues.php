<?php

$path = ($argv[1] ?? null);
if (!is_dir($path)) {
    throw new Exception('Path is not folder');
}

$token = ($argv[2] ?? null);
if (!is_string($token)) {
    throw new Exception('Invalid token');
}

$teams = [
    'red' => 'En%20cours%20%F0%9F%94%B4',
    'blu' => 'En%20cours%20%F0%9F%94%B5',
];

foreach ($teams as $team => $milestone) {
    $result = file_get_contents(
        "https://api.github.com/search/issues?q=repo:rgsystemes/kb+is:issue+milestone:%22$milestone%22&per_page=100",
        false,
        stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: PHP\r\nAuthorization: token $token",
            ]
        ])
    );

    if (!$result) {
        return;
    }

    file_put_contents("$path/$team.json", $result);
}
