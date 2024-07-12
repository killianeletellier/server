<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCP\Migration\Attributes;

use Attribute;
use JsonSerializable;

class FieldMigrationAttribute extends MigrationAttribute implements JsonSerializable {
	public function __construct(
		string $table,
		private readonly string $field,
		private readonly ?FieldType $type = null,
		MigrationWeight $weight = MigrationWeight::LIGHT,
		string $description = '',
		array $notes = [],
	) {
		parent::__construct($table, $weight, $description, $notes);
	}

	public function getField(): string {
		return $this->field;
	}

	public function getType(): ?FieldType {
		return $this->type;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			[
				'field' => $this->getField(),
				'type' => $this->getType() ?? '',
			]
		);
	}
}
