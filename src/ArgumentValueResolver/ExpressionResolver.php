<?php

declare(strict_types=1);

namespace App\ArgumentValueResolver;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;

class ExpressionResolver
{
    /**
     * @psalm-param array<string, string> $options
     * @psalm-suppress MixedAssignment
     */
    public function evaluateFromRequest(array $options, Request $request): array
    {
        $expressionLanguage = new ExpressionLanguage();

        /** @psalm-var array<array-key, string> $vars */
        $vars = $request->attributes->all();
        $vars['request'] = $request;

        $result = [];
        foreach ($options as $key => $value) {
            $result[$key] = $expressionLanguage->evaluate($value, $vars);
        }

        return $result;
    }
}
