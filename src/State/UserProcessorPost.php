<?php
// api/src/State/UserPasswordHasher.php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<UserEntity, UserEntity|void>
 */
final readonly class UserProcessorPost implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param UserEntity
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {


        if ($operation instanceof DeleteOperationInterface  && $data instanceof UserEntity) {
            // Supprimer les commandes associées à l'utilisateur
            foreach ($data->getUserOrders() as $order) {
                $data->removeUserOrder($order);
            }
            // Supprimer les notifications associées
            foreach ($data->getUserNotifications() as $notification) {
                $this->entityManager->remove($notification);
            }
            // Enregistrer les modifications dans la base de données
            $this->entityManager->remove($data);
            $this->entityManager->flush();
            return null;
        }

        if ($data instanceof UserEntity) {

            if (!empty($data->getPlainPassword())) {
                $data->setPassword(
                    $this->passwordHasher->hashPassword($data, $data->getPlainPassword())
                );
            }

            $data->eraseCredentials(); // Nettoie plainPassword
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }

    public function hashPassword(UserEntity $user): string
    {

        if ($user == null) {
            throw new \InvalidArgumentException('User cannot be null');
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        );
        return $hashedPassword;
    }
}
