## このAPIについて
Todoリストのタスクの作成・更新・削除などを行えるAPIです

## インストール・起動方法
⚠︎***事前にdocker/docker-composeのインストールが必要です***
```
git clone https://github.com/doglapping707/todo-api-php
cd todo-api-php
docker-compose up
docker-compose exec -it server bash
```

## API一覧
### ▼タスク一覧取得API
```
curl -i --request GET 'https://todo-api-php.onrender.com/api/tasks'
```

### ▼タスク登録API
```
curl -i --request POST 'https://todo-api-php.onrender.com/api/tasks' \
--header 'Content-Type: application/json' \
--data-raw '{
    "title": "Sample Task"
}'
```

### ▼ステータス更新API
```
curl -i --request PATCH 'https://todo-api-php.onrender.com/api/tasks/update-done/1' \
--header 'Content-Type: application/json' \
--data-raw '{
    "is_done": true
}'
```

## 参考にしたサイト
[React.js + Laravel SPA開発講座](https://www.youtube.com/watch?v=hPjcbKtpTjY&list=PL3B2bjwrmhfQkcBEww0gN_kcRAHntAgxG&pp=iAQB)
