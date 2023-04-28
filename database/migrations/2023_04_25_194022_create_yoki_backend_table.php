<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->index();
            $table->string('token');
            $table->boolean('used')->default(false);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('admin_activations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->index();
            $table->string('token');
            $table->boolean('used')->default(false);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('admin_password_resets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->boolean('activated')->default(false);
            $table->boolean('forbidden')->default(false);
            $table->string('language', 2)->default('en');
            $table->softDeletes();
            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();

            $table->unique(['email', 'deleted_at']);
        });

        Schema::create('appeals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('appeals_user_id_foreign');
            $table->text('body');
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
            $table->text('reply')->nullable();
            $table->dateTime('replied_at')->nullable();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->longText('body');
            $table->boolean('published')->default(false);
            $table->bigInteger('views')->default(0);
            $table->timestamps();
            $table->string('user_type');
            $table->unsignedBigInteger('user_id');
            $table->string('group_link')->nullable();

            $table->index(['user_type', 'user_id']);
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->text('about')->nullable();
            $table->string('copyright')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });

        Schema::create('black_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('membership_id')->unique();
            $table->boolean('can_comment')->default(false);
            $table->boolean('can_see_post')->default(false);
            $table->timestamps();
        });

        Schema::create('book_reads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('book_id')->index('book_reads_book_id_foreign');
            $table->timestamps();

            $table->unique(['user_id', 'book_id']);
        });

        Schema::create('book_user_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('book_id')->index('book_user_statuses_book_id_foreign');
            $table->integer('rating')->nullable();
            $table->boolean('bookmarked')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'book_id']);
            $table->unique(['user_id', 'book_id']);
        });

        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->string('language')->nullable();
            $table->integer('page_count')->nullable();
            $table->date('publication_date')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->double('compare_price', 8, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->string('status')->default('PENDING_APPROVAL')->index();
            $table->string('book_type')->index();
            $table->string('voice_director')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable()->index();
            $table->unsignedBigInteger('genre_id')->nullable()->index();
            $table->unsignedBigInteger('author_id')->nullable()->index();
            $table->timestamps();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('shop_link')->nullable();
            $table->string('code');
            $table->string('package_code');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('reply_id')->nullable()->index('comments_reply_id_foreign');
            $table->string('commentable_type');
            $table->unsignedBigInteger('commentable_id');
            $table->timestamps();

            $table->index(['commentable_type', 'commentable_id']);
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body');
            $table->unsignedBigInteger('complainer_id')->nullable()->index('complaints_complainer_id_foreign');
            $table->string('complainable_type');
            $table->unsignedBigInteger('complainable_id');
            $table->timestamps();

            $table->index(['complainable_type', 'complainable_id']);
        });

        Schema::create('coupon_uses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('user_id')->index('coupon_uses_user_id_foreign');
            $table->timestamps();

            $table->unique(['coupon_id', 'user_id']);
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('amount');
            $table->string('code')->unique();
            $table->dateTime('expires_at');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('genres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('group_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('membership_id')->unique();
            $table->boolean('can_update_group')->default(false);
            $table->boolean('can_create_post')->default(false);
            $table->boolean('can_add_to_blacklist')->default(false);
            $table->timestamps();
        });

        Schema::create('group_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->unsignedBigInteger('group_category_id')->nullable()->index();
            $table->unsignedBigInteger('owner_id')->nullable()->index('groups_owner_id_foreign');
            $table->integer('member_limit')->nullable();
            $table->json('degree_scope')->nullable();
            $table->boolean('is_private')->default(false);
            $table->string('invite_link')->nullable()->unique();
            $table->string('status')->default('PENDING_APPROVAL')->index();
            $table->timestamps();
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('likes_user_id_foreign');
            $table->string('likeable_type');
            $table->unsignedBigInteger('likeable_id');
            $table->boolean('disliked')->default(false);
            $table->timestamps();

            $table->index(['likeable_type', 'likeable_id']);
        });

        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->char('uuid', 36)->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id')->index('memberships_user_id_foreign');
            $table->boolean('approved')->default(false)->index();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->index(['group_id', 'user_id']);
            $table->unique(['group_id', 'user_id']);
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->index(['model_id', 'model_type']);
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->index(['model_id', 'model_type']);
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('type');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tokenable_type');
            $table->unsignedBigInteger('tokenable_id');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->string('user_agent')->nullable();
            $table->string('ip')->nullable();
            $table->string('fcm_token')->nullable();

            $table->index(['tokenable_type', 'tokenable_id']);
        });

        Schema::create('post_likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index('post_likes_user_id_foreign');
            $table->boolean('liked')->default(true);
            $table->timestamps();

            $table->unique(['post_id', 'user_id']);
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 200);
            $table->text('body');
            $table->json('degree_scope');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('group_id')->index('posts_group_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('posts_user_id_foreign');
            $table->timestamps();
        });

        Schema::create('product_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('code');
            $table->timestamps();
        });

        Schema::create('publishers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('location_url')->nullable();
            $table->timestamps();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('purchases_user_id_foreign');
            $table->unsignedBigInteger('book_id')->nullable()->index('purchases_book_id_foreign');
            $table->unsignedDecimal('amount', 18);
            $table->string('phone', 15);
            $table->enum('state', ['PENDING_PAYMENT', 'COMPLETED', 'CANCELED']);
            $table->json('user_data')->nullable();
            $table->json('book_data')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('from_balance')->default(0);
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body');
            $table->unsignedBigInteger('book_id')->index('quotes_book_id_foreign');
            $table->unsignedBigInteger('user_id')->index('quotes_user_id_foreign');
            $table->timestamps();
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id')->index('role_has_permissions_role_id_foreign');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->index();
            $table->text('value');
        });

        Schema::create('sms_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone')->unique();
            $table->string('code')->index('code');
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tag_id');
            $table->string('taggable_type');
            $table->unsignedBigInteger('taggable_id');
            $table->timestamps();

            $table->index(['taggable_type', 'taggable_id']);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('telescope_entries', function (Blueprint $table) {
            $table->bigIncrements('sequence');
            $table->char('uuid', 36)->unique();
            $table->char('batch_id', 36)->index();
            $table->string('family_hash')->nullable()->index();
            $table->boolean('should_display_on_index')->default(true);
            $table->string('type', 20);
            $table->longText('content');
            $table->dateTime('created_at')->nullable()->index();

            $table->index(['type', 'should_display_on_index']);
        });

        Schema::create('telescope_entries_tags', function (Blueprint $table) {
            $table->char('entry_uuid', 36);
            $table->string('tag')->index();

            $table->index(['entry_uuid', 'tag']);
        });

        Schema::create('telescope_monitoring', function (Blueprint $table) {
            $table->string('tag');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('payment_system', ['payme', 'click']);
            $table->string('system_transaction_id');
            $table->unsignedDecimal('amount', 18);
            $table->integer('state');
            $table->string('updated_time')->nullable();
            $table->string('comment')->nullable();
            $table->json('detail')->nullable();
            $table->unsignedBigInteger('purchase_id')->index('transactions_purchase_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace')->default('*')->index();
            $table->string('group')->index();
            $table->text('key');
            $table->json('text');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname');
            $table->unsignedBigInteger('balance')->default(0);
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('region')->nullable();
            $table->enum('degree', ['USER', 'GENIUS', 'SCIENTIST', 'CLEVER'])->default('USER');
            $table->boolean('verified')->default(false);
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('social_auth_id')->nullable();
            $table->string('social_auth_type')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['social_auth_id', 'social_auth_type']);
        });

        Schema::create('wysiwyg_media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_path');
            $table->unsignedInteger('wysiwygable_id')->nullable()->index();
            $table->string('wysiwygable_type')->nullable();
            $table->timestamps();
        });

        Schema::table('appeals', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('black_list', function (Blueprint $table) {
            $table->foreign(['membership_id'])->references(['id'])->on('memberships')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('book_reads', function (Blueprint $table) {
            $table->foreign(['book_id'])->references(['id'])->on('books')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('book_user_statuses', function (Blueprint $table) {
            $table->foreign(['book_id'])->references(['id'])->on('books')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->foreign(['author_id'])->references(['id'])->on('authors')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['genre_id'])->references(['id'])->on('genres')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['parent_id'])->references(['id'])->on('books')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['publisher_id'])->references(['id'])->on('publishers')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign(['reply_id'])->references(['id'])->on('comments')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->foreign(['complainer_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });

        Schema::table('coupon_uses', function (Blueprint $table) {
            $table->foreign(['coupon_id'])->references(['id'])->on('coupons')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('group_admins', function (Blueprint $table) {
            $table->foreign(['membership_id'])->references(['id'])->on('memberships')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->foreign(['group_category_id'])->references(['id'])->on('group_categories')->onDelete('SET NULL');
            $table->foreign(['owner_id'])->references(['id'])->on('users')->onDelete('SET NULL');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->foreign(['group_id'])->references(['id'])->on('groups')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->foreign(['permission_id'])->references(['id'])->on('permissions')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->foreign(['role_id'])->references(['id'])->on('roles')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('post_likes', function (Blueprint $table) {
            $table->foreign(['post_id'])->references(['id'])->on('posts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign(['group_id'])->references(['id'])->on('groups')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign(['book_id'])->references(['id'])->on('books')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreign(['book_id'])->references(['id'])->on('books')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->foreign(['permission_id'])->references(['id'])->on('permissions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['role_id'])->references(['id'])->on('roles')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('telescope_entries_tags', function (Blueprint $table) {
            $table->foreign(['entry_uuid'])->references(['uuid'])->on('telescope_entries')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_purchase_id_foreign');
        });

        Schema::table('telescope_entries_tags', function (Blueprint $table) {
            $table->dropForeign('telescope_entries_tags_entry_uuid_foreign');
        });

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropForeign('role_has_permissions_permission_id_foreign');
            $table->dropForeign('role_has_permissions_role_id_foreign');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign('quotes_book_id_foreign');
            $table->dropForeign('quotes_user_id_foreign');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_book_id_foreign');
            $table->dropForeign('purchases_user_id_foreign');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_group_id_foreign');
            $table->dropForeign('posts_user_id_foreign');
        });

        Schema::table('post_likes', function (Blueprint $table) {
            $table->dropForeign('post_likes_post_id_foreign');
            $table->dropForeign('post_likes_user_id_foreign');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign('model_has_roles_role_id_foreign');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign('model_has_permissions_permission_id_foreign');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->dropForeign('memberships_group_id_foreign');
            $table->dropForeign('memberships_user_id_foreign');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign('likes_user_id_foreign');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_group_category_id_foreign');
            $table->dropForeign('groups_owner_id_foreign');
        });

        Schema::table('group_admins', function (Blueprint $table) {
            $table->dropForeign('group_admins_membership_id_foreign');
        });

        Schema::table('coupon_uses', function (Blueprint $table) {
            $table->dropForeign('coupon_uses_coupon_id_foreign');
            $table->dropForeign('coupon_uses_user_id_foreign');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign('complaints_complainer_id_foreign');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_reply_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign('books_author_id_foreign');
            $table->dropForeign('books_genre_id_foreign');
            $table->dropForeign('books_parent_id_foreign');
            $table->dropForeign('books_publisher_id_foreign');
        });

        Schema::table('book_user_statuses', function (Blueprint $table) {
            $table->dropForeign('book_user_statuses_book_id_foreign');
            $table->dropForeign('book_user_statuses_user_id_foreign');
        });

        Schema::table('book_reads', function (Blueprint $table) {
            $table->dropForeign('book_reads_book_id_foreign');
            $table->dropForeign('book_reads_user_id_foreign');
        });

        Schema::table('black_list', function (Blueprint $table) {
            $table->dropForeign('black_list_membership_id_foreign');
        });

        Schema::table('appeals', function (Blueprint $table) {
            $table->dropForeign('appeals_user_id_foreign');
        });

        Schema::dropIfExists('wysiwyg_media');

        Schema::dropIfExists('users');

        Schema::dropIfExists('translations');

        Schema::dropIfExists('transactions');

        Schema::dropIfExists('telescope_monitoring');

        Schema::dropIfExists('telescope_entries_tags');

        Schema::dropIfExists('telescope_entries');

        Schema::dropIfExists('tags');

        Schema::dropIfExists('taggables');

        Schema::dropIfExists('sms_tokens');

        Schema::dropIfExists('settings');

        Schema::dropIfExists('roles');

        Schema::dropIfExists('role_has_permissions');

        Schema::dropIfExists('quotes');

        Schema::dropIfExists('purchases');

        Schema::dropIfExists('publishers');

        Schema::dropIfExists('product_codes');

        Schema::dropIfExists('posts');

        Schema::dropIfExists('post_likes');

        Schema::dropIfExists('personal_access_tokens');

        Schema::dropIfExists('permissions');

        Schema::dropIfExists('password_resets');

        Schema::dropIfExists('notifications');

        Schema::dropIfExists('model_has_roles');

        Schema::dropIfExists('model_has_permissions');

        Schema::dropIfExists('memberships');

        Schema::dropIfExists('media');

        Schema::dropIfExists('likes');

        Schema::dropIfExists('groups');

        Schema::dropIfExists('group_categories');

        Schema::dropIfExists('group_admins');

        Schema::dropIfExists('genres');

        Schema::dropIfExists('failed_jobs');

        Schema::dropIfExists('coupons');

        Schema::dropIfExists('coupon_uses');

        Schema::dropIfExists('complaints');

        Schema::dropIfExists('comments');

        Schema::dropIfExists('books');

        Schema::dropIfExists('book_user_statuses');

        Schema::dropIfExists('book_reads');

        Schema::dropIfExists('black_list');

        Schema::dropIfExists('banners');

        Schema::dropIfExists('authors');

        Schema::dropIfExists('articles');

        Schema::dropIfExists('appeals');

        Schema::dropIfExists('admin_users');

        Schema::dropIfExists('admin_password_resets');

        Schema::dropIfExists('admin_activations');

        Schema::dropIfExists('activations');
    }
};
