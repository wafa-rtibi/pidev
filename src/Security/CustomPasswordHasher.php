<?php
namespace App\Security;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plainPassword): string
    {
        // Hash the password with bcrypt
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
        
        // Prepend the desired prefix
        return '$2a$' . substr($hashedPassword, 4);
    }

    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        // Use the built-in function to verify the password
        return password_verify($plainPassword, $hashedPassword);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        // Determine if the hashed password needs to be rehashed
        return false; // Assuming no specific logic for rehashing
    }
}