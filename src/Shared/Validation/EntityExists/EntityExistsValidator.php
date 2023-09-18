<?php

namespace App\Shared\Validation\EntityExists;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception as ValidatorExceptions;

class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * @param mixed|array<string, mixed> $value supports simple & composite keys
     *
     * @throws ValidatorExceptions\ExceptionInterface
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new ValidatorExceptions\UnexpectedTypeException($constraint, EntityExists::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_scalar($value) && !is_array($value)) {
            throw new ValidatorExceptions\InvalidArgumentException('Entity ID should be represented by scalar or array value');
        }

        if (is_scalar($value) && empty($constraint->searchField)) {
            throw new ValidatorExceptions\InvalidArgumentException('Search field should be specified for scalar value');
        }

        try {
            // Check is entity class registered in ORM
            $this->em->getClassMetadata($constraint->entityClass);
        } catch (\Throwable) {
            throw new ValidatorExceptions\InvalidArgumentException(sprintf('There are no ORM metadata for "%s" class', $constraint->entityClass));
        }

        try {
            $criteria = is_array($value) ? $value : [$constraint->searchField => $value];
            $entity = $this->em->getRepository($constraint->entityClass)->findOneBy($criteria);

            if (!$entity instanceof $constraint->entityClass) {
                $this->buildViolation($value, $constraint);
            }
        } catch (\Throwable $e) {
            $this->logger->error(
                'Error occurred during entity existence validation',
                [
                    'exception_class' => $e::class,
                    'exception_message' => $e->getMessage(),
                    'exception_code' => $e->getCode(),
                    'entity_class' => $constraint->entityClass,
                    'entity_search_field' => $constraint->searchField,
                    'entity_id' => $value,
                ]
            );

            throw new ValidatorExceptions\ValidatorException('Error occurred during entity existence validation');
        }
    }

    private function buildViolation(mixed $value, EntityExists $constraint): void
    {
        $serializedValue = is_scalar($value) ? (string) $value : $this->serializer->serialize($value, 'json');

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ entity_class }}', $constraint->entityClass)
            ->setParameter('{{ id_field }}', $constraint->searchField ?? 'ID')
            ->setParameter('{{ entity_id }}', $serializedValue)
            ->addViolation();
    }
}
