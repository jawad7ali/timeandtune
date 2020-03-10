<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@timeandtune.com',
            'phone_no' => '',
            'role_id' => '',
            'created_at' => '16 Feb 2020',
            'roles' => ["admin"],
            "permissions" => [
                "view menu element ui",
                "view menu permission",
                "view menu components",
                "view menu charts",
                "view menu nested routes",
                "view menu table",
                "view menu administrator",
                "view menu theme",
                "view menu clipboard",
                "view menu excel",
                "view menu zip",
                "view menu pdf",
                "view menu i18n",
                "manage user",
                "manage article",
                "manage permission",
            ],
            // 'permissions' => array_map(
            //     function ($permission) {
            //         return $permission['name'];
            //     },
            //     $this->getAllPermissions()->toArray()
            // ),
            'avatar' => 'https://i.pravatar.cc',
        ];
    }
}
