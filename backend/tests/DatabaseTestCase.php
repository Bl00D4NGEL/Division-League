<?php


namespace App\Tests;


use Doctrine\ORM\EntityManagerInterface;
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
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function truncateTables($tableNames = array(), $cascade = false) {
        $connection = $this->getEntityManager()->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tableNames as $name) {
            $connection->executeUpdate($platform->getTruncateTableSQL($name,$cascade));
        }
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }


    protected function getTableNameForEntity(string $entity): string
    {
        return $this->getEntityManager()->getClassMetadata($entity)->getTableName();
    }
}
