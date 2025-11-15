#!/bin/bash
set -e

# Composerの依存関係をインストール（vendorディレクトリが存在しない場合）
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# ストレージディレクトリの権限を設定
chmod -R 775 storage bootstrap/cache || true

# 元のコマンドを実行
exec "$@"
