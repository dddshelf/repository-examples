<?php

namespace Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Domain\Model\MessageId;

class MessageIdType extends Type
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param string $value
     * @return MessageId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new MessageId($value);
    }

    /**
     * @param MessageId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    public function getName()
    {
        return 'message_id';
    }
}
