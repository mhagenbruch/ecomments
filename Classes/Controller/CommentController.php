<?php
namespace Enet\Ecomments\Controller;

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
 * Comment controller
 */
class CommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \Enet\Ecomments\Domain\Repository\CommentRepository
	 * @inject
	 */
	protected $commentRepository;

	/**
	 * @var \Enet\Ecomments\Domain\Repository\ContentRepository
	 * @inject
	 */
	protected $contentRepository;

	/**
	 * @var \Enet\Ecomments\Domain\Repository\PagesRepository
	 * @inject
	 */
	protected $pagesRepository;

	/**
	 * @var \Enet\Ecomments\Service\TypoScriptService
	 * @inject
	 */
	protected $typoscriptService;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * @var \TYPO3\CMS\Core\Cache\CacheManager
	 * @inject
	 */
	protected $cacheManager;

	/**
	 * Index action
	 *
	 * @param \Enet\Ecomments\Domain\Model\Comment $comment Comment object
	 * @param integer $identifier
	 * @return void
	 * @dontvalidate $comment
	 */
	public function indexAction(\Enet\Ecomments\Domain\Model\Comment $comment = NULL, $identifier = NULL) {
		if (empty($comment)) {
			$this->view->assign('errors', $this->getValidationErrors($comment));
			$this->initializeSettings();
			$contentObject = $this->configurationManager->getContentObject();
			$limit = (!empty($contentObject->data['tx_ecomments_limit']) ? (int) $contentObject->data['tx_ecomments_limit'] : 10);
			$commentList = $this->commentRepository->findCommentsByTableNameAndUid($this->settings, $limit);
			$comment = $this->objectManager->get('Enet\\Ecomments\\Domain\\Model\\Comment');
			$this->view->assign('commentList', $commentList);
			$this->view->assign('identifier', $contentObject->data['uid']);
			$this->view->assign('table', $this->settings['tableName']);
			$this->view->assign('uid', $this->settings['uid']);
			$this->view->assign('validationHash', $this->getHash($contentObject->data['uid'], $this->settings['tableName'], $this->settings['uid']));
		} else {
			$this->view->assign('errors', $this->getValidationErrors($comment));
			$this->view->assign('status', 'error');
		}
		$this->view->assign('comment', $comment);
	}

	/**
	 * Creates a new comment
	 *
	 * @param \Enet\Ecomments\Domain\Model\Comment $comment Comment object
	 * @return void
	 */
	public function createCommentAction(\Enet\Ecomments\Domain\Model\Comment $comment = NULL) {
		if ($this->isSubmitAllowed($this->request->getArgument('identifier'), $this->request->getArgument('foreignTable'), $this->request->getArgument('foreignUid'), $this->request->getArgument('validationHash'))) {
			try {
				if ($this->settings['verifyComments']) {
					$comment->setHidden(TRUE);
				}
				$comment->setForeignUid($this->request->getArgument('foreignUid'));
				$comment->setForeignTable($this->request->getArgument('foreignTable'));
				$this->commentRepository->add($comment);
				$this->persistenceManager->persistAll();
				if ($this->settings['verifyComments']) {
					$message = $this->translate('sucessMessageWithVerify');
				} else {
					$message = $this->translate('successMessage');
				}
				$status = 'success';
				if ($this->settings['sendNotifications'] > 0) {
					$this->sendNotificationMail($comment->getContent(), $comment->getForeignUid(), $comment->getForeignTable());
				}
				$this->cacheManager->flushCaches();
			} catch (\Exception $exception) {
				$message = $this->translate('errorMessage');
				$status = 'error';
			}
		} else {
			$status = 'error';
			$message = $this->translate('submitNotAllowedErrorMessage');
		}
		$this->view->assign('comment', $comment);
		$this->view->assign('message', $message);
		$this->view->assign('status', $status);
	}

	/**
	 * Translate a label
	 *
	 * @param string $label Label to translate
	 * @param array $arguments Optional arguments array
	 * @return string Translated label
	 */
	protected function translate($label, array $arguments = NULL) {
		$extensionKey = $this->request->getControllerExtensionKey();
		return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($label, $extensionKey, $arguments);
	}

	/**
	 * Initialize settings
	 *
	 * @return void
	 */
	protected function initializeSettings() {
		$this->settings = $this->typoscriptService->parse($this->settings);
	}

	/**
	 * Get validation errors
	 *
	 * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $domainObject The validated domain objects
	 * @return array The validation errors
	 */
	protected function getValidationErrors($domainObject) {
		$originalRequestMappingResults = $this->controllerContext->getRequest()->getOriginalRequestMappingResults();
		$errorFields = $originalRequestMappingResults->forProperty($domainObject);
		return $errorFields->getFlattenedErrors();
	}

	/**
	 * Checks if submit is allowed
	 *
	 * @return boolean TRUE if submit is allowed
	 */
	protected function isSubmitAllowed($ContentObjectUid, $tableName, $uid, $validationHash) {
		$start = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('time')/1000;
		$start = substr($start, 0, -3);
		$end = new \DateTime();
		$end = $end->getTimestamp();
		$time = ($end - (int) $start);
		$botField = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('name');
		return ($time > 5 && empty($botField) && $validationHash === $this->getHash($ContentObjectUid, $tableName, $uid));
	}

	/**
	 * Build hash to check submit
	 *
	 * @param integer $uid The plugin uid
	 * @return string The hash
	 */
	protected function getHash($contentObjectUid, $tableName, $uid) {
		$hashContent = array(
			$contentObjectUid,
			$tableName,
			$uid,
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'],
		);
		return md5(implode('', $hashContent));
	}

	/**
	 * Sends a mail if a new comment was posted
	 * @param string $content The content of comment
	 * @param integer uid The uid of of page or content element
	 * @param string The table name
	 *
	 * @return void
	 */
	protected function sendNotificationMail($content, $uid, $tableName) {
		// get page on which the comment was posted
		if ($tableName == 'tt_content') {
			$pageInfo = $this->contentRepository->findByUid($uid);
			$pageTitle = $pageInfo->getPage()->getTitle();
		}
		else if ($tableName == 'pages') {
			$pageInfo = $this->pagesRepository->findByUid($uid);
			$pageTitle = $pageInfo->getTitle();
		}
		$mailContent = 'A new comment was posted on your Site'."\n";
		$mailContent .= 'The new comment was posted on the site with the title ' . $pageTitle;
		$mailContent .= "\n\n\n\n\n\n\n\n\n\n";

		$mail = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
		$mail->setFrom(array($this->settings['notificationEmailFrom'] => 'ecomments'))
			->setTo(array($this->settings['notificationEmailTo'] => 'TYPO3 Ecomments'))
			->setSubject('New comment')
			->setBody($mailContent)
			->send();
	}


}
?>