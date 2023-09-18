<?php

namespace App\Tests\Shared\Unit\Validation;

use App\Shared\Validation\EntityExists\EntityExists;
use App\Shared\Validation\EntityExists\EntityExistsValidator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\ValidatorException;

class EntityExistsValidatorTest extends TestCase
{
    private const EXAMPLE_ENTITY_CLASS = \ArrayObject::class;

    private ServiceEntityRepository&MockObject $entityRepositoryMock;
    private EntityManagerInterface&MockObject $entityManagerMock;
    private LoggerInterface&MockObject $loggerMock;
    private SerializerInterface&MockObject $serializerMock;
    private ExecutionContextInterface&MockObject $executionContextMock;
    private EntityExistsValidator $validator;

    protected function setUp(): void
    {
        $this->entityRepositoryMock = $this->createMock(ServiceEntityRepository::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);
        $this->executionContextMock = $this->createMock(ExecutionContextInterface::class);

        $this->validator = new EntityExistsValidator(
            $this->entityManagerMock,
            $this->loggerMock,
            $this->serializerMock,
        );

        $this->validator->initialize($this->executionContextMock);
    }

    public function testSuccessfullyValidationWithScalarValue(): void
    {
        $value = 123;
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS, 'my_field');

        $this->entityManagerMock
            ->expects($this->once())
            ->method('getClassMetadata');
        $this->entityManagerMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->entityRepositoryMock);
        $this->entityRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturnCallback(
                function (array $criteria, ...$args) {
                    $this->assertArrayHasKey('my_field', $criteria);
                    $this->assertEquals(123, $criteria['my_field']);

                    return new \ArrayObject();
                }
            );
        $this->loggerMock->expects($this->never())->method('error');
        $this->serializerMock->expects($this->never())->method('serialize');

        $this->validator->validate($value, $constraint);
    }

    public function testSuccessfullyValidationWithArrayValue(): void
    {
        $value = ['field_1' => 123, 'field_2' => 456];
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('getClassMetadata');
        $this->entityManagerMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->entityRepositoryMock);
        $this->entityRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturnCallback(
                function (array $criteria, ...$args) {
                    $this->assertArrayHasKey('field_1', $criteria);
                    $this->assertArrayHasKey('field_2', $criteria);
                    $this->assertEquals(123, $criteria['field_1']);
                    $this->assertEquals(456, $criteria['field_2']);

                    return new \ArrayObject();
                }
            );
        $this->loggerMock->expects($this->never())->method('error');
        $this->serializerMock->expects($this->never())->method('serialize');

        $this->validator->validate($value, $constraint);
    }

    public function testVoidReturnOnEmptyArgument(): void
    {
        $this->expectNotToPerformAssertions();

        $this->validator->validate(null, new EntityExists(self::EXAMPLE_ENTITY_CLASS));
        $this->validator->validate([], new EntityExists(self::EXAMPLE_ENTITY_CLASS));
    }

    public function testInvalidArgumentTypeException(): void
    {
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS);
        $value = new \SplObjectStorage();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Entity ID should be represented by scalar or array value');

        $this->validator->validate($value, $constraint);
    }

    public function testArgumentNotMatchesWithConstraintException(): void
    {
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS);
        $value = 'some_scalar_that_needs_field_definition_in_constraint';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Search field should be specified for scalar value');

        $this->validator->validate($value, $constraint);
    }

    public function testEntityClassMetadataNotFoundException(): void
    {
        $value = 123;
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS, 'my_field');

        $this->entityManagerMock
            ->expects($this->once())
            ->method('getClassMetadata')
            ->willThrowException(new \RuntimeException());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There are no ORM metadata for "ArrayObject" class');

        $this->validator->validate($value, $constraint);
    }

    public function testEntityNotFoundViolationWithScalarValue(): void
    {
        $value = 123;
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS, 'my_field');

        $this->entityManagerMock
            ->expects($this->once())
            ->method('getClassMetadata');
        $this->entityManagerMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->entityRepositoryMock);
        $this->entityRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $this->serializerMock->expects($this->never())->method('serialize');

        $this->validator->validate($value, $constraint);
    }

    public function testEntityNotFoundViolationWithArrayValue(): void
    {
        $value = ['field_1' => 123, 'field_2' => 456];
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('getClassMetadata');
        $this->entityManagerMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->entityRepositoryMock);
        $this->entityRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $this->serializerMock->expects($this->once())->method('serialize');

        $this->validator->validate($value, $constraint);
    }

    public function testInfrastructureException(): void
    {
        $value = ['field_1' => 123, 'field_2' => 456];
        $constraint = new EntityExists(self::EXAMPLE_ENTITY_CLASS);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('getClassMetadata');
        $this->entityManagerMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->entityRepositoryMock);
        $this->entityRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->willThrowException(new \RuntimeException());
        $this->loggerMock->expects($this->once())->method('error');
        $this->serializerMock->expects($this->never())->method('serialize');

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('Error occurred during entity existence validation');

        $this->validator->validate($value, $constraint);
    }
}
