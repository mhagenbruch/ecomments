<?php
namespace Enet\Ecomments\ViewHelpers;
/**
 * This class is a demo view helper for the Fluid templating engine.
 */
class GravatarViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Adds a user image if user has a gravatar account, if not, a default image will displayed
	 *
	 * @param int $email The users email
	 * @return string Image tag
	 * @author Maik Hagenbruch <maik@hagenbru.ch>
	 */
	public function render($email) {
		if ($this->validate_gravatar($email)) {
			return '<img style="max-width:100%;" src="http://www.gravatar.com/avatar/' . md5($email) . '" />';
		} else {
			return '<img style="max-width:100%;" src="/typo3conf/ext/ecomments/Resources/Public/Images/userImage.png" />';
		}
	}

	/**
	 * If a gravatar account exists for given email function returns true, otherwise returns false
	 *
	 * @param $email
	 * @return bool
	 */
	function validate_gravatar($email) {
		// Craft a potential url and test its headers
		$hash = md5(strtolower(trim($email)));
		$uri = 'http://www.gravatar.com/avatar/' . $hash;
		$headers = @get_headers($uri);
		if (!preg_match("|200|", $headers[0])) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
}

?>