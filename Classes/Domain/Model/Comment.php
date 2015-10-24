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
 * Comment model
 */
class Comment extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var \DateTime
	 */
	protected $crdate;

	/**
	 * @var string
	 * @validate NotEmpty
	 */
	protected $name;

	/**
	 * @var boolean
	 */
	protected $hidden;

	/**
	 * @var integer
	 */
	protected $foreignUid;

	/**
	 * @var string
	 */
	protected $foreignTable;

	/**
	 * @var string
	 * @validate NotEmpty
	 * @validate EmailAddress
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $emailHash;

	/**
	 * @var string
	 * @validate NotEmpty
	 */
	protected $content;

	/**
	 * Sets the crdate
	 *
	 * @param \DateTime $crdate The crdate
	 * @return void
	 */
	public function setCrdate(\DateTime $crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Returns the crdate
	 *
	 * @return \DateTime The crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Sets the name
	 *
	 * @param string $name The name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the name
	 *
	 * @return string The name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the hidden state
	 *
	 * @param boolean $hidden The hidden state
	 * @return void
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * Returns the hidden state
	 *
	 * @return boolean The hidden state
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Sets the foreign uid
	 *
	 * @param integer $foreignUid The foreignUid
	 * @return void
	 */
	public function setForeignUid($foreignUid) {
		$this->foreignUid = $foreignUid;
	}

	/**
	 * Returns the foreign uid
	 *
	 * @return integer The foreign uid
	 */
	public function getForeignUid() {
		return $this->foreignUid;
	}

	/**
	 * Sets the foreign table
	 *
	 * @param string $foreignTable The foreignTable
	 * @return void
	 */
	public function setForeignTable($foreignTable) {
		$this->foreignTable = $foreignTable;
	}

	/**
	 * Returns the foreign table
	 *
	 * @return string The foreign table
	 */
	public function getForeignTable() {
		return $this->foreignTable;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email The email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
		if (!empty($email)) {
			$this->setEmailHash(md5($email));
		} else {
			$this->setEmailHash('');
		}
	}

	/**
	 * Returns the email
	 *
	 * @return string The email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email hash
	 *
	 * @param string $email The email
	 * @return void
	 */
	public function setEmailHash($emailHash) {
		$this->emailHash = $emailHash;
	}

	/**
	 * Returns the email hash
	 *
	 * @return string The email hash
	 */
	public function getEmailHash() {
		return $this->emailHash;
	}

	/**
	 * Sets the content
	 *
	 * @param string $content The content
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * Returns the content
	 *
	 * @return string The content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Returns comment array
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'name' => $this->getName(),
			'email' => $this->getEmail(),
			'content' => $this->getContent(),
			'hashedEmail' => $this->getEmailHash(),
			'hidden' => $this->getHidden(),
		);
	}

}
?>