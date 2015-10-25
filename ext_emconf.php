<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Ecomments',
	'description' => 'Ecomments allows your website visitors to leave comments on articles or entire pages.',
	'category' => 'plugin',
	'author' => 'Maik Hagenbruch',
	'author_email' => 'maik.hagenbruch@e-net.info',
	'author_company' => 'E-net Development',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '2.0.0',
	'constraints' => array(
		'depends' => array(
			'extbase' => '1.3',
			'fluid' => '1.3',
			'typo3' => '7.5.0-7.5.x',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>
