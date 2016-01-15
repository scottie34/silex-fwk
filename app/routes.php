<?php

//override microrest route to manage request arguments
//$app->get('/api/posts', "microrest.restController:findAll");
//route to be called by console task
$app->get('/api/collect', "silexfwk.postController:collect");
