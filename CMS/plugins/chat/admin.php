<?php
if(iCMSa!=1) exit;

$content->title = 'Chat';
$content->dir = './plugins/chat/';
$content->cache = './cache/chat/';
$content->file = 'admin';

$content->data['cfg'] =& $cfg;