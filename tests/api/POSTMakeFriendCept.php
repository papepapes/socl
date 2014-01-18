<?php
$I = new ApiGuy($scenario);
$I->wantTo('Make friends of two persons inside the graph.');
$I->sendPOST('/api/v1/people/4/friends/5');
$I->seeResponseCodeIs(200);
$I->seeResponseContains('/api/v1/people/4/friends');
