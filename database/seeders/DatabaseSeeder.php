<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Seed Users ──
        $admin = User::updateOrCreate(
            ['email' => 'admin@taskmanager.test'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        $user1 = User::updateOrCreate(
            ['email' => 'user@taskmanager.test'],
            [
                'name'     => 'Sample User',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]
        );

        $user2 = User::updateOrCreate(
            ['email' => 'jane@taskmanager.test'],
            [
                'name'     => 'Jane Doe',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]
        );

        // ── Seed Sample Tasks ──
        $tasks = [
            [
                'title'       => 'Set up project repository',
                'description' => 'Initialize the GitHub repository, add README, and configure CI/CD pipeline.',
                'status'      => 'completed',
                'priority'    => 'high',
                'assigned_to' => $user1->id,
                'created_by'  => $admin->id,
                'due_date'    => now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'title'       => 'Design database schema',
                'description' => 'Create ERD and write migrations for all required tables.',
                'status'      => 'completed',
                'priority'    => 'high',
                'assigned_to' => $user1->id,
                'created_by'  => $admin->id,
                'due_date'    => now()->subDays(3)->format('Y-m-d'),
            ],
            [
                'title'       => 'Implement authentication module',
                'description' => 'Build login, register, and role-based access control.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'assigned_to' => $user2->id,
                'created_by'  => $admin->id,
                'due_date'    => now()->addDays(2)->format('Y-m-d'),
            ],
            [
                'title'       => 'Create task CRUD API',
                'description' => 'Implement RESTful API endpoints for creating, reading, updating, and deleting tasks.',
                'status'      => 'in_progress',
                'priority'    => 'medium',
                'assigned_to' => $user1->id,
                'created_by'  => $admin->id,
                'due_date'    => now()->addDays(4)->format('Y-m-d'),
            ],
            [
                'title'       => 'Integrate file upload feature',
                'description' => 'Allow users to attach files to tasks. Support PDF, images, and documents.',
                'status'      => 'pending',
                'priority'    => 'medium',
                'assigned_to' => $user2->id,
                'created_by'  => $admin->id,
                'due_date'    => now()->addDays(7)->format('Y-m-d'),
            ],
            [
                'title'       => 'Write unit and feature tests',
                'description' => 'Cover all controllers, models, and API endpoints with PHPUnit tests.',
                'status'      => 'pending',
                'priority'    => 'low',
                'assigned_to' => $user1->id,
                'created_by'  => $admin->id,
                'due_date'    => now()->addDays(10)->format('Y-m-d'),
            ],
            [
                'title'       => 'Deploy to production server',
                'description' => 'Set up server environment, configure .env, and deploy the application.',
                'status'      => 'pending',
                'priority'    => 'high',
                'assigned_to' => null,
                'created_by'  => $admin->id,
                'due_date'    => now()->addDays(14)->format('Y-m-d'),
            ],
            [
                'title'       => 'Email notification system',
                'description' => 'Configure Mailtrap and send task assignment notifications via email.',
                'status'      => 'pending',
                'priority'    => 'medium',
                'assigned_to' => $user2->id,
                'created_by'  => $admin->id,
                'due_date'    => now()->addDays(6)->format('Y-m-d'),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                ['title' => $taskData['title']],
                $taskData
            );
        }
    }
}
