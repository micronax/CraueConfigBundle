<?php

namespace Craue\ConfigBundle\Repository;

use Craue\ConfigBundle\Entity\SettingInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class SettingRepository extends EntityRepository {

	/**
	 * @param string[] $names
	 * @return SettingInterface[] Array of settings, indexed by name.
	 */
	public function findByNames(array $names) {
		return $this->getEntityManager()->createQueryBuilder()
			->select('s')
			->from($this->getEntityName(), 's', 's.name')
			->where('s.name IN (:names)')
			->getQuery()
			->execute(array('names' => $names))
		;
	}

}
