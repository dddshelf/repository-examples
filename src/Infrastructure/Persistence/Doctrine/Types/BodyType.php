<?php

namespace Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Domain\Model\Body;

class BodyType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param string $value
     * @return Body
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Body($value);
    }

    /**
     * @param Body $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->content();
    }

    public function getName()
    {
        return 'body';
    }
}
