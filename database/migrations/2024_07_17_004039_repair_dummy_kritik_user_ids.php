<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get valid user IDs
        $users = DB::table('users')->pluck('id')->toArray();

        if (!empty($users)) {
            // Find any kritiks whose user_id is not in users table
            $invalidKritiks = DB::table('kritiks')
                ->whereNotIn('user_id', $users)
                ->pluck('id');

            if ($invalidKritiks->isNotEmpty()) {
                // Distribute them evenly among existing users
                $userCount = count($users);
                foreach ($invalidKritiks as $index => $kritikId) {
                    $assignedUserId = $users[$index % $userCount];
                    DB::table('kritiks')
                        ->where('id', $kritikId)
                        ->update(['user_id' => $assignedUserId]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
