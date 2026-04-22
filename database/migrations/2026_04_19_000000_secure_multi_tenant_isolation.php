<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        // 1. Prepare Workspace 'system_quarantine'
        Schema::table('workspaces', function (Blueprint $table) {
            $table->string('type')->default('personal')->change(); 
        });

        $systemWorkspaceId = null;
        $systemWorkspace = DB::table('workspaces')->where('type', 'system_quarantine')->first();

        if (!$systemWorkspace) {
            $admin = DB::table('users')->whereIn('role', ['super_admin', 'admin'])->orderBy('created_at')->first() 
                     ?? DB::table('users')->orderBy('created_at')->first();
            
            if ($admin) {
                $systemWorkspaceId = DB::table('workspaces')->insertGetId([
                    'user_id' => $admin->id,
                    'name' => 'System Quarantine',
                    'type' => 'system_quarantine',
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            $systemWorkspaceId = $systemWorkspace->id;
        }

        // 2. Update Customers Table (Idempotent)
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->after('whatsapp');
            }
            if (!Schema::hasColumn('customers', 'address')) {
                $table->text('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('customers', 'notes')) {
                $table->text('notes')->nullable()->after('address');
            }
            if (!Schema::hasColumn('customers', 'points')) {
                $table->integer('points')->default(0)->after('notes');
            }
            if (!Schema::hasColumn('customers', 'tier')) {
                $table->string('tier')->default('regular')->after('points');
            }
            if (!Schema::hasColumn('customers', 'total_spent')) {
                $table->decimal('total_spent', 15, 2)->default(0)->after('tier');
            }
            if (!Schema::hasColumn('customers', 'service_count')) {
                $table->integer('service_count')->default(0)->after('total_spent');
            }
            if (!Schema::hasColumn('customers', 'last_service_at')) {
                $table->timestamp('last_service_at')->nullable()->after('service_count');
            }
            if (!Schema::hasColumn('customers', 'workspace_id')) {
                $table->foreignId('workspace_id')->nullable()->after('id')->constrained('workspaces')->cascadeOnDelete();
            }
        });

        // 3. Update Tickets Table
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'workspace_id')) {
                $table->foreignId('workspace_id')->nullable()->after('id')->constrained('workspaces')->cascadeOnDelete();
            }
        });

        // 4. Update Inventories Table
        Schema::table('inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventories', 'workspace_id')) {
                $table->foreignId('workspace_id')->nullable()->after('id')->constrained('workspaces')->cascadeOnDelete();
            }
        });

        // 5. Update Galleries Table
        Schema::table('galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('galleries', 'workspace_id')) {
                $table->foreignId('workspace_id')->nullable()->after('id')->constrained('workspaces')->cascadeOnDelete();
            }
        });


        // 5. Data Migration: Trace and Map
        
        // A. Tickets (Trace via user_id)
        // Check if we are using MySQL or SQLite for the syntax
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("
                UPDATE tickets t 
                JOIN users u ON t.user_id = u.id 
                SET t.workspace_id = u.active_workspace_id
                WHERE t.workspace_id IS NULL AND u.active_workspace_id IS NOT NULL
            ");

            DB::statement("
                UPDATE customers c 
                JOIN tickets t ON c.id = t.customer_id
                SET c.workspace_id = t.workspace_id
                WHERE c.workspace_id IS NULL AND t.workspace_id IS NOT NULL
            ");
        } else {
            // Fallback for SQLite or other drivers (naive PHP loop if needed, but statement is better)
            // For now, let's assume MySQL given the project context but handle it safer.
            $tickets = DB::table('tickets')->whereNull('workspace_id')->get();
            foreach ($tickets as $ticket) {
                $user = DB::table('users')->where('id', $ticket->user_id)->first();
                if ($user && $user->active_workspace_id) {
                    DB::table('tickets')->where('id', $ticket->id)->update(['workspace_id' => $user->active_workspace_id]);
                }
            }

            $customers = DB::table('customers')->whereNull('workspace_id')->get();
            foreach ($customers as $customer) {
                $ticket = DB::table('tickets')->where('customer_id', $customer->id)->whereNotNull('workspace_id')->first();
                if ($ticket) {
                    DB::table('customers')->where('id', $customer->id)->update(['workspace_id' => $ticket->workspace_id]);
                }
            }
        }

        // C. Quarantine Orphans
        if ($systemWorkspaceId) {
            DB::table('customers')->whereNull('workspace_id')->update(['workspace_id' => $systemWorkspaceId]);
            DB::table('tickets')->whereNull('workspace_id')->update(['workspace_id' => $systemWorkspaceId]);
            DB::table('inventories')->whereNull('workspace_id')->update(['workspace_id' => $systemWorkspaceId]);
        }

        // 6. Enforce NOT NULL Constraints
        // Note: SQLite doesn't support changing nullable to NOT NULL easily.
        if ($driver !== 'sqlite') {
            Schema::table('customers', function (Blueprint $table) {
                $table->unsignedBigInteger('workspace_id')->nullable(false)->change();
            });
            Schema::table('tickets', function (Blueprint $table) {
                $table->unsignedBigInteger('workspace_id')->nullable(false)->change();
            });
            Schema::table('inventories', function (Blueprint $table) {
                $table->unsignedBigInteger('workspace_id')->nullable(false)->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn([
                'whatsapp', 'email', 'address', 'notes', 'points', 'tier', 
                'total_spent', 'service_count', 'last_service_at', 'workspace_id'
            ]);
        });
    }
};
