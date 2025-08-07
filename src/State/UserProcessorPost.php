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
            // Appeler la méthode pour dissocier les médias avant la suppression
            // Enregistrer les modifications dans la base de données
            $this->entityManager->remove($data);
            $this->entityManager->flush();
            return null;
        }

        if (!$data->getPlainPassword() || $data->getPassword() === $data->getPlainPassword()) {
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        /**
         * Hash the password before persisting it in the database
         */
        $hashedPassword = $this->hashPassword($data);
        $data->setPassword($hashedPassword);
        $data->eraseCredentials();
        $result = $this->processor->process($data, $operation, $uriVariables, $context);
        return $result;
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
