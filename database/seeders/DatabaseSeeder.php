<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kamar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Hotel',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Create Guest User
        User::create([
            'name' => 'Tamu Hotel',
            'email' => 'tamu@hotel.com',
            'password' => Hash::make('tamu123'),
            'role' => 'tamu'
        ]);

        // Create Sample Rooms
        $rooms = [
            [
                'nama_kamar' => 'Kamar Standard 101',
                'tipe' => 'Standard',
                'harga' => 300000,
                'jumlah_bed' => 2,
                'deskripsi' => 'Kamar standard dengan fasilitas lengkap untuk 2 orang',
                'foto' => 'kamar/default-standard.jpg'
            ],
            [
                'nama_kamar' => 'Kamar Standard 102',
                'tipe' => 'Standard',
                'harga' => 300000,
                'jumlah_bed' => 2,
                'deskripsi' => 'Kamar standard nyaman dengan AC, TV, dan WiFi gratis',
                'foto' => 'kamar/default-standard.jpg'
            ],
            [
                'nama_kamar' => 'Kamar Standard 103',
                'tipe' => 'Standard',
                'harga' => 350000,
                'jumlah_bed' => 2,
                'deskripsi' => 'Kamar standard dengan pemandangan taman yang asri',
                'foto' => 'kamar/default-standard.jpg'
            ],
            [
                'nama_kamar' => 'Kamar Deluxe 201',
                'tipe' => 'Deluxe',
                'harga' => 500000,
                'jumlah_bed' => 2,
                'deskripsi' => 'Kamar deluxe dengan pemandangan kota dan fasilitas premium',
                'foto' => 'kamar/default-deluxe.jpg'
            ],
            [
                'nama_kamar' => 'Kamar Deluxe 202',
                'tipe' => 'Deluxe',
                'harga' => 550000,
                'jumlah_bed' => 2,
                'deskripsi' => 'Kamar deluxe luas dengan balkon pribadi dan fasilitas modern',
                'foto' => 'kamar/default-deluxe.jpg'
            ],
            [
                'nama_kamar' => 'Kamar Deluxe 203',
                'tipe' => 'Deluxe',
                'harga' => 520000,
                'jumlah_bed' => 2,
                'deskripsi' => 'Kamar deluxe elegan dengan pemandangan kolam renang',
                'foto' => 'kamar/default-deluxe.jpg'
            ],
            [
                'nama_kamar' => 'Suite 301',
                'tipe' => 'Suite',
                'harga' => 800000,
                'jumlah_bed' => 3,
                'deskripsi' => 'Suite mewah dengan ruang tamu terpisah dan balkon pribadi',
                'foto' => 'kamar/default-suite.jpg'
            ],
            [
                'nama_kamar' => 'Suite 302',
                'tipe' => 'Suite',
                'harga' => 850000,
                'jumlah_bed' => 3,
                'deskripsi' => 'Suite premium dengan 2 kamar tidur dan ruang kerja',
                'foto' => 'kamar/default-suite.jpg'
            ],
            [
                'nama_kamar' => 'Suite Executive 401',
                'tipe' => 'Suite',
                'harga' => 1200000,
                'jumlah_bed' => 4,
                'deskripsi' => 'Suite eksekutif dengan jacuzzi, pantry, dan pemandangan laut',
                'foto' => 'kamar/default-suite.jpg'
            ],
            [
                'nama_kamar' => 'Family Room 104',
                'tipe' => 'Family',
                'harga' => 600000,
                'jumlah_bed' => 4,
                'deskripsi' => 'Kamar keluarga luas untuk 4-6 orang dengan 2 kamar tidur',
                'foto' => 'kamar/default-family.jpg'
            ],
        ];

        foreach ($rooms as $room) {
            Kamar::create($room);
        }
    }
}
