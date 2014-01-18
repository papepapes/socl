<?php
$I = new ApiGuy($scenario);
$I->wantTo('Remove friendship between two persons inside the graph.');
$I->sendDELETE('/api/v1/people/4/friends/5');
$I->seeResponseCodeIs(204);
$I->seeResponseEquals('');