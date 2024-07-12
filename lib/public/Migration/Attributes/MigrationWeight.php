<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCP\Migration\Attributes;

enum MigrationWeight : string {
	/** @since 30.0.0 */
	case LIGHT = 'light'; // migration should be light and quick
	/** @since 30.0.0 */
	case MEDIUM = 'medium'; // migration is estimated to require few minutes
	/** @since 30.0.0 */
	case SETUP_BASED = 'setup-based'; // depends on setup, migration might require some time
	/** @since 30.0.0 */
	case SIZE_RELATED = 'size-related'; // depends on setup, migration might require some time
	/** @since 30.0.0 */
	case HEAVY = 'heavy'; // migration is estimated to be heavy and time consuming
}
