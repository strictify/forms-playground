<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class DisableClientSideValidation extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['novalidate'] = true;
        $view->vars['attr']['autocomplete'] = 'off';
    }

    public static function getExtendedTypes(): iterable
    {
        yield FormType::class;
    }
}
