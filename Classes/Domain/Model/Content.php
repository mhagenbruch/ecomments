<?php
namespace Enet\Ecomments\Domain\Model;

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
 * Content model
 */
class Content extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Enet\Ecomments\Domain\Model\Comment>
	 */
	protected $comments;

	/**
	 * @var \Enet\Ecomments\Domain\Model\Pages
	 */
	protected $page;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->comments = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Adds a comment
	 *
	 * @param \Enet\Ecomments\Domain\Model\Comment $comment The comment
	 * @return void
	 */
	public function addComment(\Enet\Ecomments\Domain\Model\Comment $comment) {
		$this->comments->attach($comment);
	}

	/**
	 * Removes a comment
	 *
	 * @param \Enet\Ecomments\Domain\Model\Comment $commentToRemove The comment
	 * @return void
	 */
	public function removeComment(\Enet\Ecomments\Domain\Model\Comment $commentToRemove) {
		$this->comments->detach($commentToRemove);
	}

	/**
	 * Returns the comments
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Enet\Ecomments\Domain\Model\Comment> $comments The comments
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Sets the comments
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Enet\Ecomments\Domain\Model\Comment> $comments The comments
	 * @return void
	 */
	public function setComments(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $comments) {
		$this->comments = $comments;
	}

	/**
	 * Returns the page \Enet\Ecomments\Domain\Model\Pages $page
	 *
	 * @return \Enet\Ecomments\Domain\Model\Pages The page
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * Sets the page
	 *
	 * @param \Enet\Ecomments\Domain\Model\Pages $page The page
	 * @return void
	 */
	public function setPage(\Enet\Ecomments\Domain\Model\Pages $page) {
		$this->page = $page;
	}

}
?>