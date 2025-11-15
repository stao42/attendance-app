#!/bin/bash
set -e

# Composerの依存関係をインストール（vendorディレクトリが存在しない場合、またはcomposer.lockが更新された場合）
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts
fi

# Laravelのパッケージディスカバリーを実行（.envファイルが存在する場合のみ）
if [ -f ".env" ]; then
    echo "Running Laravel package discovery..."
    php artisan package:discover --ansi || true
fi

# ストレージディレクトリの権限を設定
chmod -R 775 storage bootstrap/cache || true

# 元のコマンドを実行
exec "$@"
