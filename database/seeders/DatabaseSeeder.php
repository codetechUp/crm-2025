<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as KrayinDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(KrayinDatabaseSeeder::class);
        
        // Ajouter des données de test (optionnel)
        if ($this->command->confirm('Voulez-vous générer des données de test (leads, factures, devis, commandes, dépenses)?', false)) {
            $this->call(FakeDataSeeder::class);
        }
    }
}
