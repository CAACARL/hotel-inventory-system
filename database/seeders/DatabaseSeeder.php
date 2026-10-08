<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Models\Category;
use App\Models\Item;
use App\Models\Batch;
use App\Models\Transaction;
use App\Models\BorrowedItem;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏨 Seeding Icon Venue & Suites Inventory System...');

        // 1. Users
        $this->command->info('👥 Creating users...');
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@iconvenue.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $staff1 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@iconvenue.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        $staff2 = User::create([
            'name' => 'John Reyes',
            'email' => 'john@iconvenue.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        // 2. Departments
        $this->command->info('🏢 Creating departments...');
        $housekeeping = Department::create(['name' => 'Housekeeping', 'description' => 'Room cleaning and linen management', 'is_active' => true]);
        $maintenance = Department::create(['name' => 'Maintenance', 'description' => 'Building maintenance and repairs', 'is_active' => true]);
        $frontDesk = Department::create(['name' => 'Front Desk', 'description' => 'Guest services and reception', 'is_active' => true]);
        $kitchen = Department::create(['name' => 'Kitchen', 'description' => 'Food preparation and dining', 'is_active' => true]);

        // 3. Categories
        $this->command->info('📁 Creating categories...');
        $linens = Category::create(['name' => 'Linens & Textiles', 'description' => 'All fabric items']);
        $cleaning = Category::create(['name' => 'Cleaning Supplies', 'description' => 'Cleaning materials']);
        $toiletries = Category::create(['name' => 'Toiletries', 'description' => 'Guest amenities']);
        $tools = Category::create(['name' => 'Tools & Equipment', 'description' => 'Maintenance tools']);
        $electronics = Category::create(['name' => 'Electronics', 'description' => 'Electronic equipment']);

        $bedLinens = Category::create(['name' => 'Bed Linens', 'description' => 'Sheets and pillowcases', 'parent_id' => $linens->id]);
        $bathLinens = Category::create(['name' => 'Bath Linens', 'description' => 'Towels and bathmats', 'parent_id' => $linens->id]);
        $detergents = Category::create(['name' => 'Detergents', 'description' => 'Soaps and cleaners', 'parent_id' => $cleaning->id]);
        $disinfectants = Category::create(['name' => 'Disinfectants', 'description' => 'Sanitizers', 'parent_id' => $cleaning->id]);
        $handTools = Category::create(['name' => 'Hand Tools', 'description' => 'Manual tools', 'parent_id' => $tools->id]);
        $powerTools = Category::create(['name' => 'Power Tools', 'description' => 'Electric tools', 'parent_id' => $tools->id]);

        \Artisan::call('categories:update-hierarchy');

        // 4. Consumable Items with Batches
        $this->command->info('📦 Creating consumable items...');
        
        $shampoo = Item::create([
            'name' => 'Hotel Shampoo Bottles',
            'description' => '50ml guest amenity bottles',
            'category_id' => $toiletries->id,
            'department_id' => $housekeeping->id,
            'quantity' => 350,
            'minimum_stock' => 100,
            'unit' => 'bottles',
            'status' => 'available',
            'item_type' => 'consumable',
        ]);

        Batch::create([
            'batch_number' => 'B' . now()->format('Ymd') . '-SH01',
            'item_id' => $shampoo->id,
            'quantity' => 150,
            'unit_cost' => 2.50,
            'supplier' => 'BeautySupply Co.',
            'location' => 'Storage Room A - Shelf 3',
            'manufacture_date' => now()->subMonths(10),
            'expiry_date' => now()->addDays(25),
            'status' => 'active',
        ]);

        Batch::create([
            'batch_number' => 'B' . now()->format('Ymd') . '-SH02',
            'item_id' => $shampoo->id,
            'quantity' => 200,
            'unit_cost' => 2.50,
            'supplier' => 'BeautySupply Co.',
            'location' => 'Storage Room A - Shelf 3',
            'manufacture_date' => now()->subMonths(2),
            'expiry_date' => now()->addMonths(10),
            'status' => 'active',
        ]);

        $toiletPaper = Item::create([
            'name' => 'Premium Toilet Paper',
            'description' => '3-ply soft rolls',
            'category_id' => $toiletries->id,
            'department_id' => $housekeeping->id,
            'quantity' => 288,
            'minimum_stock' => 50,
            'unit' => 'rolls',
            'status' => 'available',
            'item_type' => 'consumable',
        ]);

        Batch::create([
            'batch_number' => 'B' . now()->format('Ymd') . '-TP01',
            'item_id' => $toiletPaper->id,
            'quantity' => 288,
            'unit_cost' => 1.25,
            'supplier' => 'Paper Products Inc.',
            'location' => 'Storage Room B',
            'status' => 'active',
        ]);

        $bedSheets = Item::create([
            'name' => 'White King Bed Sheets',
            'description' => '300 thread count',
            'category_id' => $bedLinens->id,
            'department_id' => $housekeeping->id,
            'quantity' => 18,
            'minimum_stock' => 50,
            'unit' => 'sets',
            'status' => 'available',
            'item_type' => 'consumable',
        ]);

        Batch::create([
            'batch_number' => 'B' . now()->format('Ymd') . '-BS01',
            'item_id' => $bedSheets->id,
            'quantity' => 18,
            'unit_cost' => 25.00,
            'supplier' => 'Premium Linens Ltd.',
            'location' => 'Linen Room - Rack 1',
            'status' => 'active',
        ]);

        // 5. Non-Consumables with Depreciation
        $this->command->info('💻 Creating non-consumable items...');
        
        $laptops = Item::create([
            'name' => 'Dell Latitude Laptops',
            'description' => 'i7, 16GB RAM, 512GB SSD',
            'category_id' => $electronics->id,
            'department_id' => $frontDesk->id,
            'quantity' => 3,
            'minimum_stock' => 2,
            'unit' => 'pcs',
            'status' => 'available',
            'item_type' => 'non-consumable',
        ]);

        $laptopBatch = Batch::create([
            'batch_number' => 'B' . now()->subYears(2)->format('Ymd') . '-LP01',
            'item_id' => $laptops->id,
            'quantity' => 3,
            'unit_cost' => 45000.00,
            'purchase_price' => 45000.00,
            'purchase_date' => now()->subYears(2),
            'depreciation_method' => 'straight_line',
            'useful_life_years' => 5,
            'salvage_value' => 5000.00,
            'supplier' => 'TechMart Philippines',
            'location' => 'IT Storage Room',
            'status' => 'active',
        ]);

        $vacuums = Item::create([
            'name' => 'Industrial Vacuums',
            'description' => 'Heavy-duty commercial',
            'category_id' => $tools->id,
            'department_id' => $housekeeping->id,
            'quantity' => 4,
            'minimum_stock' => 2,
            'unit' => 'pcs',
            'status' => 'available',
            'item_type' => 'non-consumable',
        ]);

        Batch::create([
            'batch_number' => 'B' . now()->subYears(1)->format('Ymd') . '-VC01',
            'item_id' => $vacuums->id,
            'quantity' => 4,
            'unit_cost' => 12000.00,
            'purchase_price' => 12000.00,
            'purchase_date' => now()->subYears(1),
            'depreciation_method' => 'declining_balance',
            'useful_life_years' => 8,
            'salvage_value' => 1000.00,
            'supplier' => 'Industrial Equipment',
            'location' => 'Equipment Room',
            'status' => 'active',
        ]);

        // 6. Borrowed Items
        $this->command->info('📋 Creating borrows...');
        
        BorrowedItem::create([
            'item_id' => $laptops->id,
            'batch_id' => $laptopBatch->id,
            'user_id' => $staff1->id,
            'quantity' => 2,
            'borrower_name' => $staff1->name,
            'borrower_department' => 'Front Desk',
            'notes' => 'Staff training',
            'reference_number' => 'BOR-001',
            'borrowed_at' => now()->subDays(7),
        ]);

        Transaction::create([
            'item_id' => $laptops->id,
            'batch_id' => $laptopBatch->id,
            'user_id' => $staff1->id,
            'type' => 'out',
            'transaction_type' => 'borrow',
            'quantity' => 2,
            'notes' => 'Staff training',
            'reference_number' => 'BOR-001',
            'transaction_date' => now()->subDays(7),
        ]);

        // 7. Notifications
        $this->command->info('🔔 Creating notifications...');
        
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'low_stock',
            'title' => 'Low Stock: White King Bed Sheets',
            'description' => '18 sets remaining (min: 50)',
            'url' => '/items/' . $bedSheets->id,
            'icon' => 'warning',
            'color' => 'yellow',
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'expiring',
            'title' => 'Expiring Soon: Hotel Shampoo',
            'description' => 'Batch expires in 25 days',
            'url' => '/batches',
            'icon' => 'expiring',
            'color' => 'yellow',
        ]);

        $this->command->newLine();
        $this->command->info('✅ Seeding completed!');
        $this->command->table(
            ['Metric', 'Count'],
            [
                ['Users', 3],
                ['Departments', 4],
                ['Categories', 11],
                ['Items', 6],
                ['Batches', 7],
                ['Borrowed Items', 1],
                ['Notifications', 2],
            ]
        );
        $this->command->info('📧 Login: admin@iconvenue.com / password');
    }
}
