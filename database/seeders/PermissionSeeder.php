<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------------
        // A. TẠO ĐẦY ĐỦ PERMISSIONS
        // -------------------------------------------------------------
        $permissions = [
            // UserController
            ['name' => 'USERS.FINDALL',      'description' => 'List user (kèm role)'],
            ['name' => 'USERS.CREATE',       'description' => 'Tạo user mới (kèm role)'],
            ['name' => 'USERS.FINDONE',      'description' => 'Xem chi tiết user'],
            ['name' => 'USERS.UPDATE',       'description' => 'Sửa thông tin / đổi role'],
            ['name' => 'USERS.DELETE',       'description' => 'Vô hiệu hóa user (is_active = false)'],
            ['name' => 'USERS.UPDATESTATUS', 'description' => 'Kích hoạt lại / khóa user'],

            // RoleController
            ['name' => 'ROLES.FINDALL',      'description' => 'List role catalog'],

            // SpecialtyController
            ['name' => 'SPECIALTIES.FINDALL','description' => 'List chuyên khoa'],
            ['name' => 'SPECIALTIES.CREATE', 'description' => 'Tạo chuyên khoa'],
            ['name' => 'SPECIALTIES.FINDONE','description' => 'Chi tiết chuyên khoa'],
            ['name' => 'SPECIALTIES.UPDATE', 'description' => 'Sửa chuyên khoa'],
            ['name' => 'SPECIALTIES.DELETE', 'description' => 'Xóa chuyên khoa'],

            // DoctorController
            ['name' => 'DOCTORS.FINDALL',    'description' => 'List bác sĩ (filter theo chuyên khoa)'],
            ['name' => 'DOCTORS.CREATE',     'description' => 'Tạo hồ sơ bác sĩ'],
            ['name' => 'DOCTORS.FINDONE',    'description' => 'Chi tiết bác sĩ'],
            ['name' => 'DOCTORS.UPDATE',     'description' => 'Sửa hồ sơ bác sĩ'],
            ['name' => 'DOCTORS.DELETE',     'description' => 'Xóa hồ sơ bác sĩ'],

            // PatientController
            ['name' => 'PATIENTS.FINDALL',   'description' => 'List bệnh nhân (search theo tên/SĐT)'],
            ['name' => 'PATIENTS.CREATE',    'description' => 'Tạo hồ sơ bệnh nhân'],
            ['name' => 'PATIENTS.FINDONE',   'description' => 'Chi tiết bệnh nhân'],
            ['name' => 'PATIENTS.UPDATE',    'description' => 'Sửa hồ sơ bệnh nhân'],
            ['name' => 'PATIENTS.DELETE',    'description' => 'Soft delete bệnh nhân'],

            // AppointmentController
            ['name' => 'APPOINTMENTS.FINDALL',     'description' => 'List lịch khám'],
            ['name' => 'APPOINTMENTS.CREATE',      'description' => 'Đặt lịch khám'],
            ['name' => 'APPOINTMENTS.FINDONE',     'description' => 'Chi tiết lịch khám'],
            ['name' => 'APPOINTMENTS.UPDATE',      'description' => 'Sửa giờ hẹn/lý do'],
            ['name' => 'APPOINTMENTS.UPDATESTATUS','description' => 'Xác nhận / hủy / hoàn tất lịch khám'],

            // ExaminationController
            ['name' => 'EXAMINATIONS.FINDALL','description' => 'List phiếu khám'],
            ['name' => 'EXAMINATIONS.CREATE', 'description' => 'Tạo phiếu khám từ lịch khám'],
            ['name' => 'EXAMINATIONS.FINDONE','description' => 'Chi tiết phiếu khám'],
            ['name' => 'EXAMINATIONS.UPDATE', 'description' => 'Sửa chẩn đoán/ghi chú'],

            // MedicineController
            ['name' => 'MEDICINES.FINDALL',    'description' => 'List thuốc'],
            ['name' => 'MEDICINES.CREATE',     'description' => 'Tạo thuốc'],
            ['name' => 'MEDICINES.FINDONE',    'description' => 'Chi tiết thuốc'],
            ['name' => 'MEDICINES.UPDATE',     'description' => 'Sửa thông tin/giá thuốc'],
            ['name' => 'MEDICINES.DELETE',     'description' => 'Soft delete thuốc'],
            ['name' => 'MEDICINES.ADJUSTSTOCK','description' => 'Nhập kho / điều chỉnh tồn kho'],

            // PrescriptionController
            ['name' => 'PRESCRIPTIONS.FINDALL',   'description' => 'List đơn thuốc'],
            ['name' => 'PRESCRIPTIONS.CREATE',    'description' => 'Tạo đơn thuốc từ phiếu khám'],
            ['name' => 'PRESCRIPTIONS.FINDONE',   'description' => 'Chi tiết đơn thuốc'],
            ['name' => 'PRESCRIPTIONS.UPDATE',    'description' => 'Sửa ghi chú đơn thuốc'],
            ['name' => 'PRESCRIPTIONS.ADDITEM',   'description' => 'Thêm thuốc vào đơn'],
            ['name' => 'PRESCRIPTIONS.UPDATEITEM','description' => 'Sửa liều dùng/số lượng'],
            ['name' => 'PRESCRIPTIONS.REMOVEITEM','description' => 'Xóa thuốc khỏi đơn'],

            // InvoiceController
            ['name' => 'INVOICES.FINDALL',     'description' => 'List hóa đơn'],
            ['name' => 'INVOICES.CREATE',      'description' => 'Tạo hóa đơn từ phiếu khám'],
            ['name' => 'INVOICES.FINDONE',     'description' => 'Chi tiết hóa đơn'],
            ['name' => 'INVOICES.UPDATE',      'description' => 'Sửa discount'],
            ['name' => 'INVOICES.UPDATESTATUS','description' => 'Hủy hóa đơn'],

            // PaymentController
            ['name' => 'PAYMENTS.FINDALL',  'description' => 'List thanh toán theo hóa đơn'],
            ['name' => 'PAYMENTS.CREATE',   'description' => 'Tạo lệnh thanh toán PayPal/Visa'],
            ['name' => 'PAYMENTS.CAPTURE',  'description' => 'Capture / xác nhận thanh toán'],

            // StatsController
            ['name' => 'STATS.SHOW',        'description' => 'Số liệu tổng quan hệ thống'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                ['description' => $perm['description']]
            );
        }

        // -------------------------------------------------------------
        // B. MAP PERMISSION → ROLE
        // -------------------------------------------------------------
        $rolesMap = Role::pluck('id', 'name')->toArray();
        $permsMap = Permission::pluck('id', 'name')->toArray();

        $rolePermissionsMapping = [
            'ADMIN' => array_keys($permsMap),

            'RECEPTIONIST' => [
                'PATIENTS.FINDALL', 'PATIENTS.CREATE', 'PATIENTS.FINDONE', 'PATIENTS.UPDATE',
                'APPOINTMENTS.FINDALL', 'APPOINTMENTS.CREATE', 'APPOINTMENTS.FINDONE', 'APPOINTMENTS.UPDATE', 'APPOINTMENTS.UPDATESTATUS',
                'DOCTORS.FINDALL', 'DOCTORS.FINDONE',
                'SPECIALTIES.FINDALL', 'SPECIALTIES.FINDONE',
            ],

            'DOCTOR' => [
                'PATIENTS.FINDALL', 'PATIENTS.FINDONE',
                'APPOINTMENTS.FINDALL', 'APPOINTMENTS.FINDONE', 'APPOINTMENTS.UPDATESTATUS',
                'EXAMINATIONS.FINDALL', 'EXAMINATIONS.CREATE', 'EXAMINATIONS.FINDONE', 'EXAMINATIONS.UPDATE',
                'PRESCRIPTIONS.FINDALL', 'PRESCRIPTIONS.CREATE', 'PRESCRIPTIONS.FINDONE', 'PRESCRIPTIONS.UPDATE',
                'PRESCRIPTIONS.ADDITEM', 'PRESCRIPTIONS.UPDATEITEM', 'PRESCRIPTIONS.REMOVEITEM',
                'MEDICINES.FINDALL', 'MEDICINES.FINDONE',
            ],

            'PHARMACIST' => [
                'MEDICINES.FINDALL', 'MEDICINES.CREATE', 'MEDICINES.FINDONE', 'MEDICINES.UPDATE', 'MEDICINES.ADJUSTSTOCK',
                'PRESCRIPTIONS.FINDALL', 'PRESCRIPTIONS.FINDONE',
            ],

            'CASHIER' => [
                'PATIENTS.FINDALL', 'PATIENTS.FINDONE',
                'INVOICES.FINDALL', 'INVOICES.CREATE', 'INVOICES.FINDONE', 'INVOICES.UPDATE', 'INVOICES.UPDATESTATUS',
                'PAYMENTS.FINDALL', 'PAYMENTS.CREATE', 'PAYMENTS.CAPTURE',
                'STATS.SHOW',
            ],
        ];

        DB::table('role_permissions')->truncate();
        
        foreach ($rolePermissionsMapping as $roleCode => $permCodes) {
            if (!isset($rolesMap[$roleCode])) continue;

            $role = Role::find($rolesMap[$roleCode]);
            $permissionIds = collect($permCodes)
                ->map(fn($code) => $permsMap[$code] ?? null)
                ->filter()
                ->toArray();

            $role->permissions()->sync($permissionIds);
        }
    }
}