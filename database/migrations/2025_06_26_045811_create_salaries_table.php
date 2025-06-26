    <?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::create('salaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Terapis
                $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade'); // Gaji berlaku di cabang mana
                $table->unsignedBigInteger('amount')->comment('Jumlah gaji bulanan');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'branch_id']); // Satu terapis hanya punya satu entri gaji per cabang
            });
        }
        public function down(): void { Schema::dropIfExists('salaries'); }
    };
    