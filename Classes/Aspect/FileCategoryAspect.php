<?php
declare(strict_types = 1);
namespace T3easy\Filecategories\Aspect;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\Event\EnrichFileMetaDataEvent;

final class FileCategoryAspect
{
    /**
     * Add categories to file metadata
     *
     * @param EnrichFileMetaDataEvent $event
     */
    public function addCategoriesToMetadata(EnrichFileMetaDataEvent $event): void
    {
        // Should only be in Frontend, but not in eID context
        if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE) || isset($_REQUEST['eID'])) {
            return;
        }
        $data = $event->getRecord();
        if ($data['categories'] > 0) {
            $categories = $this->getCategories($data['uid']);
            $this->languageAndWorkspaceOverlay($categories);
            $data['categories'] = $categories;
        } else {
            $data['categories'] = [];
        }
        $event->setRecord($data);
    }

    protected function getCategories(int $metaDataUid): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');

        return $queryBuilder
            ->select('*')
            ->from('sys_category')
            ->join('sys_category',
                'sys_category_record_mm',
                'sys_category_record_mm',
                $queryBuilder->expr()->eq(
                    'sys_category_record_mm.uid_local',
                    'sys_category.uid'
                )
            )
            ->where(
                $queryBuilder->expr()->eq(
                    'sys_category_record_mm.tablenames',
                    $queryBuilder->createNamedParameter('sys_file_metadata', \PDO::PARAM_STR)
                ),
                $queryBuilder->expr()->eq(
                    'sys_category_record_mm.fieldname',
                    $queryBuilder->createNamedParameter('categories', \PDO::PARAM_STR)
                ),
                $queryBuilder->expr()->eq(
                    'sys_category_record_mm.uid_foreign',
                    $queryBuilder->createNamedParameter($metaDataUid, \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll();
    }

    /**
     * Do translation and workspace overlay
     *
     * @param array $categories
     */
    public function languageAndWorkspaceOverlay(array &$categories): void
    {
        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        foreach ($categories as $key => $category) {
            $pageRepository->versionOL('sys_category', $category);
            $overlaidCategory = $pageRepository->getLanguageOverlay(
                'sys_category',
                $category
            );
            if ($overlaidCategory !== null){
                $categories[$key] = $overlaidCategory;
            }
        }
    }
}
