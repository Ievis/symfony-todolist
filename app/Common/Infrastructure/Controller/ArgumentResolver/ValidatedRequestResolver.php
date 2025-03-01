<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Controller\ArgumentResolver;

use App\Common\Infrastructure\Attribute\ValidatedRequest;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ValidatedRequestResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface  $validator,
    )
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (null === $argument->getType()) {
            return false;
        }

        $reflection = new \ReflectionClass($argument->getType());
        return !empty($reflection->getAttributes(ValidatedRequest::class));
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $class = $argument->getType();

        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                $class,
                'json'
            );
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Некорректный формат JSON: ' . $e->getMessage());
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $propertyPath = $error->getPropertyPath();
                $errorMessages[$propertyPath] = $error->getMessage();
            }

            throw new BadRequestHttpException(json_encode(['errors' => $errorMessages]));
        }

        yield $dto;
    }
}