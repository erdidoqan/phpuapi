<?php

use App\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $student1 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been indus Lorem Ipsum is simply dummy text of the printing and typese gone with it'
        ]);

        $faker = \Faker\Factory::create();
        $student1->addMediaFromUrl($faker->image)->toMediaCollection();

        $student2 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been indus Lorem Ipsum is simply dummy text of the printing and typese gone with it'
        ]);
        $student2->addMediaFromUrl($faker->image)->toMediaCollection();

        $student3 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been indus Lorem Ipsum is simply dummy text of the printing and typese gone with it'
        ]);
        $student3->addMediaFromUrl($faker->image)->toMediaCollection();

        $student4 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student4->addMediaFromUrl($faker->image)->toMediaCollection();

        $student5 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student5->addMediaFromUrl($faker->image)->toMediaCollection();

        $student6 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student6->addMediaFromUrl($faker->image)->toMediaCollection();

        $student7 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student7->addMediaFromUrl($faker->image)->toMediaCollection();

        $student8 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student8->addMediaFromUrl($faker->image)->toMediaCollection();

        $student9 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student9->addMediaFromUrl($faker->image)->toMediaCollection();

        $student10 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student10->addMediaFromUrl($faker->image)->toMediaCollection();

        $student11 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student11->addMediaFromUrl($faker->image)->toMediaCollection();

        $student12 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student12->addMediaFromUrl($faker->image)->toMediaCollection();

        $student13 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student13->addMediaFromUrl($faker->image)->toMediaCollection();

        $student14 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student14->addMediaFromUrl($faker->image)->toMediaCollection();

        $student15 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student15->addMediaFromUrl($faker->image)->toMediaCollection();

        $student16 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student16->addMediaFromUrl($faker->image)->toMediaCollection();

        $student17 = Student::create([
            'name' => 'Berkay Erdinc',
            'title' => 'UI, UX Specialist, berkayerdinc.com',
            'email' => 'test@berkayerdinc.com',
            'twitter_url' => 'https://twitter.com/ErdiDoqan',
            'description' => null
        ]);
        $student17->addMediaFromUrl($faker->image)->toMediaCollection();
    }
}
