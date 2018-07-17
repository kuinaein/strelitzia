# すとれりちあ
「すとれりちあ」はできたてほやほやの家計簿ソフトです。まだ、記帳と最終残高の表示くらいしかできません。。

## 動作環境
（カッコ内は開発に使用しているバージョン）

* PHP 7 (7.2.x)
* PostgreSQL (10.x)
* Composer (1.x)
* Node.js (10.x)
* NPM (6.x)
* Yarn (1.x)

開発に使用している OS は Antergos (Arch Linux系)、ブラウザは Chromium です。

## インストール
### システム側のセットアップ
1. `.env.example` を `.env` にコピーする。
1. PostgreSQL でユーザー `strelitzia`、データベース `strelitzia` を作成する。（DB名等は `.env` で変更可能）<br/>
`CREATE USER strelitzia WITH ENCRYPTED PASSWORD 'strelitzia';`<br/>
`CREATE DATABASE strelitzia WITH OWNER strelitzia ENCODING 'UTF8' LC_COLLATE 'C' LC_CTYPE 'C' TEMPLATE template0;`
1. `php artisan stre:install` を実行する。

## 起動方法
1. `php artisan serve` を実行する。
1. ブラウザで http://localhost:8000/ を開く。 (ポート番号は `--port` オプションで変更可能)

## アップデート
1. データベースのバックアップを取る。<br/>
`pg_dump -U strelitzia -b -Fc > storage/app/pg_dump`
1. `php artisan stre:update` を実行する。

## アンインストール
データベースとディレクトリを削除すればOKです。
レジストリなどは利用していません。

## ライセンス
[MIT](https://github.com/kuinaein/strelitzia/blob/release/LICENSE-ja.txt)
