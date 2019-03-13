<?php

// @group mandatory

$I = new AcceptanceTester($scenario);

$I->wantTo('ensure that /timezones GET request returns proper JSON');
$I->sendGET('/timezones');
$I->seeResponseContainsJson([
    'Europe/Samara' => [
        'value' => '(UTC +04:00) Europe/Samara',
        'diff' => '+04:00',
    ],
]);
$I->seeResponseIsJson();
