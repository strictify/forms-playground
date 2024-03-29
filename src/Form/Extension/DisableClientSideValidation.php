<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * @extends AbstractTypeExtension<void>
 */
class DisableClientSideValidation extends AbstractTypeExtension
{
    /**
     * @psalm-suppress MixedArrayAssignment
     * @psalm-suppress MixedPropertyTypeCoercion
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['novalidate'] = true;
        $view->vars['attr']['autocomplete'] = 'off';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'show_factory_error' => false,
            'required' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        yield FormType::class;
    }
}
