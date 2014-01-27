<?php
$I = new ApiGuy($scenario);
$I->wantTo('Remove friendship between two persons inside the graph.');
$I->sendDELETE('/api/v1/people/6/friends/5');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('success');