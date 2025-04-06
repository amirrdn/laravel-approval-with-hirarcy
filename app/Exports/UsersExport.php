<?php

namespace App\Exports;

use App\Models\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Services\UserService;

class UsersExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $roleService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function collection()
    {
        $users = $this->userService->AllUsers();  
        return $users->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles[0]->name ?? '-',
                'position' => $user->position ?? '-',
                'hire_date' => $user->hire_date ?? '-',
                'is_active' => $user->is_active ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Role', 'Position', 'Hire Date', 'Is Active'];
    }
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        return [];
    }
}
