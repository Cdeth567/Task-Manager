# Task-Manager
Technical specification for Skyeng iternship

## Как запустить

```bash
git clone https://github.com/Cdeth567/Task-Manager.git
cd task-manager-test
docker compose up -d --build
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

API будет доступен на `http://localhost:8000`.

Swagger UI: `http://localhost:8000/api/doc`

## Endpoint'ы и ожидаемые коды

| Метод | URL | Назначение | Основные ответы |
| --- | --- | --- | --- |
| POST | `/api/tasks` | создать задачу | 201, 422 |
| GET | `/api/tasks` | список задач | 200, 422, 404 |
| GET | `/api/tasks/{id}` | получить задачу | 200, 404 |
| DELETE | `/api/tasks/{id}` | удалить задачу | 204, 404 |
| PATCH | `/api/tasks/{id}/status` | сменить статус | 200, 404, 422 |
| GET | `/api/statuses` | список статусов | 200 |
| GET | `/api/statuses/{id}` | получить статус | 200, 404 |
| POST | `/api/statuses` | добавить статус | 201, 409, 422 |
| DELETE | `/api/statuses/{id}` | удалить статус | 204, 404, 409 |

## Архитектурные решения и компромиссы

1. `Task.status` - `ManyToOne` связь с сущностью `Status`.
2. Удаление статуса запрещено, если он используется задачами (возвращает `409 Conflict`).
3. Входящие JSON payloads валидируются отдельными DTO, контроллеры не содержат SQL и не управляют транзакциями напрямую.
4. Ответы API собираются через `ApiResponder`, чтобы формат JSON и даты были одинаковыми для всех endpoint'ов.
5. Для неожиданных/HTTP-исключений `/api/*` используется единый JSON-формат ошибки.
6. Для документации добавлен OpenAPI/Swagger через Nelmio API Doc.

## Что дальше

Для production я бы добавила пагинацию/сортировку, versioning API, rate limit, более глубокие доменные тесты, CI с `composer validate`, статическим анализом и прогоном PostgreSQL, а также отдельные prod secrets вместо dev-значений из compose.

## Использование ИИ

ChatGPT и Gemini использовался для обучения, правки на грамматические ошибки README и тестов. Ответы ИИ проверялись путём использования нескольких нейронок и сравнения, когда было необходимо.

## Сколько времени ушло

На выполнение задания ушло примерно 12 часов.

## Контакты кандидата

Фамилия и имя: **Колесникова Валерия**  
Почта: **11kvvkvv11@mail.ru**  
Telegram: **@Codekd**
