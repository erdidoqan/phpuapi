<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Difficulty;
use App\Episode;
use App\Lesson;
use App\Post;
use App\PostCategory;
use App\Skill;
use App\Tag;
use App\ThreadCategory;
use App\Thread;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Comment;

$factory->define(Lesson::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'user_id' => function () {
            return factory(User::class)->states('instructor')->create();
        },
        'skill_id' => function () {
            return factory(Skill::class)->create();
        },
        'difficulty_id' => function () {
            return factory(Difficulty::class)->create();
        },
        'name' => ucfirst($name),
        'slug' => Str::slug($name),
        'description' => $faker->paragraph,
        'standalone' => rand(0, 1),
        'published' => 1,
    ];
});

$factory->define(Episode::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'lesson_id' => function () {
            return factory(Lesson::class)->create();
        },
        'name' => ucfirst($name),
        'slug' => Str::slug($name),
        'description' => $faker->paragraph,
        'video_id' => $faker->ean8,
        'duration' => rand(100,999),
        'free' => rand(0, 1),
        'downloadable' => rand(0, 1),
        'order' => 1,
        'published' => 1,
    ];
});

$factory->define(Skill::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'name' => ucfirst($name),
        'slug' => Str::slug($name),
        'description' => $faker->paragraph,
    ];
});

$factory->define(Difficulty::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'name' => ucfirst($name)
    ];
});

$factory->define(Tag::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'name' => ucfirst($name)
    ];
});

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create();
        },
        'body' => $faker->paragraph,
        'approved' => 1,
    ];
});

$factory->state(Comment::class, 'episode', function (Faker $faker) {
    return [
        'commentable_type' => Episode::class,
    ];
});

$factory->define(PostCategory::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'title' => ucfirst($name),
        'slug' => Str::slug($name),
        'body' => $faker->paragraph
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'title' => ucfirst($name),
        'slug' => Str::slug($name),
        'excerpt' => $faker->paragraph,
        'body' => $faker->text(2000),
        'published' => 1,
        'views_count' => $faker->numberBetween(1, 333)
    ];
});

$factory->define(ThreadCategory::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'title' => ucfirst($name),
        'slug' => Str::slug($name),
        'body' => $faker->paragraph
    ];
});

$factory->define(Thread::class, function (Faker $faker) {
    $name = implode(' ',$faker->words);
    return [
        'title' => ucfirst($name),
        'slug' => Str::slug($name),
        'body' => $faker->text(500),
        'published' => 1,
        'views_count' => $faker->numberBetween(1, 333)
    ];
});
