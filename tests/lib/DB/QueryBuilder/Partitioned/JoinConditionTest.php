<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2024 Robin Appelman <robin@icewind.nl>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace lib\DB\QueryBuilder\Partitioned;

use OC\DB\ConnectionAdapter;
use OC\DB\QueryBuilder\Partitioned\JoinCondition;
use OC\DB\QueryBuilder\QueryBuilder;
use OC\SystemConfig;
use OCP\DB\QueryBuilder\IQueryBuilder;
use Psr\Log\LoggerInterface;
use Test\TestCase;

class JoinConditionTest extends TestCase {
	private IQueryBuilder $builder;

	protected function setUp(): void {
		parent::setUp();
		$this->builder = new QueryBuilder(
			$this->createMock(ConnectionAdapter::class),
			$this->createMock(SystemConfig::class),
			$this->createMock(LoggerInterface::class)
		);
	}


	public function testParseCondition(): void {
		$query = $this->builder;
		$param1 = $query->createNamedParameter('files');
		$param2 = $query->createNamedParameter("test");
		$condition = $query->expr()->andX(
			$query->expr()->eq('tagmap.type', 'tag.type'),
			$query->expr()->eq('tagmap.categoryid', 'tag.id'),
			$query->expr()->eq('tag.type', $param1),
			$query->expr()->eq('tag.uid', $param2)
		);
		$parsed = JoinCondition::parse($condition, 'vcategory', 'tag', 'tagmap');
		$this->assertEquals('tagmap.type', $parsed->fromColumn);
		$this->assertEquals('tag.type', $parsed->toColumn);
		$this->assertEquals([], $parsed->fromConditions);
		$this->assertEquals([
			$query->expr()->eq('tag.type', $param1),
			$query->expr()->eq('tag.uid', $param2)
		], $parsed->toConditions);
	}
}
