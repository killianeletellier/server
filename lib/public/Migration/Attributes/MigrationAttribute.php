<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCP\Migration\Attributes;

use JsonSerializable;

class MigrationAttribute implements JsonSerializable {
	public function __construct(
		private readonly string $table,
		private readonly MigrationWeight $weight = MigrationWeight::LIGHT,
		private readonly string $description = '',
		private readonly array $notes = [],
	) {
	}

	public function getTable(): string {
		return $this->table;
	}

	public function getWeight(): MigrationWeight {
		return $this->weight;
	}

	public function getDescription(): string {
		return $this->description;
	}

	public function getNotes(): array {
		return $this->notes;
	}

	public function jsonSerialize(): array {
		return [
			'class' => get_class($this),
			'table' => $this->getTable(),
			'weight' => $this->getWeight()->value,
			'description' => $this->getDescription(),
			'notes' => $this->getNotes(),
		];
	}
}
