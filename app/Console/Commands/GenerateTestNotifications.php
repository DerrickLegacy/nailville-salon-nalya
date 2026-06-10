<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class GenerateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate-test {--interval=30 : Interval in seconds between notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate test notifications every X seconds for testing purposes';

    /**
     * Sample notification templates
     */
    private $templates = [
        [
            'type' => 'daily',
            'title' => 'Daily Income Report',
            'messages' => [
                'Today\'s total income is $%d.00 from %d transactions',
                'Great day! You earned $%d.00 today',
                'Daily summary: $%d.00 in revenue',
            ],
            'priority' => 'medium',
            'category' => 'income',
        ],
        [
            'type' => 'alert',
            'title' => 'Low Stock Alert',
            'messages' => [
                'Product "%s" is running low on stock! Only %d units remaining.',
                'Urgent: "%s" needs restocking. Current stock: %d units',
                'Stock alert: "%s" has only %d units left',
            ],
            'priority' => 'critical',
            'category' => 'alert',
        ],
        [
            'type' => 'goal_achieved',
            'title' => 'Goal Achieved! 🎉',
            'messages' => [
                'Congratulations! You\'ve reached your %s goal of $%d',
                'Amazing! Your %s target of $%d has been achieved',
                'Success! %s goal completed: $%d',
            ],
            'priority' => 'high',
            'category' => 'goal',
        ],
        [
            'type' => 'insight',
            'title' => 'Performance Insight',
            'messages' => [
                'Your sales increased by %d%% compared to last week',
                'Top performing day: %s with $%d in sales',
                'Customer visits up by %d%% this month',
            ],
            'priority' => 'medium',
            'category' => 'insight',
        ],
        [
            'type' => 'weekly',
            'title' => 'Weekly Summary',
            'messages' => [
                'This week: $%d revenue from %d transactions',
                'Weekly performance: %d customers served, $%d earned',
                'Week recap: %d sales totaling $%d',
            ],
            'priority' => 'low',
            'category' => 'insight',
        ],
        [
            'type' => 'alert',
            'title' => 'Expense Alert',
            'messages' => [
                'High expense detected: $%d spent on %s',
                'Expense notification: $%d for %s category',
                'Budget alert: $%d expense recorded',
            ],
            'priority' => 'high',
            'category' => 'expense',
        ],
        [
            'type' => 'system',
            'title' => 'System Update',
            'messages' => [
                'New feature available: %s',
                'System maintenance scheduled for %s',
                'Update: %s has been improved',
            ],
            'priority' => 'low',
            'category' => 'system',
        ],
    ];

    private $products = ['Nail Polish', 'Gel Polish', 'Acrylic Powder', 'UV Lamp', 'Nail Files', 'Cuticle Oil'];
    private $periods = ['daily', 'weekly', 'monthly'];
    private $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    private $features = ['Dashboard Analytics', 'Report Export', 'Mobile App', 'Customer Portal', 'Inventory Tracking'];
    private $categories = ['Supplies', 'Equipment', 'Utilities', 'Marketing', 'Salaries'];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interval = (int) $this->option('interval');
        $count = 0;

        $this->info("🔔 Starting test notification generator...");
        $this->info("📊 Generating notifications every {$interval} seconds");
        $this->info("⏹️  Press Ctrl+C to stop\n");

        while (true) {
            $count++;

            try {
                $notification = $this->generateNotification();

                $this->line(sprintf(
                    "[%s] #%d - %s: %s",
                    now()->format('H:i:s'),
                    $count,
                    $notification->category,
                    $notification->title
                ));

                $this->comment("   └─ {$notification->message}");
            } catch (\Exception $e) {
                $this->error("Error generating notification: " . $e->getMessage());
            }

            sleep($interval);
        }

        return 0;
    }

    /**
     * Generate a random notification
     */
    private function generateNotification()
    {
        $template = $this->templates[array_rand($this->templates)];
        $message = $template['messages'][array_rand($template['messages'])];

        // Fill in message placeholders with random data
        $message = $this->fillMessagePlaceholders($message, $template['category']);

        // Create additional data based on category
        $data = $this->generateAdditionalData($template['category']);

        return Notification::create([
            'type' => $template['type'],
            'title' => $template['title'],
            'message' => $message,
            'data' => json_encode($data),
            'priority' => $template['priority'],
            'category' => $template['category'],
            'is_read' => false,
        ]);
    }

    /**
     * Fill message placeholders with random data
     */
    private function fillMessagePlaceholders($message, $category)
    {
        $placeholderCount = substr_count($message, '%');

        if ($placeholderCount === 0) {
            return $message;
        }

        $values = [];

        for ($i = 0; $i < $placeholderCount; $i++) {
            if (strpos($message, '%s') !== false) {
                // String placeholder
                $values[] = $this->getRandomString($category);
            } elseif (strpos($message, '%d') !== false) {
                // Integer placeholder
                $values[] = $this->getRandomNumber($category);
            }
        }

        return vsprintf($message, $values);
    }

    /**
     * Get random string based on category
     */
    private function getRandomString($category)
    {
        switch ($category) {
            case 'alert':
                return $this->products[array_rand($this->products)];
            case 'goal':
                return $this->periods[array_rand($this->periods)];
            case 'insight':
                return $this->days[array_rand($this->days)];
            case 'system':
                return $this->features[array_rand($this->features)];
            case 'expense':
                return $this->categories[array_rand($this->categories)];
            default:
                return 'General';
        }
    }

    /**
     * Get random number based on category
     */
    private function getRandomNumber($category)
    {
        switch ($category) {
            case 'income':
                return rand(500, 5000); // Revenue amounts
            case 'alert':
                return rand(1, 10); // Stock levels
            case 'goal':
                return rand(10000, 50000); // Goal amounts
            case 'insight':
                return rand(5, 50); // Percentages or counts
            case 'expense':
                return rand(100, 2000); // Expense amounts
            default:
                return rand(1, 100);
        }
    }

    /**
     * Generate additional data for the notification
     */
    private function generateAdditionalData($category)
    {
        $data = [
            'generated_at' => now()->toIso8601String(),
            'test_mode' => true,
        ];

        switch ($category) {
            case 'income':
                $data['amount'] = rand(500, 5000);
                $data['transactions'] = rand(5, 50);
                break;
            case 'expense':
                $data['amount'] = rand(100, 2000);
                $data['category'] = $this->categories[array_rand($this->categories)];
                break;
            case 'alert':
                $data['product'] = $this->products[array_rand($this->products)];
                $data['stock_level'] = rand(1, 10);
                $data['reorder_level'] = 15;
                break;
            case 'goal':
                $data['target'] = rand(10000, 50000);
                $data['achieved'] = rand(10000, 55000);
                $data['percentage'] = rand(95, 110);
                break;
            case 'insight':
                $data['metric'] = 'sales';
                $data['change'] = rand(-20, 50);
                $data['period'] = 'week';
                break;
        }

        return $data;
    }
}
