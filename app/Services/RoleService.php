<?php
namespace App\Services;

use App\Models\Role;


class RoleService
{
    public function getRoleHierarchy($roles, $parentId = 0, $prefix = '', $excludeId = null)
    {
        $result = [];

        foreach ($roles as $role) {
            if ($role->parent == $parentId && $role->id != $excludeId) {
                $role->display_name = $prefix . $role->name;
                $result[] = $role;

                $children = $this->getRoleHierarchy($roles, $role->id, $prefix . '-- ', $excludeId);
                $result = array_merge($result, $children);
            }
        }

        return $result;
    }
    public function renderRoleOptions($roles, $selectedId, $currentId = null, $level = 0) {
        foreach ($roles as $role) {
            if ($role->id === $currentId) continue;

            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . ($level > 0 ? '↳ ' : '');
            $selected = $role->id == $selectedId ? 'selected' : '';

            echo "<option value=\"{$role->id}\" {$selected}>{$indent}{$role->name}</option>";

            if ($role->children && $role->children->count()) {
                $this->renderRoleOptions($role->children, $selectedId, $currentId, $level + 1);
            }
        }
    }
    public function renderRoleOptionsUser($roles, $selectedId, $currentId = null, $level = 0) {
        foreach ($roles as $role) {
            if ($role->id === $currentId) continue;

            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . ($level > 0 ? '↳ ' : '');
            $selected = $role->id == $selectedId ? 'selected' : '';

            echo "<option value=\"{$role->name}\" {$selected}>{$indent}{$role->name}</option>";

            if ($role->children && $role->children->count()) {
                $this->renderRoleOptionsUser($role->children, $selectedId, $currentId, $level + 1);
            }
        }
    }
    
    public function RoleByName(string $name)
    {
        return Role::where('name', $name)->first();
    }
    
    public function AllRoles()
    {
        return Role::with('children')->get();
    }
    public function RoleById(string $id)
    {
        return Role::findOrFail($id);
    }
}
