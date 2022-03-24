<?php

declare(strict_types=1);

namespace T3easy\Filecategories\Tests\Functional\Aspect;

use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FileCategoryAspectTest extends FunctionalTestCase
{
    protected $initializeDatabase = false;

    protected $testExtensionsToLoad = ['typo3conf/ext/filecategories'];

    /**
     * @test
     */
    public function dummyTest(): void
    {
        self::assertEquals(1, 1);
    }
}
