<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hostel;
use App\Models\Student;

class ValidateOrganizationData extends Command
{
    protected $signature = 'validate:organization';
    protected $description = 'Check and fix organization_id mismatches';

    public function handle()
    {
        // होस्टल जाँच
        $hostels = Hostel::all();
        foreach ($hostels as $hostel) {
            if (!$hostel->organization) {
                $this->error("Hostel ID {$hostel->id} को organization छैन!");
                // यहाँ आफैं मिलाउन चाहनुहुन्छ भने observer को logic प्रयोग गर्न सक्नुहुन्छ
            }
        }

        // स्टुडेन्ट जाँच
        $students = Student::with('hostel')->get();
        foreach ($students as $student) {
            if ($student->hostel && $student->organization_id != $student->hostel->organization_id) {
                $student->organization_id = $student->hostel->organization_id;
                $student->save();
                $this->info("Student ID {$student->id} सच्याइयो");
            }
        }

        $this->info('भ्यालिडेसन सकियो');
    }
}
