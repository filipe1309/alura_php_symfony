<?php

namespace App\Controller;

use App\Entity\Especialidade;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EspecialidadesController extends AbstractController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function nova(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosEmJson = json_decode($dadosRequest);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades", methods={"GET"})
     */
    public function buscarTodas(): Response
    {
        $especialidadeList = $this->repository->findAll();

        return new JsonResponse($especialidadeList);
    }

    /**
     * @Route("/especialidades/{id}", methods={"GET"})
     */
    public function buscarUma(int $id): Response
    {
        $especialidade = $this->repository->find($id);

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     */
    public function atualiza($id, Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosEmJson = json_decode($dadosRequest);

        $especialidade = $this->repository->find($id);
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     */
    public function remove($id): Response
    {
        $especialidade = $this->repository->find($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
      
}
