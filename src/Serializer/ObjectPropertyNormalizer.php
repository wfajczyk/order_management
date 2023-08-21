<?php


declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

/**
 * @phpstan-ignore-next-line
 */
class ObjectPropertyNormalizer extends PropertyNormalizer
{
    /**
     * @var string[]|null[]
     */
    private array $discriminatorCache = [];
    private \ReflectionMethod $constructor;

    /**
     * @param mixed[] $defaultContext
     */
    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null,
        ClassDiscriminatorResolverInterface $classDiscriminatorResolver = null,
        callable $objectClassResolver = null,
        array $defaultContext = []
    ){
        parent::__construct(
            $classMetadataFactory,
            $nameConverter,
            $propertyTypeExtractor,
            $classDiscriminatorResolver,
            $objectClassResolver,
            $defaultContext,
        );

        $this->constructor = new \ReflectionMethod($this, 'getConstructor');
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return is_object($data) && !$data instanceof \Traversable;
    }

    public function supportsDenormalization($data, $type, $format = null):bool
    {
        return class_exists($type)
            || (interface_exists($type, false) && $this->classDiscriminatorResolver->getMappingForClass($type));
    }

    /**
     * @phpstan-ignore-next-line
     */
    protected function getConstructor(
        array &$data,
        $class,
        array &$context,
        \ReflectionClass $reflectionClass,
        $allowedAttributes
    ): ?\ReflectionMethod {
        return $this->constructor;
    }

    /**
     * @phpstan-ignore-next-line
     */
    protected function getAttributeValue($object, $attribute, $format = null, array $context = []):mixed
    {
        /** @var string $cacheKey */
        $cacheKey = $object::class;
        if (!array_key_exists($cacheKey, $this->discriminatorCache)) {
            $this->discriminatorCache[$cacheKey] = null;

            $mapping = $this->classDiscriminatorResolver->getMappingForMappedObject($object);
            $this->discriminatorCache[$cacheKey] = null === $mapping ? null : $mapping->getTypeProperty();
        }

        return $attribute === $this->discriminatorCache[$cacheKey] ?

            $this->classDiscriminatorResolver->getTypeForMappedObject($object) :
            parent::getAttributeValue($object, $attribute, $format, $context);
    }

    /**
     * {@inheritDoc}
     * @return object
     * @phpstan-ignore-next-line
     */
    protected function instantiateObject(
        array &$data,
        string $class,
        array &$context,
        \ReflectionClass $reflectionClass,
        array|bool $allowedAttributes,
        string $format = null
    ) {
        if (
            $this->classDiscriminatorResolver &&
            $mapping = $this->classDiscriminatorResolver->getMappingForClass($class)
        ) {
            if (!isset($data[$mapping->getTypeProperty()])) {
                // fallback empty type
                $data[$mapping->getTypeProperty()] = '';
            }
        }

        return parent::instantiateObject($data, $class, $context, $reflectionClass, $allowedAttributes, $format);
    }
}
