<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('type')->default('admin');
            $table->string('job_title')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(0)->nullable();
            $table->tinyInteger('notification_status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('admins')->insert([
            'name' => 'User Admin',
            'email' => 'admin@admin.com',
            'job_title' => 'Administrator',
            'phone' => '0597777777',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'type' => 'admin',
            'email_verified_at' => now(),
            'status' => 1,
        ]);

        Schema::create('password_admin_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('password_reset_tokens');
    }
}
