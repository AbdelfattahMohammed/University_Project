<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room; // تأكد من استيراد الموديل

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            'بلازا خارجيه',
            'بلازا داخليه',
            'السيمينار',
            'القاعه الجديدة',
            'سيسكو',
            'قاعه 1',
            'قاعه 2',
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['room_name' => $room]);
        }
    }
}
