# Laravel task-trecker

## Описание

REST API приложение для работы с задачами

## Требования

* Git
* Docker
* Docker Compose v2
* Taskfile - [https://taskfile.dev/docs/installation](https://taskfile.dev/docs/installation)  
  Рекомендуемая ОС - Linux.

## Переменные окружения (обязательные)

* `APP_PORT` - порт приложения (по умолчанию `8081`).
* `DB_PORT` - внутренний порт в сети Docker (`5432`). Не менять при стандартной конфигурации.
* `DB_EXTERNAL_PORT` - внешний проброшенный порт для доступа к БД с хоста (например, DataGrip).
* Остальные стандартные переменные Laravel: `APP_ENV`, `APP_KEY`, `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

## Быстрый старт (Linux)

Клонировать и перейти в репозиторий:

```bash
git clone https://github.com/emilversiontwo/Laravel-task-trecker.git
cd Laravel-task-trecker
````

Первичная настройка (сборка, зависимости, миграции, сиды):

```bash
task setup
```

Поднять окружение:

```bash
task up
```

Остановить и удалить контейнеры:

```bash
task down
```

## Доступ

- API: `http://localhost:8081/api` (при `APP_PORT=8081`).
- Swagger документация: `http://localhost:8081/swagger` (при `APP_PORT=8081`).
- При использывании Swagger измените `APP_URL=http://127.0.0.1:<APP_PORT>`

## Команды

- Запуск тестов:

```bash
task artisan -- test
```

- Выполнение произвольной artisan-команды:

```bash
task artisan -- <command>
```

- Выполнение произвольной composer-команды:

```bash
task composer -- <command>
```

## Работа с БД

- Внутренний хост: `db` (имя сервиса в docker compose).

- Внешнее подключение: `localhost:<DB_EXTERNAL_PORT>`.  
  При конфликте портов измените `DB_EXTERNAL_PORT`.

## Тестирование

Запуск тестов:

```bash
task artisan -- test
```

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
