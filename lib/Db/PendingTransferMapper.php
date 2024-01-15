<?php

declare(strict_types=1);

/**
 * 存取 oc_user_transfer_owner 資料表
 */

namespace OCA\AdminTransferownership\Db;

use OCA\Files\Db\TransferOwnership;
use OCA\AdminTransferownership\Db\UserTransferLogMapper as LogMapper;
use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;
use OCP\DB\QueryBuilder\IQueryBuilder;

class PendingTransferMapper extends QBMapper {
	private $logMapper;

	public function __construct(IDBConnection $db, LogMapper $logMapper) {
		parent::__construct($db, 'user_transfer_owner', TransferOwnership::class);
		$this->logMapper = $logMapper;
	}

	public function getAll(): array {
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->getTableName());
		return $this->findEntities($qb);
	}

	/**
	 * 未回覆的移交
	 *
	 * SELECT owner.* 
	 * FROM `oc_user_transfer_owner` owner 
	 * WHERE owner.id
	 *	  NOT IN (SELECT log.origin_id
	 * 		FROM `oc_user_transfer_log` log 
	 * 		WHERE log.origin_id IS NOT null)
	 */
	public function getPending(): array {
		$notInArr = $this->logMapper->getNotNullOriginIds();
		if( count($notInArr) < 1) $notInArr = [0];

		$qb = $this->db->getQueryBuilder();
		$qb->select('owner.*')
			->from($this->getTableName(), 'owner')
			->where(
				$qb->expr()->notIn(
					'owner.id',
					$qb->createNamedParameter($notInArr, IQueryBuilder::PARAM_STR_ARRAY)
				)
			);
		return $this->findEntities($qb);
	}
}
