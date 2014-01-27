<?php
$I = new ApiGuy($scenario);
$I->wantTo('Lookup for a person by his ID inside the graph.');
$I->sendGET('/api/v1/people/2');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('success');
