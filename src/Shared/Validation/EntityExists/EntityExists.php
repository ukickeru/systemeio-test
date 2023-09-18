<?php

namespace App\Shared\Validation\EntityExists;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EntityExists extends Constraint
{
    public string $message = 'Entity "{{ entity_class }}" with {{ id_field }} "{{ entity_id }}" doesn\'t exist.';

    /**
     * @param class-string $entityClass
     * @param string|null  $searchField required for scalar value
     */
    #[HasNamedArguments]
    public function __construct(
        public readonly string $entityClass,
        public readonly ?string $searchField = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }
}
