<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @extends AbstractTypeExtension<void>
 * @see app.js
 *
 * If Collection has Count constraint with min > 0, add it as an attribute.
 *
 * JS will read that value and create them; no need to press + button.
 */
class MinimumCollection extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        yield CollectionType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var array<Constraint> $constraints */
        $constraints = $options['constraints'];
        $min = $this->findMin($constraints);

        $view->vars['attr']['data-min'] = $min;
    }

    /**
     * @param array<Constraint> $constraints
     */
    private function findMin(array $constraints): int
    {
        foreach ($constraints as $constraint) {
            if ($constraint instanceof Count) {
                $min = (int)$constraint->min;
                if ($min > 0) {
                    return $min;
                }
            }
        }

        return 0;
    }
}
