<?php

declare(strict_types=1);

namespace App\Tests\Application\Mapper\Request;

use App\Application\Mapper\Request\SessionRequestMapper;
use App\Domain\Dto\SignInDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SessionRequestMapperTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testToSignInDtoSuccess(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $inTest = new SessionRequestMapper($validator);
        $signInDto = $inTest->toSignInDto(
            new Request(request: [
                'name' => 'test'
            ])
        );

        $this->assertNotNull($signInDto);
        $this->assertInstanceOf(SignInDto::class, $signInDto);
    }
}