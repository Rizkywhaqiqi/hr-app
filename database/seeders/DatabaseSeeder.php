<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data master hanya diisi jika tabel masih kosong,
        // supaya data yang diubah HR tidak tertimpa saat redeploy.
        if (Brand::query()->count() === 0) {
            foreach (['jojo juice', 'jly', 'guulak'] as $name) {
                Brand::create(compact('name'));
            }
        }

        if (Division::query()->count() === 0) {
            foreach (['Front of House', 'Back of House', 'Barista', 'Kitchen', 'Cashier', 'Supervisor', 'Store Manager', 'Head Office'] as $name) {
                Division::create(compact('name'));
            }
        }

        if (Location::query()->count() === 0) {
            foreach ([
                'Jalan Merdeka, Pontianak',
                'Jalan Ahmad Yani, Pontianak',
                'Jalan Gajah Mada, Pontianak',
                'Jalan Diponegoro, Singkawang',
                'Jalan Thamrin, Singkawang',
            ] as $name) {
                Location::create(compact('name'));
            }
        }

        if (Position::query()->count() === 0) {
            foreach ([
                'Barista', 'Cashier', 'Store Manager', 'Kitchen Staff', 'Supervisor',
                'Pizza Maker', 'Cook', 'Sushi Chef', 'Hostess', 'HR Manager',
                'Finance Staff', 'Marketing Staff', 'IT Support',
            ] as $name) {
                Position::create(compact('name'));
            }
        }

        // Akun admin default (hanya jika belum ada user). Password otomatis di-hash oleh model.
        if (User::query()->count() === 0) {
            User::create([
                'name' => 'Admin HR',
                'email' => 'admin@jojo.test',
                'password' => 'password',
            ]);
        }

        // Karyawan dummy hanya diisi jika tabel karyawan kosong.
        if (Employee::query()->count() === 0) {
            $this->seedEmployees();
        }
    }

    /**
     * Data karyawan dummy (nama & brand fiktif), terhubung ke data master.
     */
    private function seedEmployees(): void
    {
        foreach ($this->dummy() as $row) {
            Employee::create([
                'employee_id' => $row['employee_id'],
                'full_name' => $row['full_name'],
                'brand_id' => Brand::where('name', $row['brand'])->value('id'),
                'position_id' => Position::where('name', $row['position'])->value('id'),
                'division_id' => Division::where('name', $row['division'])->value('id'),
                'location_id' => Location::where('name', $row['work_location'])->value('id'),
                'joined_date' => $row['joined_date'],
                'is_active' => $row['is_active'],
                'deactivated_at' => $row['is_active'] ? null : '2024-08-15 10:00:00',
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function dummy(): array
    {
        return [
            ['employee_id' => 'KS-001', 'full_name' => 'Andi Pratama', 'brand' => 'jojo juice', 'position' => 'Barista', 'division' => 'Barista', 'work_location' => 'Jalan Merdeka, Pontianak', 'joined_date' => '2021-03-01', 'is_active' => true],
            ['employee_id' => 'KS-002', 'full_name' => 'Budi Santoso', 'brand' => 'jojo juice', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Ahmad Yani, Pontianak', 'joined_date' => '2021-05-14', 'is_active' => true],
            ['employee_id' => 'KS-003', 'full_name' => 'Citra Dewi', 'brand' => 'jojo juice', 'position' => 'Store Manager', 'division' => 'Store Manager', 'work_location' => 'Jalan Gajah Mada, Pontianak', 'joined_date' => '2020-11-20', 'is_active' => true],
            ['employee_id' => 'KS-004', 'full_name' => 'Dedi Kurniawan', 'brand' => 'jojo juice', 'position' => 'Kitchen Staff', 'division' => 'Kitchen', 'work_location' => 'Jalan Diponegoro, Singkawang', 'joined_date' => '2022-01-08', 'is_active' => false],
            ['employee_id' => 'KS-005', 'full_name' => 'Eka Fitriani', 'brand' => 'jojo juice', 'position' => 'Barista', 'division' => 'Barista', 'work_location' => 'Jalan Thamrin, Singkawang', 'joined_date' => '2022-04-19', 'is_active' => true],
            ['employee_id' => 'KS-006', 'full_name' => 'Fitri Amelia', 'brand' => 'jojo juice', 'position' => 'Supervisor', 'division' => 'Supervisor', 'work_location' => 'Jalan Merdeka, Pontianak', 'joined_date' => '2019-09-30', 'is_active' => true],
            ['employee_id' => 'KS-007', 'full_name' => 'Gilang Ramadhan', 'brand' => 'jojo juice', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Ahmad Yani, Pontianak', 'joined_date' => '2023-02-11', 'is_active' => true],
            ['employee_id' => 'KS-008', 'full_name' => 'Hana Salsabila', 'brand' => 'jojo juice', 'position' => 'Barista', 'division' => 'Barista', 'work_location' => 'Jalan Gajah Mada, Pontianak', 'joined_date' => '2023-06-27', 'is_active' => false],
            ['employee_id' => 'BM-001', 'full_name' => 'Indra Wijaya', 'brand' => 'jly', 'position' => 'Kitchen Staff', 'division' => 'Back of House', 'work_location' => 'Jalan Diponegoro, Singkawang', 'joined_date' => '2020-07-15', 'is_active' => true],
            ['employee_id' => 'BM-002', 'full_name' => 'Joko Susilo', 'brand' => 'jly', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Thamrin, Singkawang', 'joined_date' => '2021-08-03', 'is_active' => true],
            ['employee_id' => 'BM-003', 'full_name' => 'Kartika Sari', 'brand' => 'jly', 'position' => 'Store Manager', 'division' => 'Store Manager', 'work_location' => 'Jalan Merdeka, Pontianak', 'joined_date' => '2019-12-01', 'is_active' => true],
            ['employee_id' => 'BM-004', 'full_name' => 'Lukman Hakim', 'brand' => 'jly', 'position' => 'Kitchen Staff', 'division' => 'Back of House', 'work_location' => 'Jalan Ahmad Yani, Pontianak', 'joined_date' => '2022-03-22', 'is_active' => true],
            ['employee_id' => 'BM-005', 'full_name' => 'Maya Lestari', 'brand' => 'jly', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Gajah Mada, Pontianak', 'joined_date' => '2023-01-17', 'is_active' => false],
            ['employee_id' => 'BM-006', 'full_name' => 'Nanda Pratama', 'brand' => 'jly', 'position' => 'Supervisor', 'division' => 'Supervisor', 'work_location' => 'Jalan Diponegoro, Singkawang', 'joined_date' => '2020-05-05', 'is_active' => true],
            ['employee_id' => 'PR-001', 'full_name' => 'Olivia Wijaya', 'brand' => 'guulak', 'position' => 'Pizza Maker', 'division' => 'Kitchen', 'work_location' => 'Jalan Thamrin, Singkawang', 'joined_date' => '2021-02-14', 'is_active' => true],
            ['employee_id' => 'PR-002', 'full_name' => 'Putra Mahardika', 'brand' => 'guulak', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Merdeka, Pontianak', 'joined_date' => '2021-09-09', 'is_active' => true],
            ['employee_id' => 'PR-003', 'full_name' => 'Qori Amalia', 'brand' => 'guulak', 'position' => 'Store Manager', 'division' => 'Store Manager', 'work_location' => 'Jalan Ahmad Yani, Pontianak', 'joined_date' => '2018-10-11', 'is_active' => true],
            ['employee_id' => 'PR-004', 'full_name' => 'Rizky Ramadhan', 'brand' => 'guulak', 'position' => 'Pizza Maker', 'division' => 'Kitchen', 'work_location' => 'Jalan Gajah Mada, Pontianak', 'joined_date' => '2023-04-18', 'is_active' => true],
            ['employee_id' => 'PR-005', 'full_name' => 'Siti Rahayu', 'brand' => 'guulak', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Diponegoro, Singkawang', 'joined_date' => '2022-11-02', 'is_active' => false],
            ['employee_id' => 'PR-006', 'full_name' => 'Teguh Prasetyo', 'brand' => 'guulak', 'position' => 'Supervisor', 'division' => 'Supervisor', 'work_location' => 'Jalan Thamrin, Singkawang', 'joined_date' => '2020-08-24', 'is_active' => true],
            ['employee_id' => 'NE-001', 'full_name' => 'Umi Kalsum', 'brand' => 'jly', 'position' => 'Cook', 'division' => 'Kitchen', 'work_location' => 'Jalan Merdeka, Pontianak', 'joined_date' => '2021-06-07', 'is_active' => true],
            ['employee_id' => 'NE-002', 'full_name' => 'Vina Oktavia', 'brand' => 'jly', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Ahmad Yani, Pontianak', 'joined_date' => '2022-02-25', 'is_active' => true],
            ['employee_id' => 'NE-003', 'full_name' => 'Wawan Setiawan', 'brand' => 'jly', 'position' => 'Store Manager', 'division' => 'Store Manager', 'work_location' => 'Jalan Gajah Mada, Pontianak', 'joined_date' => '2019-07-19', 'is_active' => true],
            ['employee_id' => 'NE-004', 'full_name' => 'Yuniarti', 'brand' => 'jly', 'position' => 'Cook', 'division' => 'Kitchen', 'work_location' => 'Jalan Diponegoro, Singkawang', 'joined_date' => '2023-03-13', 'is_active' => true],
            ['employee_id' => 'NE-005', 'full_name' => 'Zainal Abidin', 'brand' => 'jly', 'position' => 'Cashier', 'division' => 'Front of House', 'work_location' => 'Jalan Thamrin, Singkawang', 'joined_date' => '2020-12-02', 'is_active' => false],
            ['employee_id' => 'SL-001', 'full_name' => 'Agus Salim', 'brand' => 'jojo juice', 'position' => 'Sushi Chef', 'division' => 'Kitchen', 'work_location' => 'Jalan Merdeka, Pontianak', 'joined_date' => '2021-01-11', 'is_active' => true],
            ['employee_id' => 'SL-002', 'full_name' => 'Bella Saputri', 'brand' => 'jojo juice', 'position' => 'Hostess', 'division' => 'Front of House', 'work_location' => 'Jalan Ahmad Yani, Pontianak', 'joined_date' => '2022-05-30', 'is_active' => true],
            ['employee_id' => 'SL-003', 'full_name' => 'Candra Nugraha', 'brand' => 'jojo juice', 'position' => 'Store Manager', 'division' => 'Store Manager', 'work_location' => 'Jalan Gajah Mada, Pontianak', 'joined_date' => '2018-06-18', 'is_active' => true],
            ['employee_id' => 'SL-004', 'full_name' => 'Dewi Anggraini', 'brand' => 'jojo juice', 'position' => 'Sushi Chef', 'division' => 'Kitchen', 'work_location' => 'Jalan Diponegoro, Singkawang', 'joined_date' => '2023-05-09', 'is_active' => true],
            ['employee_id' => 'HQ-001', 'full_name' => 'Erwin Perkasa', 'brand' => 'jojo juice', 'position' => 'HR Manager', 'division' => 'Head Office', 'work_location' => 'Jalan Thamrin, Singkawang', 'joined_date' => '2018-03-05', 'is_active' => true],
            ['employee_id' => 'HQ-002', 'full_name' => 'Farah Nadia', 'brand' => 'jly', 'position' => 'Finance Staff', 'division' => 'Head Office', 'work_location' => 'Jalan Merdeka, Pontianak', 'joined_date' => '2020-09-15', 'is_active' => true],
            ['employee_id' => 'HQ-003', 'full_name' => 'Genta Pradana', 'brand' => 'guulak', 'position' => 'Marketing Staff', 'division' => 'Head Office', 'work_location' => 'Jalan Ahmad Yani, Pontianak', 'joined_date' => '2021-04-12', 'is_active' => true],
            ['employee_id' => 'HQ-004', 'full_name' => 'Halimah', 'brand' => 'jly', 'position' => 'IT Support', 'division' => 'Head Office', 'work_location' => 'Jalan Gajah Mada, Pontianak', 'joined_date' => '2022-12-05', 'is_active' => false],
        ];
    }
}