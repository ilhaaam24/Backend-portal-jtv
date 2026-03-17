<?php

namespace Database\Seeders;

use App\Models\Navigation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        



        Navigation::create([
            'name' => 'Konten',
            'url' => 'konten',
            'icon' => 'mdi-newspaper',
            'main_menu' => null,
        ]);

        Navigation::create([
            'name' => 'Master',
            'url' => 'master',
            'icon' => 'mdi-folder-network',
            'main_menu' => null,
        ]);

        Navigation::create([
            'name' => 'Layout',
            'url' => 'Layout',
            'icon' => 'mdi-palette-advanced',
            'main_menu' => null,
        ]);
        
        Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'mdi-cog-outline',
            'main_menu' => null,
        ]);

        Navigation::create([
            'name' => 'Berita',
            'url' => 'konten/berita',
            'icon' => '',
            'main_menu' => 1,
        ]);

        Navigation::create([
            'name' => 'Jurnalisme Warga',
            'url' => 'konten/jurnalisme_warga',
            'icon' => '',
            'main_menu' => 1,
        ]);

        Navigation::create([
            'name' => 'Iklan',
            'url' => 'konten/iklan',
            'icon' => '',
            'main_menu' => 1,
        ]);

        Navigation::create([
            'name' => 'Curhat Warga',      // Nama yang muncul di menu
            'url' => 'konten/curhatan',    // URL sesuai routes/web.php Anda
            'icon' => 'mdi-comment-text-outline', // Ikon (opsional, cari di dokumentasi MDI)
            'main_menu' => 1,              // Masuk ke dalam folder "Konten"
        ]);

        Navigation::create([
            'name' => 'Siaran langsung',
            'url' => 'konten/siaran_langsung',
            'icon' => '',
            'main_menu' => 1,
        ]);

        Navigation::create([
            'name' => 'Video',
            'url' => 'konten/video',
            'icon' => '',
            'main_menu' => 1,
        ]);

        Navigation::create([
            'name' => 'Instagram Api',
            'url' => 'konten/instagram_api',
            'icon' => '',
            'main_menu' => 1,
        ]);

        Navigation::create([
            'name' => 'Pengguna',
            'url' => 'master/pengguna',
            'icon' => '',
            'main_menu' => 2,
        ]);

        Navigation::create([
            'name' => 'Penulis',
            'url' => 'master/penulis',
            'icon' => '',
            'main_menu' => 2,
        ]);


        Navigation::create([
            'name' => 'Logo',
            'url' => 'konfigurasi/logo',
            'icon' => '',
            'main_menu' => 3,
        ]);

      
        Navigation::create([
            'name' => 'Kategori',
            'url' => 'konfigurasi/logo',
            'icon' => '',
            'main_menu' => 3,
        ]);

        Navigation::create([
            'name' => 'Menu Bawah',
            'url' => 'konfigurasi/menu_bawah',
            'icon' => '',
            'main_menu' => 3,
        ]);
      
        Navigation::create([
            'name' => 'Footer',
            'url' => 'konfigurasi/footer',
            'icon' => '',
            'main_menu' => 3,
        ]);

     
        Navigation::create([
            'name' => 'Menu',
            'url' => 'konfigurasi/menu',
            'icon' => '',
            'main_menu' => 4,
        ]);

        Navigation::create([
            'name' => 'Roles',
            'url' => 'konfigurasi/roles',
            'icon' => '',
            'main_menu' => 4,
        ]);

        Navigation::create([
            'name' => 'Permission',
            'url' => 'konfigurasi/permission',
            'icon' => '',
            'main_menu' => 4,
        ]);

     


       
    }
}
