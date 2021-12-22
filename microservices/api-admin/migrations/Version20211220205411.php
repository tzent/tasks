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
class Version20211220205411 extends AbstractMigration
{
    private const SERVICE_NAME = 'identity';
    private const ROUTE_NAME   = 'sign-in';

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
        return 'Add sign-in route';
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
                sprintf('/services/%s/routes', static::SERVICE_NAME),
                [
                    'body' => json_encode([
                        'name'          => static::ROUTE_NAME,
                        'protocols'     => ['https'],
                        'methods'       => ['POST'],
                        'paths'         => ['/sign-in'],
                        'strip_path'    => false,
                        'path_handling' => 'v1'
                    ])
                ]
            );
            $this->abortIf(
                $response->getStatusCode() !== Response::HTTP_CREATED,
                static::ROUTE_NAME . ' route not added to Kong Admin routes'
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
                sprintf('/services/%s/routes/%s', static::SERVICE_NAME, static::ROUTE_NAME)
            );

            $this->abortIf(
                $response->getStatusCode() !== Response::HTTP_NO_CONTENT,
                static::ROUTE_NAME . ' route not removed from Kong Admin routes'
            );
        } catch (ExceptionInterface $exception) {
            $this->abortIf(true, $exception->getMessage());
        }
    }
}
