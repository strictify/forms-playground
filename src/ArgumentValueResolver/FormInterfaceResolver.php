<?php

declare(strict_types=1);

namespace App\ArgumentValueResolver;

use Generator;
use App\Annotation\Form;
use InvalidArgumentException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use function sprintf;

class FormInterfaceResolver implements ArgumentValueResolverInterface
{
    private FormFactoryInterface $formFactory;
    private ExpressionResolver $expressionResolver;

    public function __construct(FormFactoryInterface $formFactory, ExpressionResolver $expressionResolver)
    {
        $this->formFactory = $formFactory;
        $this->expressionResolver = $expressionResolver;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();
        if (!$type) {
            return false;
        }

        return FormInterface::class === $type;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        /** @var Form|null $annotation */
        $annotation = $request->attributes->get('_form');
        if (!$annotation) {
            throw new InvalidArgumentException(sprintf('You must provide "%s" annotation to resolve typehinted FormInterface.', Form::class));
        }
        $class = $annotation->class;
        $dataParameter = $annotation->data;

        if ($dataParameter && !$request->attributes->has($dataParameter)) {
            throw new InvalidArgumentException(sprintf('Missing parameter "%s" in method signature.', $dataParameter));
        }
        /** @var object|null $data */
        $data = $dataParameter ? $request->attributes->get($dataParameter) : null;

        $options = $this->expressionResolver->evaluateFromRequest($annotation->options, $request);

        $form = $this->formFactory->create($class, $data, $options);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest() && $request->headers->has('refresh-form')) {
            // when form is submitted via ajax, we don't need errors displayed
            $form->clearErrors(true);
            // add blank error so controller would not think form was valid
            $form->addError(new FormError(''));
        }

        yield $form;
    }
}
