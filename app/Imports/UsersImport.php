<?php

namespace App\Imports;

use App\Models\BeneficiaryCategory;
use App\Models\Center;
use App\Models\Governorate;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public int $processedRows = 0;
    public int $createdRows = 0;
    public int $updatedRows = 0;
    public int $skippedRows = 0;
    public array $issues = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $sheetRowNumber = $index + 2;

            $row = collect($row)->map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            });

            if ($row->filter()->isEmpty()) {
                continue;
            }

            $this->processedRows++;

            $beneficiaryCode = $this->value($row, ['beneficiary_code', 'كود_المستفيد', 'كود_مستفيد']);
            $husbandName = $this->value($row, ['husband_name', 'اسم_الزوج', 'اسم_المستفيد']);
            $wifeName = $this->value($row, ['wife_name', 'اسم_الزوجة', 'اسم_الزوجةالزوج', 'اسم_الزوج_الزوجة']);
            $husbandNationalId = $this->nullableString($this->value($row, ['husband_national_id', 'الرقم_القومي_للزوج', 'الرقم_القومى_للزوج', 'الرقم_القومي_للمستفيد', 'الرقم_القومى_للمستفيد']));
            $wifeNationalId = $this->nullableString($this->value($row, ['wife_national_id', 'الرقم_القومي_للزوجة', 'الرقم_القومى_للزوجة']));

            if (blank($husbandName) && blank($wifeName)) {
                $this->skipRow($sheetRowNumber, 'تم تخطي الصف لعدم وجود اسم مستفيد/زوج/زوجة.');
                continue;
            }

            $user = User::query()
                ->when($beneficiaryCode, fn ($query) => $query->orWhere('beneficiary_code', $beneficiaryCode))
                ->when($husbandNationalId, fn ($query) => $query->orWhere('husband_national_id', $husbandNationalId))
                ->when($wifeNationalId, fn ($query) => $query->orWhere('wife_national_id', $wifeNationalId))
                ->first();

            if (blank($beneficiaryCode) && blank($husbandNationalId) && blank($wifeNationalId)) {
                $this->addIssue($sheetRowNumber, 'لا يوجد beneficiary_code أو رقم قومي للمطابقة، سيتم إنشاء سجل جديد من الصف كما هو.');
            }

            $governorateValue = $this->value($row, ['governorate', 'المحافظة']);
            $centerValue = $this->value($row, ['center', 'المركز']);
            $villageValue = $this->value($row, ['village', 'القرية']);
            $categoryValue = $this->value($row, ['beneficiary_category', 'تصنيف_المستفيد']);

            $governorate = $this->resolveGovernorate($governorateValue);
            $center = $this->resolveCenter($centerValue, $governorate?->id);
            $village = $this->resolveVillage($villageValue, $center?->id);
            $category = $this->resolveCategory($categoryValue);

            if (!blank($governorateValue) && ! $governorate) {
                $this->addIssue($sheetRowNumber, 'المحافظة [' . $governorateValue . '] غير موجودة.');
            }

            if (!blank($centerValue) && ! $center) {
                $this->addIssue($sheetRowNumber, 'المركز [' . $centerValue . '] غير موجود أو لا يتبع المحافظة المحددة.');
            }

            if (!blank($villageValue) && ! $village) {
                $this->addIssue($sheetRowNumber, 'القرية [' . $villageValue . '] غير موجودة أو لا تتبع المركز المحدد.');
            }

            if (!blank($categoryValue) && ! $category) {
                $this->addIssue($sheetRowNumber, 'تصنيف المستفيد [' . $categoryValue . '] غير موجود.');
            }

            $payload = [
                'beneficiary_code' => $beneficiaryCode ?: null,
                'husband_name' => $husbandName ?: null,
                'wife_name' => $wifeName ?: null,
                'husband_national_id' => $husbandNationalId,
                'wife_national_id' => $wifeNationalId,
                'age_husband' => $this->nullableNumber($this->value($row, ['age_husband', 'عمر_الزوج', 'عمر_الحالة', 'عمر_المستفيد'])),
                'age_wife' => $this->nullableNumber($this->value($row, ['age_wife', 'عمر_الزوجة'])),
                'social_status' => $this->normalizeSocialStatus($this->value($row, ['social_status', 'الحالة_الاجتماعية'])),
                'beneficiary_category_id' => $category?->id,
                'governorate_id' => $governorate?->id,
                'center_id' => $center?->id,
                'village_id' => $village?->id,
                'address' => $this->value($row, ['address', 'العنوان']) ?: null,
                'work_type' => $this->value($row, ['work_type', 'نوع_العمل']) ?: null,
                'nearest_phone' => $this->nullableString($this->value($row, ['nearest_phone', 'اقرب_هاتف', 'أقرب_هاتف'])),
                'salary' => $this->nullableNumber($this->value($row, ['salary', 'المرتب', 'الراتب'])),
                'pension' => $this->nullableNumber($this->value($row, ['pension', 'المعاش'])),
                'dignity' => $this->nullableNumber($this->value($row, ['dignity', 'كرامة'])),
                'trade' => $this->nullableNumber($this->value($row, ['trade', 'ايراد_اخر_1', 'إيراد_آخر_1', 'تجارة'])),
                'pillows' => $this->nullableNumber($this->value($row, ['pillows', 'ايراد_اخر_2', 'إيراد_آخر_2', 'الوسائد'])),
                'other' => $this->nullableNumber($this->value($row, ['other', 'ايراد_اضافي', 'إيراد_إضافي', 'اخرى', 'أخرى'])),
                'gross_income' => $this->nullableNumber($this->value($row, ['gross_income', 'اجمالي_الدخل', 'إجمالي_الدخل'])),
                'rent' => $this->nullableNumber($this->value($row, ['rent', 'الايجار', 'الإيجار'])),
                'gas' => $this->nullableNumber($this->value($row, ['gas', 'الغاز'])),
                'water' => $this->nullableNumber($this->value($row, ['water', 'المياه'])),
                'electricity' => $this->nullableNumber($this->value($row, ['electricity', 'الكهرباء'])),
                'food' => $this->nullableNumber($this->value($row, ['food', 'الطعام'])),
                'study' => $this->nullableNumber($this->value($row, ['study', 'الدراسة'])),
                'medical_expenses' => $this->nullableNumber($this->value($row, ['medical_expenses', 'المصروفات_الطبية'])),
                'association' => $this->nullableNumber($this->value($row, ['association', 'الجمعيات'])),
                'debt' => $this->nullableNumber($this->value($row, ['debt', 'الديون'])),
                'gross_expenses' => $this->nullableNumber($this->value($row, ['gross_expenses', 'اجمالي_المصروفات', 'إجمالي_المصروفات'])),
                'standard_living' => $this->nullableNumber($this->value($row, ['standard_living', 'مستوى_المعيشة'])),
                'has_monthly_subvention' => $this->normalizeBoolean($this->value($row, ['has_monthly_subvention', 'له_اعانة_شهرية', 'له_إعانة_شهرية'])),
                'monthly_subvention_amount' => $this->nullableNumber($this->value($row, ['monthly_subvention_amount', 'مبلغ_الاعانة_الشهرية', 'مبلغ_الإعانة_الشهرية'])),
                'Case_evaluation' => $this->value($row, ['case_evaluation', 'تقييم_الحالة']) ?: null,
                'status' => $this->normalizeStatus($this->value($row, ['status', 'الحالة'])),
            ];

            if ($user) {
                $user->update($payload);
                $this->updatedRows++;
            } else {
                User::create($payload);
                $this->createdRows++;
            }
        }
    }

    private function resolveGovernorate($value): ?Governorate
    {
        if (blank($value)) {
            return null;
        }

        return Governorate::query()
            ->where('id', $value)
            ->orWhere('name', $value)
            ->first();
    }

    private function resolveCenter($value, $governorateId = null): ?Center
    {
        if (blank($value)) {
            return null;
        }

        return Center::query()
            ->when($governorateId, fn ($query) => $query->where('governorate_id', $governorateId))
            ->where(function ($query) use ($value) {
                $query->where('id', $value)->orWhere('name', $value);
            })
            ->first();
    }

    private function resolveVillage($value, $centerId = null): ?Village
    {
        if (blank($value)) {
            return null;
        }

        return Village::query()
            ->when($centerId, fn ($query) => $query->where('center_id', $centerId))
            ->where(function ($query) use ($value) {
                $query->where('id', $value)->orWhere('name', $value);
            })
            ->first();
    }

    private function resolveCategory($value): ?BeneficiaryCategory
    {
        if (blank($value)) {
            return null;
        }

        return BeneficiaryCategory::query()
            ->where('id', $value)
            ->orWhere('name', $value)
            ->first();
    }

    private function normalizeSocialStatus($value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return match ((string) $value) {
            '0', 'أعزب', 'اعزب' => '0',
            '1', 'متزوج' => '1',
            '2', 'مطلق' => '2',
            '3', 'أرمل', 'ارمل' => '3',
            default => null,
        };
    }

    private function normalizeBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(mb_strtolower((string) $value), ['1', 'true', 'yes', 'نعم'], true);
    }

    private function normalizeStatus($value): string
    {
        return match ((string) $value) {
            'new', 'جديد' => 'new',
            'accepted', 'مقبول' => 'accepted',
            'preparing', 'قيد التنفيذ', 'قيد_التنفيذ' => 'preparing',
            'refused', 'مرفوض' => 'refused',
            default => 'new',
        };
    }

    private function nullableNumber($value)
    {
        return $value === '' || $value === null ? null : (float) $value;
    }

    private function nullableString($value): ?string
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return trim((string) $value);
    }

    private function value(Collection $row, array $keys)
    {
        foreach ($keys as $key) {
            if ($row->has($key)) {
                return $row->get($key);
            }
        }

        return null;
    }

    private function skipRow(int $rowNumber, string $message): void
    {
        $this->skippedRows++;
        $this->addIssue($rowNumber, $message);
    }

    private function addIssue(int $rowNumber, string $message): void
    {
        if (count($this->issues) >= 20) {
            return;
        }

        $this->issues[] = 'Row ' . $rowNumber . ': ' . $message;
    }
}
