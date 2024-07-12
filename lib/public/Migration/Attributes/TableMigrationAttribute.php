<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCP\Migration\Attributes;

use Attribute;
use JsonSerializable;

class TableMigrationAttribute extends MigrationAttribute implements JsonSerializable {
	public function __construct(
		string $table,
		private readonly array $fields = [],
		MigrationWeight $weight = MigrationWeight::LIGHT,
		string $description = '',
		array $notes = [],
	) {
		parent::__construct($table, $weight, $description, $notes);
	}

	public function getFields(): array {
		return $this->fields;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			[
				'fields' => $this->getFields(),
			]
		);
	}
}
