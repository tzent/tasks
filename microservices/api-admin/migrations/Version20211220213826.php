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
class Version20211220213826 extends AbstractMigration
{
    private const ROUTE_NAME  = 'edit-task';
    private const PLUGIN_NAME = 'acl';

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
        return 'Add acl plugin to ' . static::ROUTE_NAME;
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
                sprintf('/routes/%s/plugins', static::ROUTE_NAME),
                [
                    'body' => json_encode([
                        'name'   => static::PLUGIN_NAME,
                        'config' => [
                            'allow' => ['manager', 'employee'],
                            'hide_groups_header' => true
                        ]
                    ])
                ]
            );

            $this->abortIf(
                $response->getStatusCode() !== Response::HTTP_CREATED,
                'ACL plugin not added to ' . static::ROUTE_NAME
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
                'GET', sprintf('/routes/%s/plugins',
                    static::ROUTE_NAME)
            );

            $this->abortIf($response->getStatusCode() !== Response::HTTP_OK,
                'ACL plugin not removed from ' . static::ROUTE_NAME
            );

            foreach ($response->toArray()['data'] as $plugin) {
                if ($plugin['name'] === static::PLUGIN_NAME) {
                    $response = $this->apiGatewayClient->request(
                        'DELETE',
                        sprintf('/routes/%s/plugins/%s', static::ROUTE_NAME, $plugin['id'])
                    );

                    $this->abortIf(
                        $response->getStatusCode() !== Response::HTTP_NO_CONTENT,
                        'ACL plugin not removed from ' . static::ROUTE_NAME
                    );

                    break;
                }
            }
        } catch (ExceptionInterface $exception) {
            $this->abortIf(true, $exception->getMessage());
        }
    }
}
