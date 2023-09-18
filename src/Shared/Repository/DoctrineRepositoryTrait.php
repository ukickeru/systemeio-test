<?php

namespace App\Shared\Repository;

/**
 * Sample repository trait.
 */
trait DoctrineRepositoryTrait
{
    /**
     * @throws \RuntimeException if entity not found
     */
    public function findById(mixed $id): object
    {
        $entity = $this->find($id);

        if (!($entity instanceof ($this->getEntityName()))) {
            $idTemplate = is_scalar($id) ? sprintf(' with ID "%s"', $id) : '';

            throw new \RuntimeException(sprintf('"%s"%s not found!', $this->getEntityName(), $idTemplate));
        }

        return $entity;
    }

    /**
     * @throws \InvalidArgumentException if entity class not match for declared
     */
    public function save(object $entity, bool $flush = false): object
    {
        if (!($entity instanceof ($this->getEntityName()))) {
            throw new \InvalidArgumentException(sprintf('%s works only with "%s" objects!', __METHOD__, $this->getEntityName()));
        }

        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    /**
     * @throws \InvalidArgumentException if entity class not match for declared
     */
    public function remove(object $entity, bool $flush = false): void
    {
        if (!($entity instanceof ($this->getEntityName()))) {
            throw new \InvalidArgumentException(sprintf('%s works only with "%s" objects!', __METHOD__, $this->getEntityName()));
        }

        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
