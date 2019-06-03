<?php

namespace App\Controller;

use App\Service\EntityManager\Exception\ManageEntityException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     * @required
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function showAction($exception)
    {
        if ($exception instanceof ManageEntityException)
        {
            $errors = $this->translateErrors($exception->getErrors());

            return new JsonResponse(
                [
                    'errors' => $errors
                ],
                $this->getHttpErrorCodeByManageException($exception)
            );
        }
    }

    /**
     * Translate error messages
     *
     * @param array $errors
     * @return array
     */
    private function translateErrors(array $errors)
    {
        $result = [];

        foreach ($errors as $key => $error)
        {
            if (is_array($error))
            {
                $result[$key] = $this->translateErrors($error);
            }
            else
            {
                $result[$key] = $this->translator->trans($error, [], 'validators');
            }
        }

        return $result;
    }

    private function getHttpErrorCodeByManageException(ManageEntityException $exception)
    {
        $result = null;

        switch ($exception->getType())
        {
            case ManageEntityException::READ_ENTITY_ERROR_TYPE:

                $result = Response::HTTP_NOT_FOUND;

                break;

            case ManageEntityException::DELETE_ENTITY_ERROR_TYPE:
            case ManageEntityException::UPDATE_ENTITY_ERROR_TYPE:
            case ManageEntityException::CREATE_ENTITY_ERROR_TYPE:

                $result = Response::HTTP_BAD_REQUEST;

                break;

            default:

                $result = Response::HTTP_BAD_REQUEST;

                break;
        }

        return $result;
    }
}