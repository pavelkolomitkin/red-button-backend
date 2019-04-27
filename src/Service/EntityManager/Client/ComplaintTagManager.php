<?php

namespace App\Service\EntityManager\Client;

use App\Entity\ComplaintTag;
use App\Service\EntityManager\CommonEntityManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class ComplaintTagManager
 * @package App\Service\EntityManager\Client
 */
class ComplaintTagManager extends CommonEntityManager
{
    public function processTags(array $tags)
    {
        $tags = $this->cleanTags($tags);

        $result = $this->getExistingTags($tags);
        $existingTagsArray = array_map(function (ComplaintTag $tag){
            return $tag->getTitle();
        }, $result);

        $newTags = array_diff($tags, $existingTagsArray);

        foreach ($newTags as $newTag)
        {
            $tag = new ComplaintTag();
            $tag->setTitle($newTag);

            $this->entityManager->persist($tag);
            $this->entityManager->flush($tag);

            $result[] = $tag;
        }

        return $result;
    }

    protected function getCreationForm(): FormInterface
    {
        throw new \Exception('Cannot use it through a form');
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new \Exception('Cannot use it through a form');
    }

    private function cleanTags(array $tags)
    {
        $result = [];

        foreach ($tags as $tag)
        {
            $tag = trim($tag);
            if (!empty($tag))
            {
                $result[] = $tag;
            }
        }

        $result = array_unique($result);

        return $result;
    }

    private function getExistingTags(array $tags)
    {
        return $this->entityManager->getRepository('App\Entity\ComplaintTag')
            ->createQueryBuilder('complaint_tag')
            ->where('complaint_tag.title in (:tags)')
            ->setParameter('tags', $tags)
            ->getQuery()
            ->getResult();
    }
}
