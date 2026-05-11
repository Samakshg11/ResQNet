<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify existing users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'gov_admin', 'agency_admin', 'volunteer', 'victim'])->default('victim')->after('password');
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->string('preferred_language', 10)->default('en')->after('is_active');
            $table->decimal('last_lat', 10, 7)->nullable()->after('preferred_language');
            $table->decimal('last_lng', 10, 7)->nullable()->after('last_lat');
            $table->timestamp('last_seen_at')->nullable()->after('last_lng');
            $table->softDeletes();
        });

        // Agencies
        Schema::create('agencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->enum('type', [
                'medical', 'fire_rescue', 'flood_rescue',
                'food_supply', 'police', 'ngo', 'ambulance', 'civil_defense'
            ]);
            $table->text('description')->nullable();
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->text('address');
            $table->string('region');
            $table->string('state');
            $table->string('country', 3)->default('IND');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['pending', 'verified', 'suspended', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->json('specializations')->nullable();
            $table->integer('total_teams')->default(0);
            $table->integer('total_volunteers')->default(0);
            $table->decimal('rescue_success_rate', 5, 2)->default(0);
            $table->boolean('is_deployed')->default(false);
            $table->string('logo')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Disasters
        Schema::create('disasters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->enum('type', [
                'flood', 'earthquake', 'cyclone', 'fire',
                'landslide', 'tsunami', 'drought', 'industrial', 'other'
            ]);
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['monitoring', 'active', 'contained', 'resolved'])->default('active');
            $table->decimal('epicenter_lat', 10, 7)->nullable();
            $table->decimal('epicenter_lng', 10, 7)->nullable();
            $table->decimal('radius_km', 8, 2)->nullable();
            $table->json('affected_zones')->nullable();
            $table->integer('estimated_affected')->default(0);
            $table->integer('confirmed_casualties')->default(0);
            $table->integer('rescued_count')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('contained_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // SOS Requests
        Schema::create('sos_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->uuid('disaster_id')->nullable();
            $table->string('victim_name')->nullable();
            $table->string('victim_phone')->nullable();
            $table->integer('victim_count')->default(1);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('address')->nullable();
            $table->text('message')->nullable();
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('high');
            $table->enum('type', ['flood_rescue', 'medical', 'evacuation', 'fire', 'food', 'shelter', 'other']);
            $table->enum('status', ['pending', 'assigned', 'dispatched', 'en_route', 'resolved', 'cancelled'])->default('pending');
            $table->uuid('assigned_agency_id')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->integer('response_time_minutes')->nullable();
            $table->json('media')->nullable();
            $table->timestamps();
        });

        // Resources
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('agency_id');
            $table->string('name');
            $table->enum('category', [
                'food', 'medical_kit', 'vehicle', 'boat',
                'rescue_team', 'fuel', 'shelter_kit', 'communication',
                'heavy_equipment', 'other'
            ]);
            $table->integer('total_quantity');
            $table->integer('available_quantity');
            $table->integer('deployed_quantity')->default(0);
            $table->string('unit')->default('units');
            $table->integer('minimum_threshold')->default(0);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->enum('status', ['available', 'deployed', 'maintenance', 'depleted']);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
        });

        // Volunteers
        Schema::create('volunteers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('agency_id')->nullable();
            $table->string('national_id')->nullable();
            $table->json('skills')->nullable();
            $table->json('certifications')->nullable();
            $table->json('languages')->nullable();
            $table->text('bio')->nullable();
            $table->enum('availability', ['available', 'on_task', 'in_transit', 'off_duty', 'unavailable'])->default('available');
            $table->decimal('current_lat', 10, 7)->nullable();
            $table->decimal('current_lng', 10, 7)->nullable();
            $table->uuid('current_task_id')->nullable();
            $table->integer('total_missions')->default(0);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->json('emergency_contact')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('agency_id');
            $table->uuid('disaster_id')->nullable();
            $table->uuid('sos_request_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['situation', 'medical', 'rescue', 'logistics', 'damage_assessment', 'post_disaster']);
            $table->enum('ai_priority', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->decimal('ai_confidence', 5, 4)->default(0);
            $table->json('ai_tags')->nullable();
            $table->json('media')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['pending', 'under_review', 'verified', 'rejected'])->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
        });

        // Chat Messages
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('sender_id');
            $table->string('channel_id');
            $table->enum('channel_type', ['disaster_channel', 'agency_direct', 'broadcast']);
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->json('read_by')->nullable();
            $table->boolean('is_alert')->default(false);
            $table->enum('priority', ['normal', 'urgent', 'critical'])->default('normal');
            $table->timestamps();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Alerts
        Schema::create('alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('created_by');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['emergency', 'warning', 'advisory', 'info']);
            $table->enum('scope', ['all', 'agency_type', 'region', 'specific_agencies']);
            $table->json('target_agencies')->nullable();
            $table->json('target_regions')->nullable();
            $table->json('delivery_channels')->nullable();
            $table->integer('recipients_count')->default(0);
            $table->integer('acknowledged_count')->default(0);
            $table->uuid('disaster_id')->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        // Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->string('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('volunteers');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('sos_requests');
        Schema::dropIfExists('disasters');
        Schema::dropIfExists('agencies');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'avatar', 'is_active', 'preferred_language', 'last_lat', 'last_lng', 'last_seen_at']);
            $table->dropSoftDeletes();
        });
    }
};
