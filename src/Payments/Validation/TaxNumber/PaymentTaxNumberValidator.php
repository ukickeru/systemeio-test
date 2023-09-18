<?php

namespace App\Payments\Validation\TaxNumber;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception as ValidatorExceptions;

class PaymentTaxNumberValidator extends ConstraintValidator
{
    public function __construct(private readonly TaxNumberChainValidatorAdapter $validator)
    {
    }

    /**
     * @throws ValidatorExceptions\ExceptionInterface
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof PaymentTaxNumber) {
            throw new ValidatorExceptions\UnexpectedTypeException($constraint, PaymentTaxNumber::class);
        }

        if (!is_string($value) || empty($value)) {
            throw new ValidatorExceptions\InvalidArgumentException('Tax number should be represented by non-empty string');
        }

        if ($this->validator->isValid($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ tax_number }}', $value)
            ->addViolation();
    }
}
