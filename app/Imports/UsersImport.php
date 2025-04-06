<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Actions\UserActions;
class UsersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   
    protected $createUser;

    public function __construct(UserActions $createUser)
    {
        $this->createUser = $createUser;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->createUser->storeUser((object)[
                'name'       => $row['name'],
                'email'      => $row['email'],
                'password'   => $row['password'],
                'position'   => $row['position'],
                'hire_date'  => $row['hire_date'],
                'is_active'  => $row['is_active'] ?? true,
                'roleId'     => $row['role_id'] ?? null,
                'role'       => [$row['role']] ?? []
            ]);
        }
    }
}
