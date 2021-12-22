<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
* Auto-generated Migration: Please modify to your needs!
*/
class Version20211220211822 extends AbstractMigration
{
    private const SERVICE_NAME = 'tasks';

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $apiGatewayClient;

    /**
     * @param Connection $connection
     * @param LoggerInterface $logger
     * @param HttpClientInterface $apiGatewayClient
     */
    public function __construct(Connection $connection, LoggerInterface $logger, HttpClientInterface $apiGatewayClient)
    {
        $this->apiGatewayClient = $apiGatewayClient;

        parent::__construct($connection, $logger);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Add tasks service';
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        try {
            $response = $this->apiGatewayClient->request(
                'POST',
                '/services',
                [
                    'body' => json_encode([
                        'name'     => static::SERVICE_NAME,
                        'protocol' => 'https',
                        'host'     => 'tasks-gateway',
                        'port'     => 443,
                        'path'     => '/tasks/v1/'
                    ])
                ]
            );

            $this->abortIf(
                $response->getStatusCode() !== Response::HTTP_CREATED,
                static::SERVICE_NAME . ' service was not added to Kong Admin'
            );
        } catch (ExceptionInterface $exception) {
            $this->abortIf(true, $exception->getMessage());
        }
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        try {
            $response = $this->apiGatewayClient->request(
                'DELETE',
                sprintf('/services/%s', static::SERVICE_NAME)
            );

            $this->abortIf($response->getStatusCode() !== Response::HTTP_NO_CONTENT,
                static::SERVICE_NAME . ' service not removed from Kong Admin services'
            );
        } catch (ExceptionInterface $exception) {
            $this->abortIf(true, $exception->getMessage());
        }
    }
}
