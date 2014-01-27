<?php
$I = new ApiGuy($scenario);
$I->wantTo('Save a person inside the graph.');
$I->sendPOST('/api/v1/people', array('id' => 35, 'firstname' => 'Alain', 'surname' => 'Delon', 'gender' => 'Male', 'age' => 44));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('success');
