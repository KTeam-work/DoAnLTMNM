<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code', 30)->unique();

            // Khóa ngoại liên kết tới tin đăng bị tố cáo
            $table->foreignId('rental_post_id')
                ->constrained('rental_posts')
                ->onDelete('cascade');

            // Khóa ngoại liên kết tới người gửi tố cáo
            $table->foreignId('reporter_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Lý do vi phạm
            $table->enum('reason', [
                'fake_info',        // Thông tin / ảnh sai thực tế
                'price_mismatch',   // Giá thuê không đúng bài đăng
                'fraud_scam',       // Lừa đảo tiền cọc
                'contact_failed',   // Không liên hệ được chủ trọ
                'inappropriate',    // Từ ngữ / nội dung phản cảm
                'other',            // Lý do khác
            ])->default('other');

            $table->text('description');
            $table->string('evidence_image')->nullable();

            // Trạng thái xử lý của Admin
            $table->enum('status', [
                'pending',          // Chờ xử lý
                'investigating',    // Đang xác minh
                'resolved',         // Đã xử lý (đúng vi phạm)
                'dismissed',        // Bác bỏ (tố cáo sai)
            ])->default('pending');

            // Biện pháp chế tài Admin đã áp dụng
            $table->enum('action_taken', [
                'none',             // Không có
                'post_hidden',      // Đã ẩn tin đăng
                'owner_warned',     // Đã gửi cảnh cáo chủ trọ
                'owner_banned',     // Đã khóa tài khoản chủ trọ
            ])->default('none');

            // Admin nào đứng ra giải quyết
            $table->foreignId('handler_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('admin_note')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Index tối ưu tốc độ lọc danh sách
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_reports');
    }
};

