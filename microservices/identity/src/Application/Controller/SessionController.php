<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Mapper\Request\Interfaces\SessionRequestMapperInterface;
use App\Domain\Handler\Interfaces\UserHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/identity/v1')]
class SessionController extends AbstractController
{
    /**
     * @var SessionRequestMapperInterface
     */
    private SessionRequestMapperInterface $sessionRequestMapper;

    /**
     * @var UserHandlerInterface
     */
    private UserHandlerInterface $userHandler;

    /**
     * @param SessionRequestMapperInterface $sessionRequestMapper
     * @param UserHandlerInterface $userHandler
     */
    public function __construct(
        SessionRequestMapperInterface $sessionRequestMapper,
        UserHandlerInterface          $userHandler
    ) {
        $this->sessionRequestMapper = $sessionRequestMapper;
        $this->userHandler = $userHandler;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: '/sign-in',name: 'sign-in', methods: ['POST'])]
    public function signIn(Request $request): JsonResponse
    {
        $signInDto = $this->sessionRequestMapper->toSignInDto($request);
        if ($this->sessionRequestMapper->hasError()) {
            return $this->json($this->sessionRequestMapper->getError(), Response::HTTP_BAD_REQUEST);
        }

        $jwt = $this->userHandler->signIn($signInDto);

        return $this->userHandler->hasError()
            ? $this->json(['error' => $this->userHandler->getError()], Response::HTTP_BAD_REQUEST)
            : $this->json($jwt);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path:'/sign-out', name: 'sign-out', methods: ['POST'])]
    public function signOut(Request $request): JsonResponse
    {
        $this->userHandler->signOut($this->sessionRequestMapper->toSignOutDto($request));

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}