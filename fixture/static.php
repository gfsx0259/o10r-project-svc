<?php

use App\Fixture\Gateway\ResourceDto;

return [
    new ResourceDto(
        name: 'Saved cards',
        path: '/customer/cards',
        contentPath: 'data/resources/accounts.json'
    ),
];
