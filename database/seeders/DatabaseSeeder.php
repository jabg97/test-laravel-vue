<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();


        $user_1 = new User;
        $user_1->name = 'Jhon Doe';
        $user_1->save();

        $user_2 = new User;
        $user_2->name = 'Meresa Oslo';
        $user_2->save();

        $user_3 = new User;
        $user_3->name = 'Mike Lines';
        $user_3->save();

        $user_1->hasLiked()->attach($user_2);
        $user_1->hasLiked()->attach($user_3);
        $user_2->hasLiked()->attach($user_1);

        $menu = new Menu;
        $menu->label = 'Home';
        $menu->url = '/home';
        $menu->icon = 'home-icon';
        $menu->save();

        $parent = new Menu;
        $parent->label = 'Trips';
        $parent->url = '/trips';
        $parent->icon = 'trip-icon';
        $parent->save();
        
        $sub_menu = new Menu;
        $sub_menu->label = 'Own Products';
        $sub_menu->url = '/trips/own';
        $sub_menu->icon = 'trip-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();

        $sub_menu = new Menu;
        $sub_menu->label = 'Others Products';
        $sub_menu->url = '/trips/other';
        $sub_menu->icon = 'trip-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();

        $parent = new Menu;
        $parent->label = 'Flights';
        $parent->url = '/flights';
        $parent->icon = 'flight-icon';
        $parent->save();

        $sub_menu = new Menu;
        $sub_menu->label = ' National Flights';
        $sub_menu->url = '/flights/nationals';
        $sub_menu->icon = 'flight-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();

        $sub_menu = new Menu;
        $sub_menu->label = 'International Flights';
        $sub_menu->url = '/flights/internationals';
        $sub_menu->icon = 'flight-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();
    }
}
