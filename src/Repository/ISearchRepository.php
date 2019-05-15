<?php

namespace App\Repository;

use Doctrine\ORM\Query;

interface ISearchRepository
{
    function getSearchQuery(array $criteria): Query;
}