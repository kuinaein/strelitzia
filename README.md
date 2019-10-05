# すとれりちあ

「すとれりちあ」はできたてほやほやの家計簿ソフトです。まだ、記帳と最終残高の表示くらいしかできません。。

## 重要なお知らせ

すとれりちあは [Re:すとれりちあ](https://github.com/kuinaein/re-strelitzia) に転生しました。

## 動作環境

（カッコ内は開発に使用しているバージョン）

- PHP 7 (7.2.x)
- PostgreSQL (10.x)
- Composer (1.x)
- Node.js (10.x)
- NPM (6.x)
- Yarn (1.x)

開発に使用している OS は Antergos (Arch Linux 系)、ブラウザは Chromium です。

## インストール

### システム側のセットアップ

1. `.env.example` を `.env` にコピーする。
1. PostgreSQL でユーザー `strelitzia`、データベース `strelitzia` を作成する。（DB 名等は `.env` で変更可能）<br/>
   `CREATE USER strelitzia WITH ENCRYPTED PASSWORD 'strelitzia';`<br/>
   `CREATE DATABASE strelitzia WITH OWNER strelitzia ENCODING 'UTF8' LC_COLLATE 'C' LC_CTYPE 'C' TEMPLATE template0;`
1. `composer install --no-dev` を実行する。
1. `php artisan key:generate` を実行する。
1. `php artisan stre:install` を実行する。

## 起動方法

1. `php artisan serve` を実行する。
1. ブラウザで http://localhost:8000/ を開く。 (ポート番号は `--port` オプションで変更可能)
1. 終了時はコンソールウィンドウをそのまま閉じれば OK。

## アップデート

1. データベースのバックアップを取る。<br/>
   `pg_dump -U strelitzia -b -Fc > storage/app/pg_dump`
1. `composer install --no-dev` を再度実行する。
1. `php artisan stre:update` を実行する。

## アンインストール

データベースとディレクトリを削除すれば OK です。
レジストリなどは利用していません。

## MSEdge テスト用仮想環境の構築

1. Vagrant、VirtualBox と Ansible (要 python-pywinrm) をインストールしておく。
1. [Free Virtual Machines from IE8 to MS Edge - Microsoft Edge Development](https://developer.microsoft.com/en-us/microsoft-edge/tools/vms/) から Vagrant 用の box をダウンロード及び解凍する。
1. `vagrant box add --name Microsoft/EdgeOnWindows10-${バージョン番号} '${boxファイルのパス}/MSEdge - Win10.box'`
   - バージョン番号が変わった場合は `strelitzia/vagrant/Vagrantfile` 中の `config.vm.box` も書き換えてください。
1. `strelitzia/vagrant` フォルダに移動する。
1. `vagrant up`
1. デフォルトでは画面は表示しないので、VirtualBox を起動して`strelitzia-edge-test`の画面を開く。
   - 念のためここでスナップショットを取ったほうが良いです。
   - 画面が真っ黒のままになる場合は、いったん「ACPI シャットダウン」して再度起動してください。
1. 「デバイス ＞ Guest Additions CD イメージの挿入」を実行し、インストールする。
1. 再起動後、CD イメージをイジェクトする。
1. ゲスト上で`\\vboxsvr\vagrant\pre-setup.bat`をデスクトップにでもコピーし、「Run as administrator」で実行する。
   - 英語キーボードの設定なので日本語キーボード上の「]」が「\」になります。
1. 「User Accounts」ダイアログが出たら、「Users must enter a username ...」のチェックを外して「Apply」ボタンをクリックし、自動ログイン有効に戻す。
   - パスワードは「Passw0rd!」です。（「!」はそのまま入力できます）
1. `vagrant reload`
   - 再起動できたらここまでの設定は OK。
1. ゲストの WinRM サービスが起動するまで待つか、手動で開始ししてしまう。
1. ホスト上で`cd ansible && ansible-playbook -i hosts setup.yml`
1. `vagrant reload`
1. ゲスト上で IE を起動し、適当に初期設定を行って閉じる。
1. Edge を起動し、2 個以上のタブを開いたあとに閉じて、「Always close all tabs」を有効にする。
1. ホスト上で`npm run test`を実行し、テストが走れば設定完了。

## ライセンス

[MIT](https://github.com/kuinaein/strelitzia/blob/release/LICENSE-ja.txt)
