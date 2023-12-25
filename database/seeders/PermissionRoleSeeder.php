<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser = Role::create(['name' => 'user']);

        $permissionIndexVariableInput = Permission::create(['name' => 'variable-input.index']);
        $roleAdmin->givePermissionTo($permissionIndexVariableInput);
        $roleUser->givePermissionTo($permissionIndexVariableInput);
        $permissionShowVariableInput = Permission::create(['name' => 'variable-input.show']);
        $roleAdmin->givePermissionTo($permissionShowVariableInput);
        $roleUser->givePermissionTo($permissionShowVariableInput);
        $permissionStoreVariableInput = Permission::create(['name' => 'variable-input.store']);
        $roleAdmin->givePermissionTo($permissionStoreVariableInput);
        $roleUser->givePermissionTo($permissionStoreVariableInput);
        $permissionUpdateVariableInput = Permission::create(['name' => 'variable-input.update']);
        $roleAdmin->givePermissionTo($permissionUpdateVariableInput);
        $roleUser->givePermissionTo($permissionUpdateVariableInput);


        $permissionIndexVariableOutput = Permission::create(['name' => 'variable-output.index']);
        $roleAdmin->givePermissionTo($permissionIndexVariableOutput);
        $roleUser->givePermissionTo($permissionIndexVariableOutput);
        $permissionShowVariableOutput = Permission::create(['name' => 'variable-output.show']);
        $roleAdmin->givePermissionTo($permissionShowVariableOutput);
        $roleUser->givePermissionTo($permissionShowVariableOutput);
        $permissionStoreVariableOutput = Permission::create(['name' => 'variable-output.store']);
        $roleAdmin->givePermissionTo($permissionStoreVariableOutput);
        $roleUser->givePermissionTo($permissionStoreVariableOutput);
        $permissionUpdateVariableOutput = Permission::create(['name' => 'variable-output.update ']);
        $roleAdmin->givePermissionTo($permissionUpdateVariableOutput);
        $roleUser->givePermissionTo($permissionUpdateVariableOutput);

        $permissionIndexMatrixCompare =  Permission::create(['name' => 'matrix-compare.index']);
        $roleAdmin->givePermissionTo($permissionIndexMatrixCompare);
        $roleUser->givePermissionTo($permissionIndexMatrixCompare);
        $permissionNormalizationMatrixCompare = Permission::create(['name' => 'matrix-compare.normalization']);
        $roleAdmin->givePermissionTo($permissionNormalizationMatrixCompare);
        $roleUser->givePermissionTo($permissionNormalizationMatrixCompare);
        $permissionWeightMatrixCompare = Permission::create(['name' => 'matrix-compare.weight']);
        $roleAdmin->givePermissionTo($permissionWeightMatrixCompare);
        $roleUser->givePermissionTo($permissionWeightMatrixCompare);
        $permissionStoreMatrixCompare = Permission::create(['name' => 'matrix-compare.store']);
        $roleAdmin->givePermissionTo($permissionStoreMatrixCompare);
        $roleUser->givePermissionTo($permissionStoreMatrixCompare);
        $permissionUpdateMatrixCompare = Permission::create(['name' => 'matrix-compare.update']);
        $roleAdmin->givePermissionTo($permissionUpdateMatrixCompare);
        $roleUser->givePermissionTo($permissionUpdateMatrixCompare);

        $permissionPredict = Permission::create(['name' => 'predict']);
        $roleAdmin->givePermissionTo($permissionPredict);
        $roleUser->givePermissionTo($permissionPredict);
    }
}
