<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    public function collection(): Collection
    {
        return User::with(['governorate', 'center', 'village', 'beneficiaryCategory'])
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'beneficiary_code',
            'husband_name',
            'wife_name',
            'husband_national_id',
            'wife_national_id',
            'age_husband',
            'age_wife',
            'social_status',
            'beneficiary_category',
            'governorate',
            'center',
            'village',
            'address',
            'work_type',
            'nearest_phone',
            'salary',
            'pension',
            'dignity',
            'trade',
            'pillows',
            'other',
            'gross_income',
            'rent',
            'gas',
            'water',
            'electricity',
            'food',
            'study',
            'medical_expenses',
            'association',
            'debt',
            'gross_expenses',
            'standard_living',
            'has_monthly_subvention',
            'monthly_subvention_amount',
            'case_evaluation',
            'status',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->beneficiary_code,
            $user->husband_name,
            $user->wife_name,
            $user->husband_national_id,
            $user->wife_national_id,
            $user->age_husband,
            $user->age_wife,
            $user->social_status,
            optional($user->beneficiaryCategory)->name,
            optional($user->governorate)->name,
            optional($user->center)->name,
            optional($user->village)->name,
            $user->address,
            $user->work_type,
            $user->nearest_phone,
            $user->salary,
            $user->pension,
            $user->dignity,
            $user->trade,
            $user->pillows,
            $user->other,
            $user->gross_income,
            $user->rent,
            $user->gas,
            $user->water,
            $user->electricity,
            $user->food,
            $user->study,
            $user->medical_expenses,
            $user->association,
            $user->debt,
            $user->gross_expenses,
            $user->standard_living,
            $user->has_monthly_subvention ? 1 : 0,
            $user->monthly_subvention_amount,
            $user->Case_evaluation,
            $user->status,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();
                $headerRange = 'A1:' . $highestColumn . '1';
                $dataRange = 'A1:' . $highestColumn . $highestRow;

                $sheet->setRightToLeft(true);
                $sheet->freezePane('A2');

                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9E2F2'],
                        ],
                    ],
                ]);

                $sheet->getStyle($dataRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A2:' . $highestColumn . $highestRow)
                    ->getAlignment()
                    ->setWrapText(true);

                $sheet->getRowDimension(1)->setRowHeight(28);
            },
        ];
    }
}
