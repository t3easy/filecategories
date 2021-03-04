<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)->connect(
    \TYPO3\CMS\Core\Resource\Index\MetaDataRepository::class,
    'recordPostRetrieval',
    \T3easy\Filecategories\Aspect\FileCategoryAspect::class,
    'addCategoriesToMetadata'
);
