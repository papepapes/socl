<?php
$I = new ApiGuy($scenario);
$I->wantTo('DELETE a person from the graph.');
$I->sendDELETE('/api/v1/people/4');
$I->seeResponseCodeIs(204);
$I->seeResponseEquals('');

