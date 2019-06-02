<?php

namespace App\Service\EntityManager\Client;

use App\Repository\VideoMaterialRepository;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\Video\Exception\ProvideVideoException;
use App\Service\UserAwareServiceTrait;
use App\Service\Video\IExternalVideoProvider;
use App\Service\Video\VideoLinkManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class VideoMaterialManager
 * @package App\Service\EntityManager\Client
 */
class VideoMaterialManager extends CommonEntityManager
{
    use UserAwareServiceTrait;

    /**
     * @var VideoLinkManager
     */
    private $linkManager;

    /**
     * @var IExternalVideoProvider
     */
    private $externalVideoProvider;

    public function setLinkManager(VideoLinkManager $linkManager)
    {
        $this->linkManager = $linkManager;

        return $this;
    }

    public function setExternalVideoProvider(IExternalVideoProvider $externalVideoProvider)
    {
        $this->externalVideoProvider = $externalVideoProvider;

        return $this;
    }

    /**
     * @param array $data ['url' => 'web url on an external video']
     * @return mixed|void
     * @throws ManageEntityException
     */
    public function create(array $data)
    {
        $url = $data['url'];

        try
        {
            $link = $this->linkManager->create($url);

            $user = $this->getUser();

            // check it in local database
            /** @var VideoMaterialRepository $repository */
            $repository = $this->entityManager->getRepository('App\Entity\VideoMaterial');
            $result = $repository->findOneBy(['owner' => $user, 'externalId' => $link->getExternalId()]);
            if ($result)
            {
                return $result;
            }

            // try to grab from external source
            $result = $this->externalVideoProvider->getMaterial($link);
            $result->setOwner($user);


            $this->entityManager->persist($result);
            $this->entityManager->flush();

            return $result;
        }
        catch (ProvideVideoException $exception)
        {
            throw new ManageEntityException(['url' => $exception->getMessage()], ManageEntityException::CREATE_ENTITY_ERROR_TYPE);
        }
    }

    protected function getCreationForm(): FormInterface
    {
        throw new ManageEntityException(['System Error!'],ManageEntityException::CREATE_ENTITY_ERROR_TYPE);
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new ManageEntityException(['System Error!'],ManageEntityException::UPDATE_ENTITY_ERROR_TYPE);
    }
}
