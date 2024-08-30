## このAPIについて
Todoリストのタスクの作成・更新・削除などを行えるAPIです。

## インストール・起動方法
⚠︎***事前にdocker/docker-composeのインストールが必要です。***
```
git clone https://github.com/doglapping707/todo-api-php
cd todo-api-php
docker-compose up
docker-compose exec -it server bash
```

## .envの作成
1. `src`配下の`.env.example`をコピーし、同じ階層に`.env`と`.env.testing`を作成してください。
2. 下記を参考に環境変数の値を変更してください。
    ```
    APP_KEY=
    ~~略~~
    DB_CONNECTION=pgsql
    DB_HOST=
    DB_PORT=5432
    DB_DATABASE=postgres
    DB_USERNAME=root
    DB_PASSWORD=password
    ```
3. ```php artisan key:generate```を実行し生成したkeyを`APP_KEY`に設定してください。
    ```
    APP_KEY=base64:xxxxxxx
    ```
4. `DB_HOST`の値をファイル毎に設定してください。  
   *.env*
   ```
   DB_HOST=postgres-dev
   ```
   *.env.testing*
   ```
   DB_HOST=postgres-test
   ```
5. 下記の値をコピーし、ファイル毎に設定してください。
   ```
   SESSION_DOMAIN={フロント側のドメイン}（例: .example.com）※ローカル以外の場合のみ追加
   SANCTUM_STATEFUL_DOMAINS={フロント側のFQDN}（例: localhost:5173 or www.example.com）
   ```

## 認証API
### CSRF保護の初期化API
```
curl -i --request GET 'https://todo-api-php.onrender.com/api/sanctum/csrf-cookie'
```

### アカウント登録API
```bash
curl -i --request POST 'https://todo-api-php.onrender.com/api/register' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--header 'Cookie: {{ XSRF-TOKENのCookie }}' \
--data-raw '{
    "name": "admin",
    "email": "admin@example.com",
    "password": "123456789"
}'
```

### ログインAPI
```bash
curl -i --request POST 'https://todo-api-php.onrender.com/api/login' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--header 'Origin: {{ CORSの許可がされているURL }}' \
--header 'X-XSRF-TOKEN: 'X-XSRF-TOKEN:{{ XSRF-TOKEN }}' \
--header 'Cookie: {{ XSRF-TOKENのCookie }}' \
--data-raw '{
    "email": "admin@example.com",
    "password": "123456789"
}'
```

### ログアウトAPI
```bash
curl -i --request POST 'https://todo-api-php.onrender.com/api/logout' \
--header 'Accept: application/json' \
--header 'Origin: {{ CORSの許可がされているURL }}' \
--header 'Cookie: {{ XSRF-TOKENのCookie }}' \
```

## タスクAPI

⚠︎***実行にはアカウント登録APIまたはログインAPIでの認証が必要です。***

### ▼タスク一覧取得API
```bash
curl -i --request GET 'https://todo-api-php.onrender.com/api/tasks'
```

### ▼タスク登録API
```bash
curl -i --request POST 'https://todo-api-php.onrender.com/api/tasks' \
--header 'Content-Type: application/json' \
--data-raw '{
    "title": "Sample Task"
}'
```

### ▼タスク更新API
```bash
curl -i --request PUT 'https://todo-api-php.onrender.com/api/tasks/1' \
--header 'Content-Type: application/json' \
--data-raw '{
    "title": "Sample Task_2"
}'
```

### ▼ステータス更新API
```bash
curl -i --request PATCH 'https://todo-api-php.onrender.com/api/tasks/update-done/1' \
--header 'Content-Type: application/json' \
--data-raw '{
    "is_done": true
}'
```

### ▼タスク削除API
```bash
curl -i --request DELETE 'https://todo-api-php.onrender.com/api/tasks/1'
```

## 参考にしたサイト
[React.js + Laravel SPA開発講座](https://www.youtube.com/watch?v=hPjcbKtpTjY&list=PL3B2bjwrmhfQkcBEww0gN_kcRAHntAgxG&pp=iAQB)
