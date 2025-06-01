namespace App\Helpers;

use App\Models\Menu;

class MenuBuilder
{
    public static function getMenus($user)
    {
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        // Ambil semua menu aktif dan urut
        $menus = Menu::where('is_active', 1)
            ->orderBy('parent_id')
            ->orderBy('id')
            ->get();

        // Filter menu berdasarkan permission
        $filteredMenus = $menus->filter(function ($menu) use ($userPermissions) {
            return !$menu->permission || in_array($menu->permission, $userPermissions);
        });

        // Susun menjadi struktur parent-child
        $menuTree = $filteredMenus->whereNull('parent_id')->map(function ($parent) use ($filteredMenus) {
            $parent->children = $filteredMenus->where('parent_id', $parent->id);
            return $parent;
        });

        return $menuTree;
    }
}
