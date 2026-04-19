<?php

namespace Database\Seeders;

use App\Models\Disposisi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'password' => 'password',
            ]
        );

        foreach ([
            ['username' => 'staf', 'name' => 'Staf', 'email' => 'staf@gmail.com', 'role' => 'staf'],
            ['username' => 'asmin', 'name' => 'Asmin', 'email' => 'asmin@gmail.com', 'role' => 'asmin'],
            ['username' => 'asops', 'name' => 'Asops', 'email' => 'asops@gmail.com', 'role' => 'asops'],
            ['username' => 'kasatker', 'name' => 'Kasatker', 'email' => 'kasatker@gmail.com', 'role' => 'kasatker'],
            ['username' => 'kaskogartap', 'name' => 'Kaskogartap', 'email' => 'kaskogartap@gmail.com', 'role' => 'kaskogartap'],
        ] as $data) {
            User::query()->firstOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'password' => 'password',
                ]
            );
        }

        $creator = User::query()->where('username', 'staf')->first() ?? User::query()->first();
        $creatorId = $creator?->id;

        if ($creatorId) {
            $kaskogartap = User::query()->where('username', 'kaskogartap')->first();
            $kasatker = User::query()->where('username', 'kasatker')->first();
            $asmin = User::query()->where('username', 'asmin')->first();
            $asops = User::query()->where('username', 'asops')->first();

            for ($i = 1; $i <= 5; $i++) {
                $suratMasuk = SuratMasuk::query()->firstOrCreate(
                    ['nomor_surat' => sprintf('SM-2026-%03d', $i)],
                    [
                        'user_id' => $creatorId,
                        'tanggal_surat' => now()->subDays(6 - $i)->toDateString(),
                        'tanggal_terima' => now()->subDays(6 - $i)->toDateString(),
                        'pengirim' => 'Instansi '.$i,
                        'jenis_surat' => 'Dinas',
                        'perihal' => 'Perihal surat masuk '.$i,
                        'file_surat' => null,
                        'barcode' => (string) Str::uuid(),
                        'status' => 'diproses',
                    ]
                );

                SuratKeluar::query()->firstOrCreate(
                    ['nomor_surat' => sprintf('SK-2026-%03d', $i)],
                    [
                        'user_id' => $creatorId,
                        'tanggal_surat' => now()->subDays(6 - $i)->toDateString(),
                        'tujuan' => 'Tujuan '.$i,
                        'jenis_surat' => 'Dinas',
                        'perihal' => 'Perihal surat keluar '.$i,
                        'file_surat' => null,
                        'barcode' => (string) Str::uuid(),
                    ]
                );

                $recipientId = match ($i) {
                    1, 4 => $kasatker?->id,
                    2 => $asmin?->id,
                    default => $asops?->id,
                };

                if ($kaskogartap?->id && $recipientId) {
                    Disposisi::query()->updateOrCreate(
                        [
                            'surat_masuk_id' => $suratMasuk->id,
                            'dari_user_id' => $kaskogartap->id,
                            'ke_user_id' => $recipientId,
                        ],
                        [
                            'instruksi' => 'Mohon ditindaklanjuti (seed).',
                            'tanggal_disposisi' => now()->subDays(6 - $i)->toDateString(),
                            'status' => 'selesai',
                        ]
                    );

                    $suratMasuk->update([
                        'status' => 'selesai',
                    ]);
                }
            }
        }
    }
}
