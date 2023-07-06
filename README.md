# Ifee

## About

暮らしていく上での考え方や知識を整理し、

感情の変化を記録・観察していくことで、

生活をよりよくする行動をしていくためのアプリです。

こちらはそのアプリのフロント側になります。

## Getting Started

コンポーザーインストール

```
composer install
```

.env ファイルの作成

```
cp .env.example .env
```

MySQL でデータベースを作成

```
// MySQLサーバーの起動
mysql.server start

// ルートユーザーでログイン
mysql -uroot

// データベースの作成
create database laravel_test;

// 作成したデータベースが存在するか確認
show databases;

// ユーザーの作成とパスワードの設定
create user 'user'@'localhost' identified with mysql_native_password by 'password';

// ユーザーに権限を付与
grant all on laravel_test.* to 'user'@'localhost' with grant option;

// 権限変更の設定の反映
flush privileges;
```

.env の設定

```
DB_DATABASE=laravel_test
DB_USERNAME=user
DB_PASSWORD=password
```

APP_KEY の作成

```
php artisan key:generate
```

シークレットキーの作成

```
php artisan jwt:secret
```

マイグレーション

```
php artisan migrate
```

シーダーの実行
※こちらの実行で GuestLogin のユーザーが作成されます。

```
php artisan db:seed
```

他のユーザーを作成する場合はメール認証が必要になります。
任意の Gmail アドレスでアプリパスワードを取得し.env の下記項目の空欄を埋めてください

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=Ifee
```

フロントエンド側は Next.js で作成しています。下記から git clone して起動してください。

https://github.com/wasborn14/prj_idea_feel_app_v3

## Technology used

フロントエンド：Next.js, TypeScript, styled-components

バックエンド：Laravel, PHP

## Environment

フロントエンド：Vercel

バックエンド：conohaVPS (Ubuntu)
