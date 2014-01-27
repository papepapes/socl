<?php
$I = new ApiGuy($scenario);
$I->wantTo('DELETE a person from the graph.');
$I->sendDELETE('/api/v1/people/8');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('success');

