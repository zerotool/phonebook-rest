<?php

// @group mandatory

$I = new AcceptanceTester($scenario);

$I->wantTo('ensure that /countries GET request returns proper JSON');
$I->sendGET('/countries');
$I->seeResponseContainsJson(['US' => 'United States of America']);
$I->seeResponseIsJson();
