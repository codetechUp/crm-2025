<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Contact\Models\Person;
use Webkul\Lead\Models\Lead;
use Webkul\Order\Models\Order;
use Webkul\Order\Models\OrderItem;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCategory;
use Webkul\Quote\Models\Quote;
use Webkul\Quote\Models\QuoteItem;
use Webkul\User\Models\User;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les 2 commerciaux existants (IDs 1 et 2)
        $salesPersons = User::whereIn('id', [1, 2])->get();

        if ($salesPersons->count() < 2) {
            $this->command->error('Il faut au moins 2 commerciaux avec les IDs 1 et 2 dans la base de données.');
            return;
        }

        $this->command->info('Génération de données de test...');

        // 1. Créer des produits
        $this->command->info('Création de produits...');
        $products = $this->createProducts();

        // 2. Créer des personnes (contacts)
        $this->command->info('Création de contacts...');
        $persons = $this->createPersons();

        // 3. Créer des leads (prospects)
        $this->command->info('Création de leads...');
        $leads = $this->createLeads($salesPersons, $persons);

        // 4. Créer des devis
        $this->command->info('Création de devis...');
        $quotes = $this->createQuotes($salesPersons, $persons, $products, 'devis');

        // 5. Créer des factures
        $this->command->info('Création de factures...');
        $invoices = $this->createQuotes($salesPersons, $persons, $products, 'facture');

        // 6. Créer des commandes
        $this->command->info('Création de commandes...');
        $this->createOrders($salesPersons, $persons, $products);

        // 7. Créer des dépenses
        $this->command->info('Création de dépenses...');
        $this->createExpenses($salesPersons);

        $this->command->info('✅ Données de test générées avec succès!');
    }

    /**
     * Créer des produits
     */
    private function createProducts()
    {
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $category = ProductCategory::create(['name' => 'Catégorie par défaut']);
            $categories = collect([$category]);
        }

        $products = [];
        $productNames = [
            'Ordinateur portable', 'Souris sans fil', 'Clavier mécanique', 'Écran 27 pouces',
            'Webcam HD', 'Casque audio', 'Microphone USB', 'Tablette graphique',
            'Imprimante laser', 'Scanner document', 'Disque dur externe', 'Clé USB 64GB',
            'Câble HDMI', 'Adaptateur USB-C', 'Hub USB', 'Support écran',
            'Souris gaming', 'Clavier rétroéclairé', 'Enceinte Bluetooth', 'Chargeur universel'
        ];

        foreach ($productNames as $index => $name) {
            // Générer un SKU unique en utilisant l'index et un identifiant unique
            $skuPrefix = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $name), 0, 6));
            $uniqueId = str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '-' . uniqid();
            
            $products[] = Product::create([
                'name' => $name,
                'sku' => 'SKU-' . $skuPrefix . '-' . $uniqueId,
                'description' => 'Description du produit ' . $name,
                'quantity' => rand(10, 500),
                'price' => rand(5000, 500000) / 100, // Prix entre 50€ et 5000€
                'product_category_id' => $categories->random()->id,
            ]);
        }

        return collect($products);
    }

    /**
     * Créer des personnes (contacts)
     */
    private function createPersons()
    {
        $firstNames = ['Jean', 'Marie', 'Pierre', 'Sophie', 'Thomas', 'Julie', 'Nicolas', 'Camille', 'David', 'Laura'];
        $lastNames = ['Dupont', 'Martin', 'Bernard', 'Dubois', 'Laurent', 'Moreau', 'Simon', 'Michel', 'Lefebvre', 'Garcia'];
        $emailDomains = ['example.com', 'test.fr', 'demo.com', 'sample.org', 'mail.fr', 'contact.net'];
        $emailLabels = ['work', 'personal', 'home'];

        $persons = [];
        for ($i = 0; $i < 30; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            
            // Générer 1 à 3 emails par contact
            $emailCount = rand(1, 3);
            $emails = [];
            for ($j = 0; $j < $emailCount; $j++) {
                $emailBase = strtolower(str_replace([' ', 'é', 'è', 'ê', 'à', 'ù', 'ô', 'ç'], ['', 'e', 'e', 'e', 'a', 'u', 'o', 'c'], $firstName . '.' . $lastName));
                if ($j > 0) {
                    $emailBase .= '.' . ($j + 1);
                }
                $emails[] = [
                    'value' => $emailBase . '@' . $emailDomains[array_rand($emailDomains)],
                    'label' => $emailLabels[array_rand($emailLabels)]
                ];
            }
            
            // Générer 1 à 2 numéros de téléphone
            $phoneCount = rand(1, 2);
            $contactNumbers = [];
            for ($j = 0; $j < $phoneCount; $j++) {
                $contactNumbers[] = [
                    'value' => '0' . rand(100000000, 999999999),
                    'label' => $j === 0 ? 'work' : 'mobile'
                ];
            }

            $persons[] = Person::create([
                'name' => $name,
                'emails' => $emails, // Laravel convertira automatiquement en JSON grâce au cast 'array'
                'contact_numbers' => $contactNumbers, // Laravel convertira automatiquement en JSON grâce au cast 'array'
            ]);
        }

        return collect($persons);
    }

    /**
     * Créer des leads (prospects)
     */
    private function createLeads($salesPersons, $persons)
    {
        $leadSources = DB::table('lead_sources')->pluck('id');
        $leadTypes = DB::table('lead_types')->pluck('id');
        $pipelines = DB::table('lead_pipelines')->pluck('id');
        
        if ($leadSources->isEmpty() || $leadTypes->isEmpty() || $pipelines->isEmpty()) {
            $this->command->warn('Certaines tables de configuration (sources, types, pipelines) sont vides. Les leads ne seront pas créés.');
            return collect([]);
        }

        $stages = DB::table('lead_pipeline_stages')->pluck('id');
        if ($stages->isEmpty()) {
            $this->command->warn('Aucun stage de pipeline trouvé. Les leads ne seront pas créés.');
            return collect([]);
        }

        $leads = [];
        $titles = [
            'Nouveau projet web', 'Refonte site internet', 'Application mobile', 'Solution e-commerce',
            'Système de gestion', 'Plateforme SaaS', 'Intégration API', 'Migration données',
            'Formation équipe', 'Support technique', 'Audit sécurité', 'Optimisation performance',
            'Développement sur mesure', 'Consulting IT', 'Infrastructure cloud', 'Maintenance applicative'
        ];

        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 180));
            $leadValue = rand(5000, 100000); // Entre 50€ et 1000€

            $leads[] = Lead::create([
                'title' => $titles[array_rand($titles)] . ' #' . ($i + 1),
                'description' => 'Description détaillée du projet ' . ($i + 1),
                'lead_value' => $leadValue,
                'status' => rand(0, 1),
                'user_id' => $salesPersons->random()->id,
                'person_id' => $persons->random()->id,
                'lead_source_id' => $leadSources->random(),
                'lead_type_id' => $leadTypes->random(),
                'lead_pipeline_id' => $pipelines->random(),
                'lead_pipeline_stage_id' => $stages->random(),
                'expected_close_date' => $createdAt->copy()->addDays(rand(30, 90)),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        return collect($leads);
    }

    /**
     * Créer des devis ou factures
     */
    private function createQuotes($salesPersons, $persons, $products, $type = 'devis')
    {
        $quotes = [];
        $count = $type === 'facture' ? 40 : 35;

        for ($i = 0; $i < $count; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 120));
            $person = $persons->random();
            $salesPerson = $salesPersons->random();
            
            $subTotal = 0;
            $itemsCount = rand(1, 5);
            $selectedProducts = $products->random(min($itemsCount, $products->count()));

            // Créer le devis/facture
            $quote = Quote::create([
                'subject' => ($type === 'facture' ? 'Facture' : 'Devis') . ' #' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'description' => 'Description de la ' . ($type === 'facture' ? 'facture' : 'devis'),
                'type' => $type,
                'user_id' => $salesPerson->id,
                'person_id' => $person->id,
                'sub_total' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'adjustment_amount' => 0,
                'grand_total' => 0,
                'acompte' => $type === 'facture' ? rand(0, 50000) / 100 : 0,
                'expired_at' => $type === 'devis' ? $createdAt->copy()->addDays(30) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Créer les items
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 10);
                $price = $product->price;
                $itemTotal = $quantity * $price;
                $subTotal += $itemTotal;

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                    'total' => $itemTotal,
                ]);
            }

            // Calculer les totaux
            $discountAmount = rand(0, $subTotal * 0.1); // Remise max 10%
            $taxAmount = ($subTotal - $discountAmount) * 0.20; // TVA 20%
            $grandTotal = $subTotal - $discountAmount + $taxAmount;

            $quote->update([
                'sub_total' => $subTotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
            ]);

            $quotes[] = $quote;
        }

        return collect($quotes);
    }

    /**
     * Créer des commandes
     */
    private function createOrders($salesPersons, $persons, $products)
    {
        $statuses = ['received', 'preparing', 'production', 'finishing', 'delivered', 'registered', 'processing', 'executed'];
        
        // Désactiver temporairement la génération automatique pour éviter les doublons
        Order::unsetEventDispatcher();
        
        // Compter les commandes existantes par année pour éviter les doublons
        $ordersByYear = [];
        $existingOrders = Order::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->get();
        
        foreach ($existingOrders as $order) {
            $ordersByYear[$order->year] = $order->count;
        }
        
        for ($i = 0; $i < 30; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 90));
            $person = $persons->random();
            $salesPerson = $salesPersons->random();
            $hasProduction = rand(0, 1);
            
            $subTotal = 0;
            $itemsCount = rand(1, 4);
            $selectedProducts = $products->random(min($itemsCount, $products->count()));

            // Générer un numéro de commande unique basé sur l'année de création
            $year = (int) $createdAt->format('Y');
            if (!isset($ordersByYear[$year])) {
                $ordersByYear[$year] = 0;
            }
            $ordersByYear[$year]++;
            $orderNumber = 'CMD-' . $year . '-' . str_pad($ordersByYear[$year], 5, '0', STR_PAD_LEFT);

            // Créer la commande
            $order = Order::create([
                'order_number' => $orderNumber,
                'subject' => 'Commande #' . ($i + 1),
                'description' => 'Description de la commande',
                'has_production' => $hasProduction,
                'status' => $statuses[array_rand($statuses)],
                'user_id' => $salesPerson->id,
                'person_id' => $person->id,
                'expected_delivery_date' => $createdAt->copy()->addDays(rand(15, 60)),
                'sub_total' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'grand_total' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Créer les items de commande
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 8);
                $price = $product->price;
                $itemTotal = $quantity * $price;
                $subTotal += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $itemTotal,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Calculer les totaux
            $discountAmount = rand(0, $subTotal * 0.05); // Remise max 5%
            $taxAmount = ($subTotal - $discountAmount) * 0.20; // TVA 20%
            $grandTotal = $subTotal - $discountAmount + $taxAmount;

            $order->update([
                'sub_total' => $subTotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
            ]);
        }
        
        // Réactiver les événements
        Order::setEventDispatcher(app('events'));
    }

    /**
     * Créer des dépenses
     */
    private function createExpenses($salesPersons)
    {
        $categories = ExpenseCategory::all();
        if ($categories->isEmpty()) {
            $category = ExpenseCategory::create(['name' => 'Divers']);
            $categories = collect([$category]);
        }

        $descriptions = [
            'Frais de déplacement', 'Repas d\'affaires', 'Fournitures de bureau',
            'Abonnement logiciel', 'Formation', 'Maintenance équipement',
            'Publicité', 'Télécommunications', 'Location bureau', 'Assurance'
        ];

        $statuses = ['draft', 'submitted', 'approved', 'paid', 'rejected'];

        for ($i = 0; $i < 60; $i++) {
            $date = Carbon::now()->subDays(rand(0, 90));
            $amount = rand(1000, 50000) / 100; // Entre 10€ et 500€
            $taxRate = 20;
            $taxAmount = $amount * ($taxRate / 100);

            Expense::create([
                'date' => $date,
                'amount' => $amount,
                'tax_amount' => $taxAmount,
                'tax_rate' => $taxRate,
                'description' => $descriptions[array_rand($descriptions)],
                'notes' => 'Notes sur la dépense #' . ($i + 1),
                'category_id' => $categories->random()->id,
                'user_id' => $salesPersons->random()->id,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
