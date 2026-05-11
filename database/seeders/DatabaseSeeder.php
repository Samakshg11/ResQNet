<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agency;
use App\Models\Disaster;
use App\Models\SOSRequest;
use App\Models\Resource;
use App\Models\Volunteer;
use App\Models\Alert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::create([
            'name' => 'Government Administrator',
            'email' => 'admin@resqnet.org',
            'password' => Hash::make('ResQNet@2026!'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
            'phone' => '+91-11-23456789',
        ]);

        // Government Admin
        $govAdmin = User::create([
            'name' => 'NDMA Director',
            'email' => 'ndma@resqnet.org',
            'password' => Hash::make('NDMA@2026!'),
            'role' => 'gov_admin',
            'email_verified_at' => now(),
        ]);

        // Sample Agencies
        $agencyData = [
            ['name' => 'NDRF National HQ', 'type' => 'flood_rescue', 'region' => 'Pan India', 'state' => 'Delhi', 'lat' => 28.6139, 'lng' => 77.2090],
            ['name' => 'Mumbai Red Cross', 'type' => 'medical', 'region' => 'Mumbai', 'state' => 'Maharashtra', 'lat' => 19.0760, 'lng' => 72.8777],
            ['name' => 'Delhi Fire Service', 'type' => 'fire_rescue', 'region' => 'Delhi NCR', 'state' => 'Delhi', 'lat' => 28.6328, 'lng' => 77.2197],
            ['name' => 'Bihar Ambulance Network', 'type' => 'ambulance', 'region' => 'Bihar', 'state' => 'Bihar', 'lat' => 25.6093, 'lng' => 85.1376],
            ['name' => 'Assam Police Special Unit', 'type' => 'police', 'region' => 'Assam', 'state' => 'Assam', 'lat' => 26.1445, 'lng' => 91.7362],
            ['name' => 'Kerala NGO Coalition', 'type' => 'ngo', 'region' => 'Kerala', 'state' => 'Kerala', 'lat' => 10.8505, 'lng' => 76.2711],
            ['name' => 'Gujarat Flood Response', 'type' => 'flood_rescue', 'region' => 'Gujarat', 'state' => 'Gujarat', 'lat' => 23.0225, 'lng' => 72.5714],
            ['name' => 'Tamil Nadu Civil Defense', 'type' => 'civil_defense', 'region' => 'Tamil Nadu', 'state' => 'Tamil Nadu', 'lat' => 13.0827, 'lng' => 80.2707],
            ['name' => 'Odisha Food Supply Corps', 'type' => 'food_supply', 'region' => 'Odisha', 'state' => 'Odisha', 'lat' => 20.2961, 'lng' => 85.8245],
        ];

        $agencies = [];
        foreach ($agencyData as $data) {
            $user = User::create([
                'name' => $data['name'] . ' Admin',
                'email' => strtolower(str_replace(' ', '.', $data['name'])) . '@resqnet.org',
                'password' => Hash::make('Agency@2026!'),
                'role' => 'agency_admin',
                'email_verified_at' => now(),
            ]);

            $agencies[] = Agency::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'registration_number' => 'REG-' . strtoupper(substr(md5($data['name']), 0, 8)),
                'type' => $data['type'],
                'description' => 'Government registered rescue and disaster relief agency operating in ' . $data['region'],
                'contact_email' => $user->email,
                'contact_phone' => '+91-' . rand(9000000000, 9999999999),
                'address' => 'Main Office, ' . $data['state'],
                'region' => $data['region'],
                'state' => $data['state'],
                'country' => 'IND',
                'latitude' => $data['lat'],
                'longitude' => $data['lng'],
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'total_teams' => rand(5, 50),
                'total_volunteers' => rand(20, 200),
                'rescue_success_rate' => rand(75, 98) + rand(0, 99) / 100,
            ]);
        }

        // Active Disasters
        $disasters = [
            Disaster::create([
                'title' => 'Cyclone Biparjoy — Gujarat Coast',
                'description' => 'Category 4 cyclone making landfall. 2.3 million affected across 12 districts. Heavy rainfall and storm surge expected.',
                'type' => 'cyclone',
                'severity' => 'critical',
                'status' => 'active',
                'epicenter_lat' => 23.02,
                'epicenter_lng' => 72.57,
                'radius_km' => 150,
                'estimated_affected' => 2300000,
                'confirmed_casualties' => 42,
                'rescued_count' => 1847,
                'created_by' => $admin->id,
                'started_at' => now()->subHours(8),
            ]),
            Disaster::create([
                'title' => 'Flash Floods — Assam Brahmaputra Basin',
                'description' => 'Severe flooding in 6 districts along the Brahmaputra river. Multiple villages submerged, roads cut off.',
                'type' => 'flood',
                'severity' => 'high',
                'status' => 'active',
                'epicenter_lat' => 26.14,
                'epicenter_lng' => 91.74,
                'radius_km' => 200,
                'estimated_affected' => 890000,
                'confirmed_casualties' => 12,
                'rescued_count' => 3421,
                'created_by' => $admin->id,
                'started_at' => now()->subDays(2),
            ]),
            Disaster::create([
                'title' => 'Industrial Fire — Mumbai Docks',
                'description' => 'Major fire at commercial warehouse complex near Mumbai port. Chemical materials involved.',
                'type' => 'fire',
                'severity' => 'medium',
                'status' => 'contained',
                'epicenter_lat' => 19.08,
                'epicenter_lng' => 72.88,
                'radius_km' => 5,
                'estimated_affected' => 12000,
                'confirmed_casualties' => 3,
                'rescued_count' => 156,
                'created_by' => $govAdmin->id,
                'started_at' => now()->subDays(4),
                'contained_at' => now()->subDays(3),
            ]),
            Disaster::create([
                'title' => 'Earthquake — Bihar-Nepal Border',
                'description' => 'Magnitude 5.8 earthquake along the Bihar-Nepal border. Aftershocks reported.',
                'type' => 'earthquake',
                'severity' => 'high',
                'status' => 'active',
                'epicenter_lat' => 26.65,
                'epicenter_lng' => 85.28,
                'radius_km' => 80,
                'estimated_affected' => 450000,
                'confirmed_casualties' => 8,
                'rescued_count' => 623,
                'created_by' => $admin->id,
                'started_at' => now()->subHours(12),
            ]),
        ];

        // SOS Requests
        $sosTypes = ['flood_rescue', 'medical', 'evacuation', 'fire', 'food', 'shelter', 'other'];
        $severities = ['critical', 'high', 'medium', 'low'];
        $statuses = ['pending', 'assigned', 'dispatched', 'en_route', 'resolved'];

        for ($i = 0; $i < 35; $i++) {
            $status = $statuses[array_rand($statuses)];
            SOSRequest::create([
                'user_id' => $admin->id,
                'disaster_id' => $disasters[array_rand($disasters)]->id,
                'victim_name' => fake()->name(),
                'victim_phone' => '+91-' . rand(9000000000, 9999999999),
                'victim_count' => rand(1, 15),
                'latitude' => rand(8, 35) + rand(0, 999999) / 1000000,
                'longitude' => rand(68, 97) + rand(0, 999999) / 1000000,
                'address' => fake()->address(),
                'message' => fake()->sentence(rand(5, 15)),
                'severity' => $severities[array_rand($severities)],
                'type' => $sosTypes[array_rand($sosTypes)],
                'status' => $status,
                'assigned_agency_id' => $status !== 'pending' ? $agencies[array_rand($agencies)]->id : null,
                'assigned_at' => $status !== 'pending' ? now()->subMinutes(rand(5, 120)) : null,
                'resolved_at' => $status === 'resolved' ? now()->subMinutes(rand(1, 30)) : null,
                'response_time_minutes' => $status === 'resolved' ? rand(5, 90) : null,
            ]);
        }

        // Resources
        $resourceData = [
            ['name' => 'Emergency Food Packets', 'category' => 'food', 'unit' => 'packets'],
            ['name' => 'First Aid Kits', 'category' => 'medical_kit', 'unit' => 'kits'],
            ['name' => 'Rescue Boats', 'category' => 'boat', 'unit' => 'boats'],
            ['name' => 'Ambulances', 'category' => 'vehicle', 'unit' => 'vehicles'],
            ['name' => 'Search & Rescue Team', 'category' => 'rescue_team', 'unit' => 'teams'],
            ['name' => 'Diesel Fuel', 'category' => 'fuel', 'unit' => 'liters'],
            ['name' => 'Emergency Shelter Kits', 'category' => 'shelter_kit', 'unit' => 'kits'],
            ['name' => 'Satellite Phones', 'category' => 'communication', 'unit' => 'units'],
            ['name' => 'JCB Excavators', 'category' => 'heavy_equipment', 'unit' => 'units'],
            ['name' => 'Water Purification Tablets', 'category' => 'medical_kit', 'unit' => 'boxes'],
        ];

        foreach ($agencies as $agency) {
            $count = rand(3, 6);
            $selected = array_rand($resourceData, $count);
            foreach ((array)$selected as $idx) {
                $total = rand(10, 500);
                $deployed = rand(0, (int)($total * 0.6));
                $available = $total - $deployed;
                Resource::create([
                    'agency_id' => $agency->id,
                    'name' => $resourceData[$idx]['name'],
                    'category' => $resourceData[$idx]['category'],
                    'total_quantity' => $total,
                    'available_quantity' => $available,
                    'deployed_quantity' => $deployed,
                    'unit' => $resourceData[$idx]['unit'],
                    'minimum_threshold' => rand(5, 20),
                    'status' => $available > 0 ? 'available' : 'depleted',
                ]);
            }
        }

        // Volunteers
        $skills = ['First Aid', 'CPR', 'Swimming', 'Driving', 'Communication', 'Navigation', 'Rescue Operations', 'Medical Assistance', 'Crowd Management', 'Logistics'];
        $availabilities = ['available', 'on_task', 'off_duty'];

        for ($i = 0; $i < 25; $i++) {
            $volUser = User::create([
                'name' => fake()->name(),
                'email' => 'volunteer' . ($i + 1) . '@resqnet.org',
                'password' => Hash::make('Vol@2026!'),
                'role' => 'volunteer',
                'email_verified_at' => now(),
                'phone' => '+91-' . rand(9000000000, 9999999999),
            ]);

            Volunteer::create([
                'user_id' => $volUser->id,
                'agency_id' => $agencies[array_rand($agencies)]->id,
                'skills' => array_values(array_intersect_key($skills, array_flip(array_rand($skills, rand(2, 5))))),
                'languages' => ['Hindi', 'English'],
                'bio' => fake()->sentence(10),
                'availability' => $availabilities[array_rand($availabilities)],
                'current_lat' => rand(8, 35) + rand(0, 999999) / 1000000,
                'current_lng' => rand(68, 97) + rand(0, 999999) / 1000000,
                'total_missions' => rand(0, 50),
                'rating' => rand(350, 500) / 100,
            ]);
        }

        // Alerts
        $alertData = [
            ['title' => 'CRITICAL: Cyclone Biparjoy Landfall Imminent', 'type' => 'emergency', 'message' => 'All coastal agencies activate emergency protocols immediately. Category 4 cyclone expected to make landfall within 6 hours.'],
            ['title' => 'Flood Warning: Brahmaputra Rising', 'type' => 'warning', 'message' => 'Water levels in Brahmaputra approaching danger mark. All agencies in Assam to be on standby.'],
            ['title' => 'Resource Deployment Advisory', 'type' => 'advisory', 'message' => 'Additional rescue boats and food supplies being dispatched to Gujarat and Assam regions.'],
            ['title' => 'System Update: New Agencies Verified', 'type' => 'info', 'message' => '3 new rescue agencies have been verified and are now active on the platform.'],
            ['title' => 'URGENT: Earthquake Aftershock Alert', 'type' => 'emergency', 'message' => 'Strong aftershocks expected in Bihar-Nepal border region. All rescue teams maintain safety protocols.'],
        ];

        foreach ($alertData as $alert) {
            Alert::create([
                ...$alert,
                'created_by' => $admin->id,
                'scope' => 'all',
                'delivery_channels' => ['web', 'sms', 'email'],
                'recipients_count' => rand(50, 200),
                'acknowledged_count' => rand(10, 50),
            ]);
        }
    }
}
