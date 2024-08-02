<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAllUsers(): Collection;

    public function getUserById(int $id);

    public function createUser(array $data);

    public function updateUser(int $id, array $data);

    public function deleteUser(int $id);
}