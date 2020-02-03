<?php


namespace App\Tests;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseTestCase extends KernelTestCase
{
    protected function getEntityManager(): EntityManagerInterface
    {
        return self::bootKernel()->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        self::bootKernel()->getContainer()->get('doctrine')->getManager()->close();
    }

    /**
     * @param array $tableNames
     * @param bool $cascade
     */
    protected function truncateTables($tableNames = array(), $cascade = false): void {
        $connection = $this->getEntityManager()->getConnection();
        try {
            $platform = $connection->getDatabasePlatform();
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
            foreach ($tableNames as $name) {
                $connection->executeUpdate($platform->getTruncateTableSQL($name, $cascade));
            }
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
        } catch(Exception $e) {
            printf ("Could not truncate tables: %s because: %s", implode(', ', $tableNames), $e->getMessage());
        }
    }

    protected function truncateDatabase(): void {
        throw new Exception('Not implemented yet');
    }

    protected function getTableNameForEntity(string $entity): string
    {
        return $this->getEntityManager()->getClassMetadata($entity)->getTableName();
    }

    /**
     * @return ManagerRegistry|MockObject
     */
    protected function getMockedManagerRegistryForClass(): ManagerRegistry
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $managerRegistry->method('getManagerForClass')->willReturn($this->getEntityManager());
        return $managerRegistry;
    }
}
