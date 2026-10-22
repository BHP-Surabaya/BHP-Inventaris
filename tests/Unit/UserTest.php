<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_user_role_label_returns_expected_names(): void
    {
        $admin = new User(['role' => 'admin']);
        $this->assertEquals('Admin Gudang', $admin->role_label);
        $this->assertTrue($admin->isAdmin());

        $staff = new User(['role' => 'pegawai_gudang']);
        $this->assertEquals('Staff Gudang', $staff->role_label);
        $this->assertTrue($staff->isPegawaiGudang());
        $this->assertTrue($staff->isStaffGudang());

        $defaultUser = new User();
        $this->assertEquals('Staff Gudang', $defaultUser->role_label);
    }
}
