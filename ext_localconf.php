<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Enet.' . $_EXTKEY,
	'Comment',
	array(
		'Comment' => 'index, createComment',
		
	),
	array(
		'Comment' => 'createComment',
	)
);
$extensionUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Enet\\Ecomments\\Utility\\ExtensionUtility');
//$extensionUtility->addCommentsSetup('pages');
//$extensionUtility->addCommentsSetup('tt_content');
?>