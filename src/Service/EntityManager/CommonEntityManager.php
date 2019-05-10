<?php

namespace App\Service\EntityManager;

use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\FormErrorExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

abstract class CommonEntityManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FormErrorExtractor
     */
    protected $errorExtractor;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormErrorExtractor $errorExtractor,
        FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->errorExtractor = $errorExtractor;
        $this->formFactory = $formFactory;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ManageEntityException
     * @throws \Exception
     */
    public function create(array $data)
    {

        $this->entityManager->beginTransaction();

        try
        {
            $form = $this->getCreationForm();

            $entity = $form->getData();

            $this->preValidate($entity, $data);

            $form->submit($data);
            if (!$form->isValid())
            {
                throw new ManageEntityException(
                    $this->errorExtractor->extract($form),
                    ManageEntityException::CREATE_ENTITY_ERROR_TYPE
                );
            }

            $entity = $form->getData();

            $this->postValidate($entity, $data);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return $entity;
        }
        catch (\Exception $exception)
        {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    /**
     * @param $entity
     * @param array $data
     * @return mixed
     * @throws ManageEntityException
     * @throws \Exception
     */
    public function update($entity, array $data)
    {
        $this->entityManager->beginTransaction();

        try
        {
            $form = $this->getUpdatingForm();

            $form->setData($entity);

            $this->preValidate($entity, $data);

            $form->submit($data);
            if (!$form->isValid())
            {
                throw new ManageEntityException(
                    $this->errorExtractor->extract($form),
                    ManageEntityException::UPDATE_ENTITY_ERROR_TYPE
                );
            }

            $entity = $form->getData();

            $this->postValidate($entity, $data);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return $entity;

        }
        catch (\Exception $exception)
        {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    /**
     * @param $entity
     * @throws ManageEntityException
     */
    public function remove($entity)
    {
        try
        {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
        catch (\Exception $exception)
        {
            throw new ManageEntityException(['Cannot delete item'], ManageEntityException::DELETE_ENTITY_ERROR_TYPE);
        }
    }

    protected function preValidate($entity, $data)
    {

    }

    protected function postValidate($entity, $data)
    {

    }

    abstract protected function getCreationForm(): FormInterface;

    abstract protected function getUpdatingForm(): FormInterface;
}
