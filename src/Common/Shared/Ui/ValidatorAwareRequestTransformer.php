<?php

declare(strict_types=1);

namespace Common\Shared\Ui;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;

/**
 * Class ValidatorAwareRequestTransformer
 *
 * @package Common\Shared\Ui
 */
abstract class ValidatorAwareRequestTransformer
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param RequestInterface $request
     * @param array            $context
     *
     * @throws ValidationException
     */
    protected function validate(RequestInterface $request, array $context = []): void
    {
        $this->validator->validate($request, $context);
    }

}
