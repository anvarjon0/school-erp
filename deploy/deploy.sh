#!/usr/bin/env bash
set -e

# ==============================================================================
# Laravel Production Zero-Downtime Release Deployment Script
# ==============================================================================

APP_DIR="/var/www/school-erp"
RELEASES_DIR="$APP_DIR/releases"
SHARED_DIR="$APP_DIR/shared"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
NEW_RELEASE_DIR="$RELEASES_DIR/$TIMESTAMP"
REPO_URL="https://github.com/anvarjon0/school-erp.git"
BRANCH="${1:-master}"

echo "========================================================"
echo "🚀 Boshlanmoqda: Yangi Release ($TIMESTAMP)"
echo "📌 Branch: $BRANCH"
echo "========================================================"

# 1. Kataloglar mavjudligini tekshirish
if [ ! -d "$SHARED_DIR" ] || [ ! -f "$SHARED_DIR/.env" ]; then
    echo "❌ XATO: $SHARED_DIR yoki $SHARED_DIR/.env topilmadi!"
    exit 1
fi

mkdir -p "$RELEASES_DIR"

# 2. Yangi release katalogini yaratish va kodni olish
echo "📥 1/8: Yangi release uchun kod yuklanmoqda..."
mkdir -p "$NEW_RELEASE_DIR"
git clone --depth 1 --branch "$BRANCH" "$REPO_URL" "$NEW_RELEASE_DIR"

# Xatolik bo'lsa tozalash funksiyasi (Rollback)
cleanup_on_failure() {
    echo "❌ Deployment xatolik bilan to'xtadi! Tozalanmoqda..."
    rm -rf "$NEW_RELEASE_DIR"
    echo "⚠️ Yangi release bekor qilindi. Mavjud production 'current' versiyasi buzilmadi."
    exit 1
}
trap cleanup_on_failure ERR

cd "$NEW_RELEASE_DIR"

# 3. Shared .env va storage'ni ulash
echo "🔗 2/8: Shared .env va storage ulanmoqda..."
ln -sfn "$SHARED_DIR/.env" "$NEW_RELEASE_DIR/.env"
rm -rf "$NEW_RELEASE_DIR/storage"
ln -sfn "$SHARED_DIR/storage" "$NEW_RELEASE_DIR/storage"

# 4. Composer qaramliklarini o'rnatish
echo "📦 3/8: Composer o'rnatilmoqda (no-dev, optimized)..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 5. Frontend assets build qilish
echo "🎨 4/8: NPM build tayyorlanmoqda..."
npm install --no-audit --no-fund --silent
npm run build

# 6. Storage link va Bazaviy migratsiyalar
echo "🗄️ 5/8: Storage link va Database migration bajarilmoqda..."
php artisan storage:link || true
php artisan migrate --force

# 7. Laravel Cache & Optimization
echo "⚡ 6/8: Laravel konfiguratsiyalari keshyalanmoqda..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Health Check tekshiruvi (PHP CLI orqali)
echo "🩺 7/8: Yangi reliz sog'lomligi tekshirilmoqda..."
php -r "
    require __DIR__.'/vendor/autoload.php';
    \$app = require_once __DIR__.'/bootstrap/app.php';
    \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
    \$kernel->bootstrap();
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo 'DB Connection: OK'.PHP_EOL;
    } catch (\Throwable \$e) {
        fwrite(STDERR, 'DB Connection Failed: '.\$e->getMessage().PHP_EOL);
        exit(1);
    }
"

# 9. ATOMIC SWITCH: current symlink'ni yangi release'ga o'tkazish
echo "🔄 8/8: Trafik yangi release'ga o'tkazilmoqda (Atomic Switch)..."
ln -sfn "$NEW_RELEASE_DIR" "$APP_DIR/current_tmp"
mv -Tf "$APP_DIR/current_tmp" "$APP_DIR/current"

# 10. PHP-FPM va Supervisor'ni qayta yuklash
echo "♻️ PHP-FPM reload qilinmoqda..."
if systemctl is-active --quiet php8.2-fpm; then
    sudo /usr/bin/systemctl reload php8.2-fpm
elif systemctl is-active --quiet php8.3-fpm; then
    sudo /usr/bin/systemctl reload php8.3-fpm
fi

if systemctl is-active --quiet supervisor; then
    sudo /usr/bin/supervisorctl restart all || true
fi

# 11. Eski releaselarni tozalash (faqat oxirgi 5 tasini saqlash)
echo "🧹 Eski releaselar tozalanmoqda (oxirgi 5 ta saqlanadi)..."
cd "$RELEASES_DIR"
ls -1dt 20* | tail -n +6 | xargs rm -rf 2>/dev/null || true

echo "========================================================"
echo "✅ DEPLOYMENT MUVAFFAQIYATLI YAKUNLANDI!"
echo "🌐 Sayt faol: https://edu-link.uz"
echo "📂 Joriy versiya: $NEW_RELEASE_DIR"
echo "========================================================"
