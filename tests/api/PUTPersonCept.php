<?php
$I = new ApiGuy($scenario);
$I->wantTo('Update a person\'s  record inside the graph.');
$I->sendPUT('/api/v1/people/4', array('id' => 4, 'firstname' => 'Alain', 'surname' => 'Delon', 'gender' => 'Male', 'age' => 44));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('success');
