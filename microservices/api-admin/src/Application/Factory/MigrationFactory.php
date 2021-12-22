<?php

declare(strict_types=1);

namespace App\Application\Factory;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory as MigrationFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MigrationFactory implements MigrationFactoryInterface
{
    /** @var Connection */
    private Connection $connection;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $apiGatewayClient;

    /**
     * MigrationFactory constructor.
     * @param Connection          $connection
     * @param LoggerInterface     $logger
     * @param HttpClientInterface $apiGatewayClient
     */
    public function __construct(Connection $connection, LoggerInterface $logger, HttpClientInterface $apiGatewayClient)
    {
        $this->connection       = $connection;
        $this->logger           = $logger;
        $this->apiGatewayClient = $apiGatewayClient;
    }

    /**
     * @param  string            $migrationClassName
     * @return AbstractMigration
     */
    public function createVersion(string $migrationClassName): AbstractMigration
    {
        return new $migrationClassName(
            $this->connection,
            $this->logger,
            $this->apiGatewayClient
        );
    }
}
