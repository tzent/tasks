<?php declare(strict_types = 1);

namespace <namespace>;

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
class <className> extends AbstractMigration
{
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
        return '';
    }

    /**
    * @param Schema $schema
    *
    * @return void
    */
    public function up(Schema $schema): void
    {
        try {
            $response = $this->apiGatewayClient->request();
            $this->abortIf(
                $response->getStatusCode() !== Response::HTTP_CREATED,
                ''
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
            $response = $this->apiGatewayClient->request();

            $this->abortIf(
                $response->getStatusCode() !== Response::HTTP_NO_CONTENT,
                ''
            );
        } catch (ExceptionInterface $exception) {
            $this->abortIf(true, $exception->getMessage());
        }
    }
}
