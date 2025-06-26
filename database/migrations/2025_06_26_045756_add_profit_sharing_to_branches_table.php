    <?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::table('branches', function (Blueprint $table) {
                // Persentase bagi hasil dengan tempat (misal: 15.50 untuk 15.5%)
                $table->decimal('profit_sharing_percentage', 5, 2)->nullable()->default(0)->after('phone_number');
            });
        }
        public function down(): void {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('profit_sharing_percentage');
            });
        }
    };
    