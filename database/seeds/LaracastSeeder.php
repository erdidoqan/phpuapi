<?php

use App\Conversation;
use App\CreditCard;
use App\Difficulty;
use App\Episode;
use App\Lesson;
use App\Post;
use App\PostCategory;
use App\Skill;
use App\Subscription;
use App\SubscriptionPayment;
use App\SubscriptionType;
use App\Tag;
use App\ThreadCategory;
use App\Thread;
use App\User;
use Laravel\Passport\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Comment;

class LaracastSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)
            ->create(['name' => 'User', 'email' => 'user@example.com']);

        factory(Skill::class)->create([
            'name' => 'Laravel',
            'slug' => Str::slug('Laravel'),
            'icon' => 'lv',
        ]);

        factory(Skill::class)->create([
            'name' => 'PHP',
            'slug' => Str::slug('PHP'),
            'icon' => 'php',
        ]);

        factory(Skill::class)->create([
            'name' => 'Tooling',
            'slug' => Str::slug('Tooling'),
            'icon' => 'tool',
        ]);

        factory(Skill::class)->create([
            'name' => 'JavaScript',
            'slug' => Str::slug('JavaScript'),
            'icon' => 'js',
        ]);

        factory(Skill::class)->create([
            'name' => 'Testing',
            'slug' => Str::slug('Testing'),
            'icon' => 'test',
        ]);

        factory(Difficulty::class)->create([
            'name' => 'Advanced',
        ]);

        factory(Difficulty::class)->create([
            'name' => 'Beginner',
        ]);

        factory(Difficulty::class)->create([
            'name' => 'Intermediate',
        ]);

        factory(Tag::class)->create([
            'name' => 'Eloquent'
        ]);

        factory(Tag::class)->create([
            'name' => 'React'
        ]);

        factory(Tag::class)->create([
            'name' => 'Server'
        ]);

        factory(Tag::class)->create([
            'name' => 'Vue'
        ]);

        $instructor = factory(User::class)
            ->create(['name' => 'Eğitmen', 'email' => 'instructor@example.com'])
            ->assignRole('instructor');

        $lesson1 = Lesson::create([
            'user_id' => 2,
            'skill_id' => 1,
            'difficulty_id' => 2,
            'name' => 'Laravel 5.7 From Scratch',
            'slug' => Str::slug('Laravel 5.7 From Scratch'),
            'description' => 'Laravel From Scratch has been the go-to video resource for Laravel newcomers since 2013. Considering this, as you can imagine, this truth requires that we repeatedly refresh the series to ensure that it remains as up-to-date as possible. To celebrate the release of Laravel 5.7, we ve done it again. Every video has been re-recorded. Every technique has been optimized. Every example has been updated. I hope you enjoy it! And if you are brand new to Laravel, you are in for a treat. Laravel is a joy to work with. If you are willing, I willl teach you everything I know.',
            'standalone' => 0,
            'published' => 1,
        ]);
        $lesson1->addMediaFromUrl('https://laracasts.com/images/series/2018/laravel-from-scratch.svg')->toMediaCollection();

        Episode::create([
            'lesson_id' => $lesson1->id,
            'name' => ucfirst('The Laravel Sell'),
            'slug' => Str::slug('The Laravel Sell'),
            'description' => 'Presumably, if you are watching this series, you have already made the decision to embrace all that Laravel has to offer. However, if you are still on the fence, give me just a moment to sell you on why I believe Laravel is the best framework choice in the PHP world.',
            'video_id' => '151390908',
            'duration' => 152,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 1,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson1->id,
            'name' => ucfirst('Initial Setup Requirements'),
            'slug' => Str::slug('Initial Setup Requirements'),
            'description' => 'Like any modern PHP framework, youll need to install a few prerequisites to prepare for Laravel. Dont worry: this is a one-time job. Stick with me, and well get through it quickly. Well first install Composer, make it available system-wide, and then pull in the Laravel installer. This small tool will allow us to run a simple command (laravel new app) to instantly generate a fresh Laravel project.',
            'video_id' => '353832763',
            'duration' => 452,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson1->id,
            'name' => ucfirst('Basic Routing'),
            'slug' => Str::slug('Basic Routing'),
            'description' => 'When learning a new framework, one of your first tasks is to determine how routing is handled. Or in other words, when I visit a particular URL in the browser, how does my framework route that URL to the necessary logic? Lets review the basics in this episode.',
            'video_id' => '353832763',
            'duration' => 306,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson1->id,
            'name' => ucfirst('Blade Layout Files'),
            'slug' => Str::slug('Blade Layout Files'),
            'description' => 'When constructing your views, youre not limited to basic PHP. Instead, you can use Blade: Laravels powerful templating engine. Well talk about Blade more in the future, but for now, lets leverage it to create a layout file to reduce duplication and complexity.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson1->id,
            'name' => ucfirst('Sending Data to Your Views'),
            'slug' => Str::slug('Sending Data to Your Views'),
            'description' => 'Youll often need to pass data to your views. Perhaps its a collection from the database, or maybe a flash message to confirm a particular user action. Lets review how easy this is to do.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson1->id,
            'name' => ucfirst('Controllers 101'),
            'slug' => Str::slug('Controllers 101'),
            'description' => 'So far, weve handled all route logic through a closure in our routes/web.php file. This is an excellent choice in some cases; however, I think youll find that the majority of your projects will require a bit more structure. Lets learn how to migrate from route closures to dedicated controllers.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);


        $lesson2 = Lesson::create([
            'user_id' => 2,
            'skill_id' => 2,
            'difficulty_id' => 3,
            'name' => ucfirst('How to Read Code'),
            'slug' => Str::slug('How to Read Code'),
            'description' => 'To improve as a developer, you must focus on three things: learning, reading, and writing. Or, in other words, learn from somebody more seasoned than you; read a lot of code; and write your own code daily. This series will focus on the reading component. Together, well mentally parse an open source project. How was it constructed? What are the routing conventions? How are the controllers structured?',
            'standalone' => 0,
            'published' => 1,
        ]);
        $lesson2->addMediaFromUrl('https://laracasts.com/images/series/2018/how-to-read-code.svg')->toMediaCollection();

        Episode::create([
            'lesson_id' => $lesson2->id,
            'name' => ucfirst('Get it Running Locally'),
            'slug' => Str::slug('Get it Running Locally'),
            'description' => 'Lets use the laravel.com source code as the basis for our learning in this series. This code is freely available to review on GitHub. Specifically, well focus on the documentation layer. We see all these markdown files for each page of documentation. How are they loaded, parsed, and presented on the page? And how is versioning handled? Well decode all of it. To begin this journey, however, we must first get the laravel.com source code running locally.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson2->id,
            'name' => ucfirst('Finding the Documentation Page'),
            'slug' => Str::slug('Finding the Documentation Page'),
            'description' => 'Now that we have the application running locally, lets figure out how a documentation page is loaded. We already know that each section is stored as a Markdown file. So how exactly are the files loaded and presented to the user?',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson2->id,
            'name' => ucfirst('Parsing Markdown'),
            'slug' => Str::slug('Parsing Markdown'),
            'description' => 'It seems that, at some point, the companion Markdown for a requested documentation page is loaded, compiled, and cached. In this episode, lets figure out exactly how that workflow is organized and processed.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson2->id,
            'name' => ucfirst('Rendering the Documentation'),
            'slug' => Str::slug('Rendering the Documentation'),
            'description' => 'Weve figured out how the Markdown files are loaded, and parsed. The only remaining step is to get the content on the page. In this episode, well learn how the content is passed to the view, while accounting the situations when an invalid documentation page is requested.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        $lesson3 = Lesson::create([
            'user_id' => 2,
            'skill_id' => 1,
            'difficulty_id' => 2,
            'name' => ucfirst('Eloquent Relationships'),
            'slug' => Str::slug('Eloquent Relationships'),
            'description' => 'Eloquent make the process of interacting with your database tables as natural and intuitive as possible. Its vital that you recognize and understand six key relationship types. Lets review them all - one episode per relationship.',
            'standalone' => 0,
            'published' => 1,
        ]);
        $lesson3->addMediaFromUrl('https://laracasts.com/images/series/2018/eloquent-relationships.svg')->toMediaCollection();

        Episode::create([
            'lesson_id' => $lesson3->id,
            'name' => ucfirst('One to One'),
            'slug' => Str::slug('One to One'),
            'description' => 'First up, we have "one to one" relationships. This is an easy one to picture in your head. What is one thing that a user might be associated with? What about a profile, or an address, or an avatar?',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson3->id,
            'name' => ucfirst('Many to Many'),
            'slug' => Str::slug('Many to Many'),
            'description' => 'Many to many relationships are a bit more confusing to understand. Lets break it down by reviewing the common "posts" and "tags" relationship. A one-to-one or one-to-many relationship isnt quite right here. A post will never own a tag. It can be associated with one, sure, but it doesnt own the tag. The same is true in reverse. When we encounter situations such as this, a "many-to-many" relationship is exactly what we need.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson3->id,
            'name' => ucfirst('Has Many Through'),
            'slug' => Str::slug('Has Many Through'),
            'description' => 'While not nearly as common, the hasManyThrough relationship, when necessary, can prove to be incredibly useful. This relationship type allows you to perform queries across long-distance relationships.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson3->id,
            'name' => ucfirst('Polymorphic Relations'),
            'slug' => Str::slug('Polymorphic Relations'),
            'description' => 'Weve made it to the scariest of Eloquent relations: polymorphic. Dont worry! As with most things, the word is scarier than the technique. A polymorphic relationship allows a model to belong to any number of models on a single association. Lets demonstrate this with a practical example.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson3->id,
            'name' => ucfirst('Many to Many Polymorphic Relations'),
            'slug' => Str::slug('Many to Many Polymorphic Relations'),
            'description' => 'Weve finally made it to the most intimidating of Eloquent relationships: many to many polymorphic. Dont worry; its far simpler to understand than you might initially think. In this lesson, well construct a standard many-to-many relationship: a user can "like" a post. Then, well throw a wrench into the mix. What if a user can also "like" a comment? How might we restructure our existing structure to allow for this?',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        Episode::create([
            'lesson_id' => $lesson3->id,
            'name' => ucfirst('Lets Begin With a Test'),
            'slug' => Str::slug('Lets Begin With a Test'),
            'description' => 'Lets begin by reviewing the application that we plan to build. Well then finish up by installing Laravel and performing the first commit.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        $lesson4 = Lesson::create([
            'user_id' => 2,
            'skill_id' => 1,
            'difficulty_id' => 3,
            'name' => ucfirst('Build A Laravel App With TDD'),
            'slug' => Str::slug('Build A Laravel App With TDD'),
            'description' => 'Its time to take the techniques we learned in Laravel From Scratch, and put them to good use building your first real-world application. Together, well leverage TDD to create Birdboard: a minimal Basecamp-like project management app.',
            'standalone' => 1,
            'published' => 1,
        ]);
        $lesson4->addMediaFromUrl('https://laracasts.com/images/series/2018/build-an-app-with-tdd.svg')->toMediaCollection();

        Episode::create([
            'lesson_id' => $lesson4->id,
            'name' => ucfirst('Meet Birdboard'),
            'slug' => Str::slug('Meet Birdboard'),
            'description' => 'Lets begin by reviewing the application that we plan to build. Well then finish up by installing Laravel and performing the first commit.',
            'video_id' => '353832763',
            'duration' => 446,
            'free' => rand(0, 1),
            'downloadable' => rand(0, 1),
            'order' => 2,
            'published' => 1,
        ]);

        /*factory(Lesson::class, 50)->make(['user_id' => $instructor->id, 'skill_id' => 1, 'difficulty_id' => 1])
            ->each(function ($lesson) {
                $lesson->skill_id = rand(1, 5);
                $lesson->difficulty_id = rand(1, 3);
                $lesson->save();
                $lesson->tags()->attach([rand(1, 2), rand(3, 4)]);
                $episodeCount = $lesson->standalone ? 1 : rand(1, 7);
                factory(Episode::class, $episodeCount)->create(['lesson_id' => $lesson->id]);
            });

        Episode::first()->comments()->saveMany(factory(Comment::class, 3)->make());
        Episode::first()->comments()->saveMany(factory(Comment::class, 2)->make([
            'parent_id' => Episode::first()->comments()->first()->id
        ]));*/

        $editor = factory(User::class)
            ->create(['name' => 'Editör', 'email' => 'editor@example.com'])
            ->assignRole('editor');
        $postCategories = factory(PostCategory::class, 5)->create();
        factory(Post::class, 5)->create([
            'user_id' => $editor->id,
            'category_id' => $postCategories->random()->id
        ]);

        $users = factory(User::class, 5)->create();
        $threadCategories = factory(ThreadCategory::class, 5)->create();
        factory(Thread::class, 5)->create([
            'user_id' => $users->random()->id,
            'category_id' => $threadCategories->random()->id
        ]);

        Post::first()->comments()->saveMany(factory(Comment::class, 3)->make());
        Post::first()->comments()->saveMany(factory(Comment::class, 2)->make([
            'parent_id' => Post::first()->comments()->first()->id
        ]));

        Thread::first()->comments()->saveMany(factory(Comment::class, 30)->make());

        $conversation = Conversation::create([
            'user_one_id' => 1,
            'user_two_id' => 2
        ]);
        $conversation->messages()->create([
            'user_id' => 1,
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum tincidunt eros, egestas iaculis libero fringilla quis. Donec pulvinar, justo laoreet dapibus pulvinar, nunc libero consequat leo, sed ullamcorper nibh turpis sit amet velit. In sed diam scelerisque, luctus nisi sit amet, vestibulum ligula. In commodo dignissim nisi et consequat. Proin ac mattis mauris. Sed scelerisque id purus id'
        ]);
        $conversation->messages()->create([
            'user_id' => 2,
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum tincidunt eros, egestas iaculis libero fringilla quis. Donec pulvinar, justo laoreet dapibus pulvinar, nunc libero consequat leo, sed ullamcorper nibh turpis sit amet velit. In sed diam scelerisque, luctus nisi sit amet, vestibulum ligula. In commodo dignissim nisi et consequat. Proin ac mattis mauris. Sed scelerisque id purus id'
        ]);

        Client::create([
            'id' => 1,
            'name' => 'phpuzem-client',
            'secret' => 'BTBhnspHpS00jRtCLgYL0M4EpRR9Kxd9AvWYKPFY',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0
        ]);

        $user = User::where('email', 'user@example.com')->first();
        $startDate = $user->nextStartDate();
        $firstType = SubscriptionType::first();
        $endDate = $firstType->endDate($startDate);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_type_id' => $firstType->id,
            'next_charge_date' => $endDate->copy()->addDay()
        ]);

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'paid' => 1,
            'price' => $firstType->price,
            'conversation_id' => uniqid(),
            'payment_id' => uniqid(),
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        CreditCard::create([
            'user_id' => $user->id,
            'card_user_key' => uniqid(),
            'card_token' => uniqid(),
            'bin_number' => '123456'
        ]);

    }
}
