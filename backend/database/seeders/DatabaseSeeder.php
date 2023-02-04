<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        //add entity_types

        $entity_id = 'id';
        $type = 'type';
        $created_at = 'created_at';
        $updated_at = 'updated_at';
        $entity_types = [];

        foreach (config('names_to_db.entity_types') as $key => $item) {
            $entity_types[$key][$entity_id] = $key + 1;
            $entity_types[$key][$type] = $item['slug'];
            $entity_types[$key][$created_at] = Date::now();
            $entity_types[$key][$updated_at] = Date::now();
        }

        //add categories

        $categories_id = 'id';
        $categories_title = 'title';
        $categories_slug = 'slug';
        $category_type_id = 'category_type_id';
        $categories = [];

        foreach (config('names_to_db.categories') as $key => $item) {
            $categories[$key][$categories_id] = $key + 1;
            $categories[$key][$categories_title] = $item['category_title'];
            $categories[$key][$categories_slug] = $item['category_slug'];
            $categories[$key][$category_type_id] = (int) $item['category_id'];
        }

        //add category_types

        $category_types_id = 'id';
        $category_types_title = 'title';
        $category_types_slug = 'slug';
        $category_types = [];

        foreach (config('names_to_db.category_types') as $key => $item) {
            $category_types[$key][$category_types_id] = $key + 1;
            $category_types[$key][$category_types_title] = $item['category_title'];
            $category_types[$key][$category_types_slug] = $item['category_slug'];
        }

        //add statuses

        $statuses_id = 'id';
        $statuses_code = 'code';
        $statuses = [];

        foreach (config('names_to_db.statutes') as $key => $status) {
            $statuses[$key][$statuses_id] = $key + 1;
            $statuses[$key][$statuses_code] = $status['code'];
        }

        //add roles

        $roles_id = 'id';
        $roles_code = 'code';
        $roles = [];

        foreach (config('names_to_db.roles') as $key => $role) {
            $roles[$key][$roles_id] = $key + 1;
            $roles[$key][$roles_code] = $role['code'];
        }

        //add entity statuses

        $entity_status_id = 'id';
        $entity_status_code = 'code';
        $entity_statuses = [];

        foreach (config('names_to_db.entity_statuses') as $key => $entity_status) {
            $entity_statuses[$key][$entity_status_id] = $key + 1;
            $entity_statuses[$key][$entity_status_code] = $entity_status['code'];
        }

        //add user

        $user['id'] = 1;
        $user['name'] = 'admin';
        $user['email'] = 'admin@admin.com';
        $user['role_id'] = 1;
        $user['status_id'] = 2;
        $user['password'] = '$2y$10$ll.PSax4PxRt3Z3yvdNDNeKpsc3d1u1NnKNpy54f9weWWIRpF8hgq';
        $user['created_at'] = Carbon::now();
        $user['updated_at'] = Carbon::now();

        //add profile

        $profile['id'] = 1;
        $profile['name'] = 'test profile name';
        $profile['surname'] = 'test profile surname';
        $profile['patronymic'] = 'test profile patronymic';
        $profile['user_id'] = 1;
        $profile['entity_type_id'] = 3;
        $profile['created_at'] = Carbon::now();
        $profile['updated_at'] = Carbon::now();

        //add test admin

        DB::table('user_status')->insert($statuses);
        DB::table('user_role')->insert($roles);
        DB::table('entity_types')->insert($entity_types);
        DB::table('category_types')->insert($category_types);
        DB::table('categories')->insert($categories);
        DB::table('entity_status')->insert($entity_statuses);
        DB::table('users')->insert($user);
        DB::table('profiles')->insert($profile);
    }
}
