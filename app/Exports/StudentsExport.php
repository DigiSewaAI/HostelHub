<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Student::with('room')->get();
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->name,
            $student->email,
            $student->phone,
            $student->room ? $student->room->name : 'N/A',
            $student->guardian_name,
            $student->guardian_phone,
            $student->status == 'active' ? 'सक्रिय' : 'निष्क्रिय',
            $student->created_at->format('Y-m-d')
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'नाम',
            'इमेल',
            'फोन',
            'कोठा',
            'अभिभावकको नाम',
            'अभिभावकको फोन',
            'स्थिति',
            'सामेल भएको मिति'
        ];
    }
}