<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @extends AbstractType<User>
 */
class BasicUserType extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstName', TextType::class, [
            'get_value'    => fn(User $user) => $user->getFirstName(),
            'update_value' => fn(string $firstName, User $data) => $data->updateFirstName($firstName),
        ]);

        $builder->add('lastName', TextType::class, [
            'get_value'    => fn(User $user) => $user->getLastName(),
            'update_value' => fn(string $lastName, User $user) => $user->updateLastName($lastName),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'factory' => function (string $firstName, string $lastName) {
                return $this->userRepository->create($firstName, $lastName);
            },
        ]);
    }
}
