<?php

// @group mandatory

$I = new AcceptanceTester($scenario);

$I->wantTo('ensure that home page returns JSON');
$I->sendGET('/contacts/1');
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'id' => '1',
    'first_name' => 'test1',
    'last_name' => 'test6',
    'phone_number' => '1234',
    'country_code' => 'RU',
    'timezone' => 'Pacific/Midway'
]);
