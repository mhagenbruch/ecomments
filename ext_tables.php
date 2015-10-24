<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 
	'Enet.' . $_EXTKEY,
	'Ecomments', 
	array(
		'Comment' => 'index'
	)
);


$extensionUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Enet\\Ecomments\\Utility\\ExtensionUtility');
$extensionUtility->addCommentsTab('tt_content');
$extensionUtility->addCommentsTab('pages');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Ecomments Configuration');
?>