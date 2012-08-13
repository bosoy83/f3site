<?php
if(iCMSa!=1) exit;

$content->title = 'Chat';
$content->dir = './plugins/chat/';
$content->cache = './cache/chat/';
$content->add('admin', array('cfg' => &$cfg));