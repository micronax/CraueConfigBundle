<?php

namespace Craue\ConfigBundle\Tests\IntegrationTestBundle\Entity;

use Craue\ConfigBundle\Entity\BaseSetting;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CustomSetting extends BaseSetting {

	/**
	 * @var string|null
	 */
	protected $comment;

	public function setComment($comment) {
		$this->comment = $comment;
	}

	public function getComment() {
		return $this->comment;
	}

}
