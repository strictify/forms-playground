<?php

declare(strict_types=1);

namespace App\Annotation;

use App\ArgumentValueResolver\FormInterfaceResolver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @Annotation
 *
 * @see FormInterfaceResolver::resolve()
 */
class Form implements ConfigurationInterface
{
    /**
     * This will be your form class, @see FormTypeInterface
     *
     * @Required
     * @psalm-var class-string<FormTypeInterface>
     */
    public string $class;

    public ?string $data = null;

    /**
     * @psalm-var array<string, string>
     */
    public array $options = [];

    public function getAliasName(): string
    {
        return 'form';
    }

    public function allowArray(): bool
    {
        return false;
    }
}
