<?php

namespace Database\Seeders;

use App\Models\Information\InformationIcon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformationIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('information_icon')->truncate();

        $infos = [
            ['name' => "Daily Tips", 'information' => "<p>Every day brings a new opportunity for growth and optimization. Check your daily tip to find personalized advice tailored to optimize your day's potential. These insights are based on your latest assessment results and are here to help you navigate the day with energy and purpose.</p>"],
            ['name' => "Core Stats", 'information' => "<p>Discover Your Core Stats! Take some time to explore your Core Stats — your personalized blueprint that reveals your unique traits, natural motivating forces, boundaries of tolerance, communication style, perception of life and daily physical energy levels. These stats reveal the uniqueness of your natural performance potential. By aligning with your core stats, you’ll learn how to stay in alignment with your true nature and unlock greater ease and fulfillment as you move forward. Click on each component of your stats to learn more about this aspect in you.</p>"],
            ['name' => "90-Day Optimization Plan", 'information' => "<p>Your HumanOp assessment results provide specific insights into your natural needs and motivations. Your unique 90-Day Optimization Plan reveals the precise strategies, related to your latest results, that you can apply to improve your performance in business, at home and in life.</p>"],
            ['name' => "14-Day Optimization Plan", 'information' => "<p>Your HumanOp assessment results provide specific insights into your natural needs and motivations. Your unique 90-Day Optimization Plan reveals the precise strategies, related to your latest results, that you can apply to improve your performance in business, at home and in life.</p>"],
            ['name' => "Tools & Trainings", 'information' => "<p>Explore our rich library of resources and trainings! Here, you'll find insightful articles, practical guides, video trainings, and more to support your journey of self-discovery and optimization. Whatever your self-optimization goals, our resources are here to assist you in reaching them.</p>"],
            ['name' => "Just For You", 'information' => "<p>Get personalized support and guidance whenever you need it.</p>"],
            ['name' => "What's Next?", 'information' => "<p>Get personalized support and guidance whenever you need it.</p>"],
            ['name' => "Your Assessment Core Stats", 'information' => "<p>Review your main HumanOp assessment stats and insights.</p>"],
            ['name' => "Display Mode", 'information' => "<p>Adjust how your information and profile are displayed.</p>"],
            ['name' => "Announcements & News", 'information' => "<p>Stay updated with the latest news and announcements.</p>"],
            ['name' => "HAi Chat", 'information' => "<p>Got questions? HAi is here to help! Ask any question about your assessment, daily tips, or any other queries you might have. HAi is your personal guide, ready to assist you in navigating your HumanOp experience.</p>"],
            ['name' => "Your HumanOp Profile Overview", 'information' => "<p>This is where you can access your explanation of your unique HumanOp Assessment Results!</p>"],
            ['name' => "HumanOp Integration Radio", 'information' => "<p>This is where you can listen to the integration podcast to deepen your understanding of how to use your results to change your life!</p>"],
            ['name' => "Hello, I'm HAi® Ask About Your Results!", 'information' => "<p>This is where you can listen to the integration podcast to deepen your understanding of how to use your results to change your life!</p>"],
            ['name' => "Matching Connection", 'information' => "<p>Find people who match your traits and interests.</p>"],
            ['name' => "Find & Connect", 'information' => "<p>Search and connect with others in the community.</p>"],
            ['name' => "Your Connection", 'information' => "<p>View and manage your current connections.</p>"],
            ['name' => "Your Connection Requests", 'information' => "<p>Check and respond to your connection requests.</p>"],
            ['name' => "Followers", 'information' => "<p>See who is following you.</p>"],
            ['name' => "Following", 'information' => "<p>View the people you are following.</p>"],
            ['name' => "HumanOp Points", 'information' => "<p>Track your points earned from activities and progress.</p>"],
            ['name' => "First Assessment Video Results", 'information' => "<p>Watch your initial assessment video summary.</p>"],
            ['name' => "Daily Optimization Tip", 'information' => "<p>Check a quick tip to improve your day.</p>"],
            ['name' => "Hello, I'm HAi®! Chat Challenge", 'information' => "<p>Join HAi chat challenges to improve your results.</p>"],
            ['name' => "Complete Streaks", 'information' => "<p>Track your completed streaks and consistency.</p>"],
            ['name' => "Completed Challenges", 'information' => "<p>See the challenges you have successfully completed.</p>"],
            ['name' => "Completed Daily Tasks", 'information' => "<p>Review your finished daily tasks.</p>"],
            ['name' => "Total Log Ins", 'information' => "<p>View how many times you have logged in.</p>"],
            ['name' => "HP Points Earned", 'information' => "<p>See how many HumanOp points you’ve collected.</p>"],
            ['name' => "Badges & Rewards", 'information' => "<p>Check the badges and rewards you’ve unlocked.</p>"],
            ['name' => "Tier Level", 'information' => "<p>See your current tier level based on progress.</p>"],
            ];

        DB::transaction(function()use ($infos){

            foreach($infos as $info){

                DB::table("information_icon")->insert($info);

            }

        });

    }
}
