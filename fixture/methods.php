<?php

use App\Fixture\Catalog\MethodDto;

return [
    new MethodDto(
        code: 'card',
        title: 'Card',
        description: 'Pay using bank cards'
    ),
    new MethodDto(
        code: 'enthusiast',
        title: 'Enthusiast pay',
        description: 'Pay using enthusiast wallet'
    ),
];
