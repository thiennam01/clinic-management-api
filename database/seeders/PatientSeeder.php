<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['Nguyễn Văn Bệnh Nhân', 'male', '1995-05-15', '0912345678'],
            ['Lê Thị Bệnh Nhân', 'female', '1998-10-20', '0987654321'],
            ['Phạm Minh Hoàng', 'male', '1992-03-12', '0901234567'],
            ['Trần Thu Hà', 'female', '1997-07-25', '0911223344'],
            ['Đỗ Quốc Anh', 'male', '1989-11-03', '0922334455'],
            ['Nguyễn Thị Lan', 'female', '1994-02-18', '0933445566'],
            ['Vũ Minh Tuấn', 'male', '2001-06-10', '0944556677'],
            ['Hoàng Ngọc Mai', 'female', '2000-09-14', '0955667788'],
            ['Bùi Đức Long', 'male', '1985-12-21', '0966778899'],
            ['Phan Thị Hương', 'female', '1990-04-08', '0977889900'],
            ['Đặng Minh Quân', 'male', '1999-01-30', '0988990011'],
            ['Nguyễn Thu Trang', 'female', '1996-08-17', '0999001122'],
            ['Trần Văn Nam', 'male', '1987-05-26', '0901122334'],
            ['Lý Thị Hoa', 'female', '1993-10-05', '0912233445'],
            ['Mai Văn Thành', 'male', '1991-03-19', '0923344556'],
            ['Đỗ Thị Ngọc', 'female', '2002-11-11', '0934455667'],
            ['Nguyễn Hoàng Sơn', 'male', '1984-07-02', '0945566778'],
            ['Phạm Thị Yến', 'female', '1995-12-09', '0956677889'],
            ['Trần Minh Khôi', 'male', '2003-04-23', '0967788990'],
            ['Hoàng Thị Linh', 'female', '1998-06-29', '0978899001'],
        ];

        foreach ($patients as $index => $patient) {
            Patient::updateOrCreate(
                ['code' => sprintf('BN-%06d', $index + 1)],
                [
                    'full_name' => $patient[0],
                    'gender' => $patient[1],
                    'date_of_birth' => $patient[2],
                    'phone' => $patient[3],
                    'email' => 'patient' . ($index + 1) . '@clinic.test',
                    'address' => 'Hà Nội',
                ]
            );
        }
    }
}