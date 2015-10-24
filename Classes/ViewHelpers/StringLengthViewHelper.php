<?php
namespace Enet\Ecomments\ViewHelpers;
/**
 * This class is a demo view helper for the Fluid templating engine.
 */
class StringLengthViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Counts the chars of a string
	 *
	 * @param string $string The string to be counted
	 * @return int $stringLength The length of given string
	 * @author Maik Hagenbruch <maik@hagenbru.ch>
	 */
	public function render($string) {
		return strlen($string);
	}
}

?>