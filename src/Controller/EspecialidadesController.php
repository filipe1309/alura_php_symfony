<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\Especialidade;
use App\Helper\ExtratorDadosRequest;
use App\Helper\EspecialidadeFactory;
use Psr\Cache\CacheItemPoolInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository,
        EspecialidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $repository, $factory, $extratorDadosRequest, $cache, $logger);
    }

    /**
     * @param Especialidade $entidadeExistente
     * @param Especialidade $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente->setDescricao($entidadeEnviada->getDescricao());
    }

    public function cachePrefix(): string
    {
        return 'especialidade_';
    }

    /**
     * @Route("/especialidades_html")
     */
    public function especialidadesEmHtml()
    {
        return $this->render('especialidades.html.twig', [
            'especialidades' => $this->repository->findAll()
        ]);
    }
}
