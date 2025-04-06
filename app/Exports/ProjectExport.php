<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Services\ProjectService;
use App\Services\UserService;

class ProjectExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    public function collection()
    {
        $projects = $this->projectService->getAll();
        
        return $projects->map(function ($project) {
            return [
                'Name' => $project->name,
                'Status' => match($project->status) {
                    1 => 'Active',
                    3 => 'Completed',
                    4 => 'Rejected',
                    default => 'Not Active',
                },
                'Start Date' => $project->start_date,
                'End Date' => $project->end_date,
                'Assigned To' => $project->projectUser?->assigned->name,
                'Creator' => $project->projectUser?->creator->name,
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'Status', 'Start Date', 'End Date', 'Assigned To', 'Creator'];
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
