<?php
namespace Enet\Ecomments\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Maik Hagenbruch <maik.hagenbruch@e-net.info>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Provides a way to add the comment tab to other tables than tt_content
 */
class ExtensionUtility implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @var string
	 */
	protected $languageFile = 'EXT:ecomments/Resources/Private/Language/locallang_db.xlf:';

	/**
	 * Adds a comment tab to own tables
	 *
	 * @param string $tableName The table
	 * @return void
	 */
	public function addCommentsTab($tableName) {
		$columns = array(
			'tx_ecomments_enable_comments' => array(
				'exclude' => 0,
				'label' => 'LLL:' . $this->languageFile . 'tx_ecomments_enable_comments',
				'config' => array(
					'type' => 'check',
					'default' => 0,
					'items' => array(
						array('LLL:' . $this->languageFile . 'tx_ecomments_enable_comments.description', ''),
					),
				),
			),
			'tx_ecomments_limit' => array(
				'exclude' => 0,
				'label' => 'LLL:' . $this->languageFile . 'tx_ecomments_enable_comments.tx_ecomments_limit',
				'config' => array(
					'type' => 'input',
					'size' => '5',
					'default' => 10,
				),
			),
			'tx_ecomments_comments' => array(
				'exclude' => 0,
				'label' => 'LLL:' . $this->languageFile . 'tx_ecomments_enable_comments.tx_ecomments_comments',
				'config' => array(
					'type' => 'inline',
					'foreign_table' => 'tx_ecomments_domain_model_comment',
					'foreign_field' => 'foreign_uid',
					'appearance' => array(
						'levelLinksPosition' => 'none',
					),
				),
			),
		);

		// Add comments to content elements
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($tableName, $columns);
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
			$tableName,
			'--div--;LLL:' . $this->languageFile . 'tab.comments,tx_ecomments_enable_comments, tx_ecomments_limit, tx_ecomments_comments'
		);
	}

	/**
	 * Adds TypoScript to tables
	 *
	 * @param $tableName
	 * @return void
	 */
	public function addCommentsSetup($tableName) {
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('
			[global]
			' . $tableName . '.stdWrap.append =< tt_content.list.20.ecomments_comment
			' . $tableName . '.stdWrap.append {
				stdWrap.if.isTrue.field = tx_ecomments_enable_comments
				settings {
					tableName = ' . $tableName . '
					uid = TEXT
					uid.field = uid
				}
			}
		');
	}

}
?>