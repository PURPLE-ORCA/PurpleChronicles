<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            TagSeeder::class,
            PostTagSeeder::class,
            CommentSeeder::class,
        ]);
    }
}

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Author User',
                'email' => 'author@example.com',
                'password' => bcrypt('password'),
                'role' => 'author',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Technology', 'slug' => 'technology', 'parent_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Programming', 'slug' => 'programming', 'parent_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'parent_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Travel', 'slug' => 'travel', 'parent_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Health', 'slug' => 'health', 'parent_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Food', 'slug' => 'food', 'parent_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gaming', 'slug' => 'gaming', 'parent_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Books', 'slug' => 'books', 'parent_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Movies', 'slug' => 'movies', 'parent_id' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}


class PostSeeder extends Seeder
{
    public function run()
    {
        $posts = [
            [
                'user_id' => 2,
                'title' => 'The Future of Artificial Intelligence',
                'slug' => 'the-future-of-artificial-intelligence',
                'content' => 'Artificial intelligence is rapidly transforming various industries and aspects of our lives. From machine learning algorithms to sophisticated neural networks, AI is becoming an integral part of our world. This article explores the potential of AI in the future, including its impact on jobs, healthcare, and daily life.',
                 'featured_image' => 'storage/posts/feat.png',
                'category_id' => 1,
                'status' => 'published',
                'views' => 250,
            ],
            [
                'user_id' => 3,
                'title' => 'Top Programming Languages to Learn in 2024',
                'slug' => 'top-programming-languages-to-learn-in-2024',
                'content' => 'Staying up-to-date with the latest programming languages is crucial for any aspiring developer. In this article, we delve into the top programming languages to learn in 2024, including JavaScript, Python, Java, and Kotlin. Whether you are a beginner or an experienced coder, this guide provides valuable insights into the most in-demand languages of the year.',
                  'featured_image' => 'storage/posts/feat.png',
                'category_id' => 2,
                'status' => 'published',
                'views' => 180,
            ],
           [
                'user_id' => 2,
                'title' => '10 Must-Visit Travel Destinations in Europe',
                'slug' => '10-must-visit-travel-destinations-in-europe',
               'content' => 'Europe boasts a diverse range of cultures, cuisines, and landscapes, making it a popular destination for travelers. In this article, we explore 10 must-visit travel destinations in Europe, including iconic landmarks in Paris, historical sites in Rome, and natural wonders in Switzerland. Discover the beauty and charm of these European gems.',
                  'featured_image' => 'storage/posts/feat.png',
                'category_id' => 4,
                'status' => 'published',
               'views' => 300,
            ],
            [
                'user_id' => 3,
                'title' => 'Effective Strategies for Healthy Living',
                'slug' => 'effective-strategies-for-healthy-living',
                 'content' => 'Maintaining a healthy lifestyle is essential for overall well-being. This article provides effective strategies for healthy living, including proper nutrition, regular exercise, stress management, and adequate sleep. Discover how to make positive changes to improve your health and enhance your quality of life.',
                 'featured_image' => 'storage/posts/feat.png',
                 'category_id' => 5,
                'status' => 'published',
                'views' => 220,
            ],
            [
                'user_id' => 2,
                'title' => 'Delicious Recipes for Food Lovers',
                 'slug' => 'delicious-recipes-for-food-lovers',
                'content' => 'For all the food enthusiasts, this article features a collection of delicious recipes to satisfy your culinary cravings. From savory main courses to decadent desserts, these recipes are perfect for both novice and experienced cooks. Explore a variety of flavors and cuisines to tantalize your taste buds.',
                  'featured_image' => 'storage/posts/feat.png',
                 'category_id' => 6,
                'status' => 'published',
                'views' => 150,
           ],
              [
                'user_id' => 2,
                'title' => 'The Evolution of Video Games',
                'slug' => 'the-evolution-of-video-games',
                'content' => 'Video games have come a long way since their inception. This article explores the evolution of video games, from simple arcade classics to modern immersive experiences. We delve into the history, technology, and culture of gaming, and discover how video games continue to shape the entertainment landscape.',
                  'featured_image' => 'storage/posts/feat.png',
                'category_id' => 7,
                'status' => 'published',
                'views' => 350,
            ],
              [
                'user_id' => 2,
                  'title' => 'Must-Read Books for Every Book Lover',
                'slug' => 'must-read-books-for-every-book-lover',
               'content' => 'For all the avid readers out there, this article provides a curated list of must-read books from various genres. From classic literature to contemporary fiction, explore compelling stories and unforgettable characters. Expand your literary horizons with this collection of remarkable reads.',
                  'featured_image' => 'storage/posts/feat.png',
                 'category_id' => 8,
                'status' => 'published',
                 'views' => 120,
            ],
            [
               'user_id' => 3,
                 'title' => 'Top Movies of the Year',
                'slug' => 'top-movies-of-the-year',
                'content' => 'Cinema enthusiasts, this one is for you! This article highlights the top movies of the year, featuring diverse genres, compelling storylines, and remarkable performances. Discover the latest releases that have captivated audiences and critics, and plan your next movie night with our recommendations.',
                  'featured_image' => 'storage/posts/feat.png',
               'category_id' => 9,
                 'status' => 'published',
                'views' => 280,
            ],
         [
                'user_id' => 2,
                'title' => 'JavaScript Frameworks in 2024',
                 'slug' => 'javascript-frameworks-in-2024',
                'content' => 'JavaScript frameworks continue to dominate the web development landscape, and developers must stay current with the most popular ones. This article provides an analysis of JavaScript frameworks in 2024, including React, Vue, Angular, and Svelte. Discover each framework`s strengths, weaknesses, and use cases to make an informed choice for your next project.',
                  'featured_image' => 'storage/posts/feat.png',
                'category_id' => 2,
                'status' => 'published',
                'views' => 170,
            ],
          [
              'user_id' => 3,
                'title' => 'Simple Steps for Better Mental Health',
              'slug' => 'simple-steps-for-better-mental-health',
              'content' => 'Prioritizing mental health is important for overall well-being. In this article, we explore simple steps you can take to improve your mental health, including meditation, mindfulness, exercise, and social connections. Discover how these practices can promote calmness, reduce stress, and enhance your emotional resilience.',
                  'featured_image' => 'storage/posts/feat.png',
                'category_id' => 5,
               'status' => 'published',
               'views' => 200,
            ],
        ];


        foreach ($posts as $post) {
             DB::table('posts')->insert([
                  'user_id' => $post['user_id'],
                 'title' => $post['title'],
                'slug' => $post['slug'],
                  'content' => $post['content'],
                    'featured_image' => $post['featured_image'],
                 'category_id' => $post['category_id'],
               'status' => $post['status'],
                 'views' => $post['views'],
                 'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

class TagSeeder extends Seeder
{
    public function run()
    {
        DB::table('tags')->insert([
            ['name' => 'AI', 'slug' => 'ai', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Laravel', 'slug' => 'laravel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Web Development', 'slug' => 'web-development', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'React', 'slug' => 'react', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Travel Tips', 'slug' => 'travel-tips', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Healthy Eating', 'slug' => 'healthy-eating', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
              ['name' => 'JavaScript', 'slug' => 'javascript', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
               ['name' => 'Mental Health', 'slug' => 'mental-health', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
              ['name' => 'European Travel', 'slug' => 'european-travel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
               ['name' => 'Programming', 'slug' => 'programming', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                  ['name' => 'Cooking', 'slug' => 'cooking', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'Gaming', 'slug' => 'gaming', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}

class PostTagSeeder extends Seeder
{
    public function run()
    {
           $posts = DB::table('posts')->pluck('id')->toArray();
        $tags = DB::table('tags')->pluck('id')->toArray();

         foreach ($posts as $post) {
            $numTags = rand(1, 3);
            $selectedTags = array_rand(array_flip($tags), $numTags);

             foreach ((array) $selectedTags as $tag) {
                DB::table('post__tags')->insert([
                   'post_id' => $post,
                    'tag_id' => $tag,
                ]);
           }
       }
    }
}


class CommentSeeder extends Seeder
{
    public function run()
    {
        $comments = [
             [
                'user_id' => 3,
                'post_id' => 1,
               'content' => 'Great article! Very informative.',
                'status' => 'approved',
            ],
            [
                'user_id' => 2,
                'post_id' => 1,
               'content' => 'I am also very interested in this topic',
               'status' => 'approved',
             ],
             [
                'user_id' => 3,
                 'post_id' => 2,
                 'content' => 'What about Kotlin or Swift?',
                 'status' => 'pending',
            ],
             [
                'user_id' => 1,
                 'post_id' => 3,
                'content' => 'I loved to visit France and Italy',
                  'status' => 'approved',
            ],
             [
                'user_id' => 3,
                 'post_id' => 4,
                 'content' => 'I will try these recommendations',
                 'status' => 'approved',
            ],
             [
                 'user_id' => 1,
                 'post_id' => 5,
                 'content' => 'Amazing recipes!',
                 'status' => 'approved',
            ],
              [
                'user_id' => 2,
                  'post_id' => 6,
                   'content' => 'I grew up with gaming.',
                   'status' => 'approved',
            ],
             [
                 'user_id' => 3,
                 'post_id' => 7,
                 'content' => 'I love reading very much',
                   'status' => 'approved',
           ],
            [
                 'user_id' => 1,
                  'post_id' => 8,
                 'content' => 'I am excited to see the new movies.',
                 'status' => 'approved',
           ],
             [
                'user_id' => 2,
                'post_id' => 9,
                  'content' => 'I will try to learn React',
                'status' => 'approved',
           ],
          [
               'user_id' => 3,
                 'post_id' => 10,
                'content' => 'Thanks for sharing these tips.',
                 'status' => 'approved',
            ],
         ];


        foreach ($comments as $comment) {
                DB::table('comments')->insert([
                 'user_id' => $comment['user_id'],
                 'post_id' => $comment['post_id'],
                'content' => $comment['content'],
                'status' => $comment['status'],
               'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}