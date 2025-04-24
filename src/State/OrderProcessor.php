<?php
// api/src/State/UserPasswordHasher.php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Order;
use App\Entity\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<Order, Order|void>
 */
final readonly class OrderProcessorPost implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Order
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {


        if ($operation instanceof DeleteOperationInterface  && $data instanceof Order) {
            // Appeler la méthode pour dissocier les médias avant la suppression
            // Enregistrer les modifications dans la base de données
            $this->entityManager->remove($data);
            $this->entityManager->flush();
            return null;
        }


        /** @var Order $order */
        $order = $data;
        /** @var UserEntity  $userInOrder*/
        $userInOrder = $order->getCustomer();
        if ($userInOrder == null) {
            throw new \InvalidArgumentException('User cannot be null');
        }

        /**
         * Hash the password before persisting it in the database
         * check if user dont exist in the database
         * if user exist in the database, we dont hash the password
         */
         /** @var UserEntity  $existingUser*/
        $existingUser = $this->entityManager->getRepository(UserEntity::class)
            ->findOneBy(['email' => $userInOrder->getEmail()]);

        if ($existingUser) {
            // User already exists, do not hash the password
            $userInOrder->setPassword($existingUser->getPassword());
            $order->setCustomer($existingUser);

        } else {
            // User does not exist, hash the password
            // generate a new password aleatoirely password

            $userInOrder->setPlainPassword(bin2hex(random_bytes(10)));
            $hashedPassword = $this->hashPassword($order->getCustomer());
            $userInOrder->setPassword($hashedPassword);
            $userInOrder->eraseCredentials();
            $order->setCustomer($userInOrder);
        }

        $result = $this->processor->process($order, $operation, $uriVariables, $context);
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
