<?php

use App\Core\Models\Messenger;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Messenger::create([
            'name' => 'vk',
            'active' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Messenger::where('name', 'vk')->delete();
    }
};
