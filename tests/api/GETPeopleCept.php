<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET the list of people inside the graph.');
$I->sendGET('/api/v1/people');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('success');
