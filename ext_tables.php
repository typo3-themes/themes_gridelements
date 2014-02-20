<?php

#if(!t3lib_extMgm::isLoaded('themes')) {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'themes:gridelements');
#}