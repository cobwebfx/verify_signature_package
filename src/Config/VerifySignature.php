<?php
return [
    'keyIdentifier' => 'tokenId',
    'keys'          => [
        'cobwebfx-uk' => [
            'key' => env('VERIFY_SIG.KEYS.cobwebfx-uk.KEY'),
            'alg' => env('VERIFY_SIG.KEYS.cobwebfx-uk.ALG'),
        ],
    ],
];
