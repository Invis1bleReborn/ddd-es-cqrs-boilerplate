<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

/**
 * Class AccessAttribute
 *
 * @package IdentityAccess\Ui\Access
 */
final class AccessAttribute
{
    public string $attribute;

    public ?string $field;

    public function __construct(string $attribute, ?string $field = null)
    {
        $this->attribute = $attribute;
        $this->field = $field;
    }

}
