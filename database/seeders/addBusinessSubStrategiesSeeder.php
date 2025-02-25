<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class addBusinessSubStrategiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $businessSubStrategies = [

            // ========================== Retail & E-Commerce ========================= //
            ['business_strategy_id' => 1, 'name' => 'Clothing & Apparel','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Electronics & Gadgets','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Beauty & Personal Care','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Furniture & Home Decor','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Grocery & Supermarkets','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Pet Supplies','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Jewelry & Accessories','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Books & Stationery','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Automotive Parts & Accessories','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 1, 'name' => 'Sporting Goods','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


            // ========================== Food & Beverage ========================= //
            ['business_strategy_id' => 2, 'name' => 'Restaurants & Cafés','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Food Delivery Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Bakery & Confectionery','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Catering Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Organic & Health Foods','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Coffee & Tea Shops','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Beverage & Alcohol Distribution','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Specialty Food Stores','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Meal Prep & Subscription Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 2, 'name' => 'Food Trucks','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


            // ========================== Health & Wellness ========================= //
            ['business_strategy_id' => 3, 'name' => 'Fitness Centers & Gyms','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Yoga & Meditation Studios','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Health Supplements & Nutrition','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Mental Health Counseling','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Personal Training & Coaching','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Beauty Salons & Spas','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Alternative Medicine (Ayurveda, Homeopathy)','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Medical & Dental Clinics','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Physical Therapy & Rehabilitation','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 3, 'name' => 'Skincare & Dermatology','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


            // ========================== Technology & IT Services ========================= //
            ['business_strategy_id' => 4, 'name' => 'Software Development','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'Mobile App Development','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'Web Design & Development','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'IT Consulting & Support','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'Digital Marketing Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'Cybersecurity Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'Cloud Computing & Hosting','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'AI & Machine Learning Solutions','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'Data Analytics & Business Intelligence','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 4, 'name' => 'Blockchain & Cryptocurrency Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],



            // ========================== Education & Training ========================= //
            ['business_strategy_id' => 5, 'name' => 'Online Courses & E-Learning','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Tutoring & Test Preparation','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Language Schools','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Skill Development Workshops','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Professional Certifications','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'STEM Education for Kids','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Corporate Training Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Arts & Music Schools','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Driving Schools','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 5, 'name' => 'Special Education Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],



            // ========================== Real Estate & Property Management ========================= //
            ['business_strategy_id' => 6, 'name' => 'Residential Real Estate','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Commercial Real Estate','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Property Management Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Interior Design & Home Staging','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Vacation Rentals & Airbnb Management','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Real Estate Investment & Brokerage','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Moving & Relocation Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Home Inspection Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Real Estate Appraisal','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 6, 'name' => 'Construction & Renovation','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],



            // ========================== Finance & Legal Services ========================= //
            ['business_strategy_id' => 7, 'name' => 'Accounting & Bookkeeping','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Tax Consultancy','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Financial Advisory & Wealth Management','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Insurance Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Legal Consulting & Law Firms','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Debt Recovery & Credit Repair','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Mortgage & Loan Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Business Consulting & Strategy','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Investment & Stock Trading Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 7, 'name' => 'Payroll Processing','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],



            // ========================== Manufacturing & Industrial ========================= //
            ['business_strategy_id' => 8, 'name' => 'Textile & Apparel Manufacturing','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Automotive Manufacturing','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Pharmaceutical Production','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Chemical Manufacturing','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Construction Materials Manufacturing','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Food Processing & Packaging','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Renewable Energy & Solar Solutions','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Aerospace & Defense Manufacturing','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Printing & Packaging Solutions','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 8, 'name' => 'Robotics & Automation','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


            // ========================== Creative & Media ========================= //
            ['business_strategy_id' => 9, 'name' => 'Graphic Design & Branding','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Photography & Videography','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Content Writing & Blogging','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Social Media Management','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Podcasting & Streaming Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Film & Video Production','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Event Planning & Wedding Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Animation & Visual Effects','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Advertising & Public Relations','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 9, 'name' => 'Interior & Exterior Design','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


            // ========================== Travel & Hospitality ========================= //
            ['business_strategy_id' => 10, 'name' => 'Travel Agencies & Tour Operators','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Hotel & Resort Management','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Car Rentals & Chauffeur Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Adventure & Outdoor Activities','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Cruise & Luxury Travel Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Business Travel & Corporate Retreats','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Eco-Tourism & Sustainable Travel','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Visa & Immigration Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Cultural & Heritage Tourism','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['business_strategy_id' => 10, 'name' => 'Airline & Transportation Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ];

        DB::table('business_sub_strategies')->insert($businessSubStrategies);
    }
}
