<?php
namespace Enet\Ecomments\View;

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
 * Abstract JSON view
 */
abstract class AbstractJsonView extends \TYPO3\CMS\Extbase\Mvc\View\AbstractView {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Render template content
	 *
	 * @return void
	 */
	public function render() {
		$response = $this->getResponseContent();
		$this->sendResponse(json_encode($response));
	}

	/**
	 * Get response content
	 *
	 * @return array Response content
	 */
	abstract protected function getResponseContent();

	/**
	 * Send response to browser
	 *
	 * @param string $content The response content
	 * @return void
	 */
	protected function sendResponse($content) {
		$response = $this->objectManager->create('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Response');
		$response->setHeader('Content-Type', 'application/json; charset=utf-8');
		$response->setContent(trim($content));
		$response->sendHeaders();
		$response->send();
		exit;
	}

}
?>