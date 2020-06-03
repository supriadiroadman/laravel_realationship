<?php

use App\Category;
use App\Post;
use App\Profile;
use App\Role;
use App\User;

Route::get('/', function () {
    $user = User::findOrFail(2);
    $user->posts()->create([
        'title' => 'Title Milik admin 2',
        'body' => 'Body Milik admin 2',
    ]);
    return $user->posts;
});


// One To One------------------------------------------------

Route::get('/create_user', function () {
    $user = User::create([
        'name' => 'Roadman',
        'email' => 'Roadman@gmail.com',
        'password' => bcrypt('rahasia')
    ]);
    return $user;
});

Route::get('/create_profile', function () {
    $profile = Profile::create([
        'user_id' => 1,
        'phone' => '08120000',
        'address' => 'Jakarta',
    ]);
    return $profile;
});

Route::get('/create_user_profile', function () {

    $user = User::find(2);
    // $profile = new Profile([
    //     'phone' => '0812345654',
    //     'address' => 'Medan'
    // ]);
    // if ($user->profile()->count() > 0) {
    //     return 'Data User sudah ada';
    // }
    // $user->profile()->save($profile);

    $user->profile()->create([
        'phone' => '0812345654',
        'address' => 'Bandung'
    ]);

    return $user->profile;
});

Route::get('read_user', function () {

    $user = User::find(1);
    $data = [
        'Goar' => $user->name,
        'phone' => $user->profile->phone,
        'address' => $user->profile->address
    ];
    return $data;
});

Route::get('read_profile', function () {

    $profile = Profile::where('address', 'Duri')->first();
    $data = [
        'name' => $profile->user->name,
        'email' => $profile->user->email,
        'phone' => $profile->phone,
        'address' => $profile->address,
    ];
    return $data;
});


Route::get('/update_user_profile', function () {
    $user = User::find(1);

    $data = [
        'phone' => '082320538654',
        'address' => 'Medan'
    ];

    $user->profile()->update($data);

    // $user->profile()->update([
    //     'phone' => '082320538654',
    //     'address' => 'Bandung'
    // ]);
    return $user->profile;
});

Route::get('delete_user_profile', function () {
    $user = User::find(1);
    // $user->delete();
    // return $user;
    $user->profile()->delete();
    return $user->profile;
});



// One To Many------------------------------------------------

Route::get('/create_user_post', function () {
    // $user = User::create([
    //     'name' => 'Roadman',
    //     'email' => 'Roadman@gmail.com',
    //     'password' => bcrypt('rahasia')
    // ]);

    $user = User::findOrFail(2);
    $user->posts()->create([
        'title' => 'Title 3',
        'body' => 'Body 3',
    ]);
    return $user->posts;
});

Route::get('/read_user_post', function () {
    $user = User::findOrFail(1);
    // return $user->posts;
    // dd($user->posts()->get());

    // $posts = $user->posts()->first();

    // $data = [
    //     'Nama' => $user->name,
    //     'Judul' => $posts->title,
    //     'Body' => $posts->body,
    // ];

    $posts = $user->posts()->get();
    foreach ($posts as $post) {
        $data[] = [
            'Nama' => $user->name,
            'Post Id' => $post->user_id,
            'Judul' => $post->title,
            'Body' => $post->body,
        ];
    }
    return $data;
});


Route::get('/update_user_post', function () {


    $user = User::findOrFail(1);

    // $user->posts()->where('id', 2)->update([
    //     'title' => 'Title 2 update',
    //     'body' => 'Body 2 update',
    // ]);

    $user->posts()->whereId(2)->update([
        'title' => 'Title 2 update',
        'body' => 'Body 2 update',
    ]);

    return $user->posts;
});

Route::get('/delete_user_post', function () {
    $user = User::findOrFail(2);

    // $user->posts()->whereTitle('Title 3')->delete();
    $user->posts()->where('id', 3)->delete();
    // $user->posts()->whereId(1)->delete();
    return $user->posts;
});





// Many To Many------------------------------------------------

Route::get('create_post_category', function () {
    $post = Post::findOrFail(1);

    $post->categories()->create([
        'slug' => Str::slug('Belajar PHP', '-'),
        'category' => 'Category PHP'
    ]);

    return $post->categories;
});

Route::get('create_user_post_category', function () {
    $user = User::create([
        'name' => 'Roadman',
        'email' => 'Roadman@gmail.com',
        'password' => bcrypt('rahasia')
    ]);

    // $user = User::findOrFail(2);

    $user->posts()->create([
        'title' => 'Title 2',
        'body' => 'Body 2',
    ])->categories()->create([
        'slug' => Str::slug('Belajar PHP', '-'),
        'category' => 'Category PHP'
    ]);
    return $user->posts;
});

Route::get('read_post_category', function () {
    $post = Post::findOrFail(1);
    // return $post->categories;

    $categories = $post->categories;
    // $categories = $post->categories->where('id', 2);
    foreach ($categories as $category) {
        echo "$category->category <br>";
        echo "$category->slug <br>";
        echo "<br>";
    }
});

// Inverse
Route::get('read_category_post', function () {
    $category = Category::findOrFail(1);
    // return $category->posts;

    $posts = $category->posts;
    // $posts = $category->posts->where('id', 2);
    foreach ($posts as $post) {
        echo "$post->title <br>";
        echo "$post->body <br>";
        echo "<br>";
    }
});

Route::get('/attach_post_category', function () {
    $post = Post::findOrFail(1);

    // $post->categories()->attach(2);
    $post->categories()->attach([1, 2, 3]);

    return $post->categories;
});

Route::get('/attach_category_post', function () {
    $category = Category::findOrFail(1);

    // $category->posts()->attach(2);
    $category->posts()->attach([1, 2, 3]);

    return $category->posts;
});

Route::get('/detach_post_category', function () {
    $post = Post::findOrFail(1);

    // $post->categories()->detach();
    // $post->categories()->detach(3);
    $post->categories()->detach([1, 2]);

    return $post->categories;
});

Route::get('/detach_category_post', function () {
    $category = Category::findOrFail(1);

    // $category->posts()->detach();
    // $category->posts()->detach(3);
    $category->posts()->detach([1, 2, 3]);

    return $category->posts;
});


Route::get('/sync_post_category', function () {
    $post = Post::findOrFail(1);

    $post->categories()->sync([1, 2]);

    return $post->categories;
});

Route::get('/sync_category_post', function () {
    $category = Category::findOrFail(1);
    $category->posts()->sync([1, 2, 3]);

    return $category->posts;
});




// Hash Many Through ------------------------------------------------

// Tambahkan data ditabel user, role dan post secara manual dgn tinker untuk praktek ini
// Role Has Many To User
// User Has Many To Post
// Mencari data Post berdasarkan data Role (role_id) melalui tabel User


Route::get('/role_post', function () {

    $role = Role::findOrFail(1);
    return  $role->posts;
});

/*
    "select `posts`.*, `users`.`role_id` as `laravel_through_key` from `posts` inner join `users` on `users`.`id` = `posts`.`user_id` where `users`.`role_id` = ?"
        "bindings" => array:1 [▼
        0 => 1
*/
