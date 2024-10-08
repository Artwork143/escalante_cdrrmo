<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barangays = [
            ['name' => 'ALIMANGO', 'punong_barangay' => 'HON. LEO ALEJANDRO U. YAP', 'contact_number' => '09171234567'],
            ['name' => 'BALINTAWAK', 'punong_barangay' => 'HON. MARLYN D. SALILI', 'contact_number' => '09171234568'],
            ['name' => 'BINAGUIOHAN', 'punong_barangay' => 'HON. JOCELYN N. BALICUATRO', 'contact_number' => '09171234569'],
            ['name' => 'BUENAVISTA', 'punong_barangay' => 'HON. FEDERICO M. TANGUAN JR.', 'contact_number' => '09171234569'],
            ['name' => 'CERVANTES', 'punong_barangay' => 'HON. NICOLAS V. BERNAJE JR.', 'contact_number' => '09171234569'],
            ['name' => 'DIAN-AY', 'punong_barangay' => 'HON. JUANITO I. GREGORIO JR.', 'contact_number' => '09171234569'],
            ['name' => 'HACIENDA FE', 'punong_barangay' => 'HON. AIDA P. FRANCISCO', 'contact_number' => '09171234569'],
            ['name' => 'JAPITAN', 'punong_barangay' => 'HON. CARLITO L. VILLARIN', 'contact_number' => '09171234569'],
            ['name' => 'JONOB-JONOB', 'punong_barangay' => 'HON. TACIANA Y. SARATOBIAS', 'contact_number' => '09171234569'],
            ['name' => 'LANGUB', 'punong_barangay' => 'HON. LADISLAO G. PONTEVEDRA JR.', 'contact_number' => '09171234569'],
            ['name' => 'LIBERTAD', 'punong_barangay' => 'HON. NEPTALI P. NARVASA', 'contact_number' => '09171234569'],
            ['name' => 'MABINI', 'punong_barangay' => 'HON. JOHN PAUL D. ESCALA', 'contact_number' => '09171234569'],
            ['name' => 'MAGSAYSAY', 'punong_barangay' => 'HON. ROBERTO G. GRIPO SR.', 'contact_number' => '09171234569'],
            ['name' => 'MALASIBOG', 'punong_barangay' => 'HON ROMULO P. AMACAN', 'contact_number' => '09171234569'],
            ['name' => 'OLD POBLACION', 'punong_barangay' => 'HON. JOAN M. GUINANAO', 'contact_number' => '09171234569'],
            ['name' => 'PAITAN', 'punong_barangay' => 'HON. RAMILO C. VIAJEDOR', 'contact_number' => '09171234569'],
            ['name' => 'PINAPUGASAN', 'punong_barangay' => 'HON. EDGAR D. RAPANA', 'contact_number' => '09171234569'],
            ['name' => 'RIZAL', 'punong_barangay' => 'HON. IVY A. CANTERE', 'contact_number' => '09171234569'],
            ['name' => 'TAMLANG', 'punong_barangay' => 'HON. ANALYN E. ALBERIO', 'contact_number' => '09171234569'],
            ['name' => 'UDTONGAN', 'punong_barangay' => 'HON. RASEL B. CASES', 'contact_number' => '09171234569'],
            ['name' => 'WASHINGTON', 'punong_barangay' => 'HON. RENE L. TIGUELO', 'contact_number' => '09171234569'],
        ];

        DB::table('barangays')->insert($barangays);
    }
}
