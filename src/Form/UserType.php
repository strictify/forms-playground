<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @extends AbstractType<User>
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstName', TextType::class, [
            'get_value'    => fn(User $user) => $user->getFirstName(),
            'update_value' => fn(string $firstName, User $user) => $user->updateFirstName($firstName),
            'constraints' => [
                new NotNull(['message' => 'You cannot leave this field empty.']),
            ]
        ]);

        $builder->add('lastName', TextType::class, [
            'get_value'    => fn(User $user) => $user->getLastName(),
            'update_value' => fn(string $lastName, User $user) => $user->updateLastName($lastName),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'show_factory_error' => false,
            'factory'            => fn(string $firstName, string $lastName) => new User($firstName, $lastName),
        ]);
    }
}
