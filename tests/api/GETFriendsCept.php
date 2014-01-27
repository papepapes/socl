<?php
$I = new ApiGuy($scenario);
$I->wantTo('GET a person\'s friends inside the graph.');
$I->sendGET('/api/v1/people/4/friends');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('success');
