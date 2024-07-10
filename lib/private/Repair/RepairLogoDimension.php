<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OC\Repair;

use OCA\Theming\ImageManager;
use OCP\IConfig;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;
use OCP\Server;

class RepairLogoDimension implements IRepairStep {
	public function __construct(
		protected IConfig $config,
	) {
	}

	public function getName(): string {
		return 'Cache logo dimension to fix size in emails on Outlook';
	}

	public function run(IOutput $output): void {
		$logoDimensions = $this->config->getAppValue('theming', 'logoDimensions');
		if (preg_match('/^\d+x\d+$/', $logoDimensions)) {
			$output->info('Theming is not used to provide a logo');
			return;
		}

		try {
			/** @var ImageManager $imageManager */
			$imageManager = Server::get(ImageManager::class);
		} catch (\Throwable) {
			$output->info('Theming is disabled');
			return;
		}

		if (!$imageManager->hasImage('logo')) {
			$output->info('Theming is not used to provide a logo');
			return;
		}

		$simpleFile = $imageManager->getImage('logo', false);

		$image = @imagecreatefromstring($simpleFile->getContent());
		if ($image === false) {
			$output->warning('Failed to read dimensions from logo');
			return;
		}

		$dimensions = imagesx($image) . 'x' . imagesy($image);
		$this->config->setAppValue('theming', 'logoDimensions', $dimensions);
		$output->info('Updated logo dimensions: ' . $dimensions);
	}
}
