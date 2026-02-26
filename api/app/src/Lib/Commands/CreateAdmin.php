<?php

namespace App\Lib\Commands;

use App\Entities\User;
use App\Repositories\UserRepository;

class CreateAdmin extends AbstractCommand {
    public function execute(): void {
        $userRepository = new UserRepository();
        
        $email = 'admin@cms.local';
        $existingUser = $userRepository->findByEmail($email);
        
        if ($existingUser) {
            echo "L'utilisateur admin existe déjà (admin@cms.local)" . PHP_EOL;
            return;
        }

        $user = new User();
        $user->email = $email;
        $user->firstname = 'Admin';
        $user->lastname = 'CMS';
        $user->role = 'admin';
        $user->hashPassword('admin123');
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');

        $id = $userRepository->save($user);
        
        echo "Utilisateur admin créé avec succès (ID: $id)" . PHP_EOL;
        echo "Email: admin@cms.local" . PHP_EOL;
        echo "Password: admin123" . PHP_EOL;
    }

    public function undo(): void {}
    public function redo(): void {}
}
