# Estimate API

Це тестове завдання - API для розрахунку кошторису на Symfony 7 з використанням Docker.

---

## 🚀 Підняття проекту

### 1. Клонуємо репозиторій

```bash
git clone <your-repo-url> estimate-api
cd estimate-api
```

### 2. Запуск Docker
```bash
docker compose up -d --build
```

Контейнер symfony_app буде запущений з PHP 8.2 та Composer.
Сервер Symfony доступний за адресою: http://localhost:8000

### 3. Перевірка
```bash
docker ps
```
Переконайтеся, що контейнер symfony_app у статусі Up.

### 📦 API ```/api/estimate```
#### Метод: ```POST```
#### Content-Type: ```application/json```

```bash
{
  "works": [
    { "id": 1, "name": "Монтаж", "quantity": 10, "unitPrice": 100 },
    { "id": 2, "name": "Установка", "quantity": 5, "unitPrice": 200 }
  ],
  "materials": [
    { "id": 1, "name": "Кабель", "quantity": 20, "unitPrice": 10 },
    { "id": 2, "name": "Кріплення", "quantity": 50, "unitPrice": 2 }
  ],
  "adjustments": [
    { "type": "markup", "value": 10 },
    { "type": "discount", "value": 5 },
    { "type": "fixed_cost", "value": 100 }
  ]
}
```

#### Приклад запиту через curl

```bash
curl -X POST http://localhost:8000/api/estimate \
  -H "Content-Type: application/json" \
  -d '{
    "works": [
      { "id": 1, "name": "Монтаж", "quantity": 10, "unitPrice": 100 },
      { "id": 2, "name": "Установка", "quantity": 5, "unitPrice": 200 }
    ],
    "materials": [
      { "id": 1, "name": "Кабель", "quantity": 20, "unitPrice": 10 },
      { "id": 2, "name": "Кріплення", "quantity": 50, "unitPrice": 2 }
    ],
    "adjustments": [
      { "type": "markup", "value": 10 },
      { "type": "discount", "value": 5 },
      { "type": "fixed_cost", "value": 100 }
    ]
  }'
```

#### Приклад відповіді
```bash
{
  "totalWorks": 2000,
  "totalMaterials": 300,
  "subtotal": 2300,
  "adjustments": [
    { "type": "markup", "value": 10 },
    { "type": "discount", "value": 5 },
    { "type": "fixed_cost", "value": 100 }
  ],
  "total": 2395
}
```

### ⚙️ Валідація вхідних даних
* ```works``` и ```materials```:
  * ```id``` — integer, обов'язковий
  * ```name``` — string, обов'язковий
  * ```quantity``` и ```unitPrice``` — позитивні числа
* ```adjustments```:
  * ```type``` — одне з [```markup```, ```discount```, ```fixed_cost```]
* ```value``` — число

Якщо дані некоректні → HTTP 400 повертається з описом помилок.

### 🧩 Паттерн Strategy / Open-Closed Principle
Кожен тип ```Adjustment``` реалізований як окрема стратегія:
```bash
AdjustmentInterface
   ├─ MarkupAdjustment
   ├─ DiscountAdjustment
   └─ FixedCostAdjustment
```
* Розрахунок відбувається у сервісі EstimateCalculator
* Factory вибирає стратегію за типом надбавки
* Додавання нового типу Adjustment **не вимагає зміни існуючого коду**

#### Приклад додавання нового ```Adjustment```
1. Створюємо клас, що реалізує ```AdjustmentInterface```:
```bash
namespace App\Adjustment;

class TaxAdjustment implements AdjustmentInterface
{
    public function apply(float $amount, float $value): float
    {
        return $amount + ($amount * $value / 100);
    }
}
```
2. Додаємо в ```AdjustmentFactory```:
```bash
'tax' => new TaxAdjustment(),
```
Тепер можна використовувати новий тип у JSON:
```bash
{ "type": "tax", "value": 10 }
```

### 🧪 Запуск тестів
Всі тести проекту знаходяться в папці ```tests/```.
Оскільки проект працює в Docker, тести слід запускати всередині контейнера.
#### 1️⃣ Запуск усіх тестів однією командою
```docker exec -it symfony_app sh -c "cd /var/www/app && php vendor/bin/phpunit"```

💡 Що робить команда:
* ```docker exec -it symfony_app``` — заходить у працюючий контейнер із Symfony
* ```cd /var/www/app``` — переходимо в робочу директорію проекту
* ```php vendor/bin/phpunit``` — запускає PHPUnit, який автоматично знайде папку ```tests/``` і використовує ```phpunit.xml.dist```

#### 2️⃣ Перевірка успішності тестів
* Тести використовують валідні JSON дані для ```/api/estimate```
* Всі ```WebTestCase``` і ```TestCase``` проходять коректно
* Надбавки (```adjustments```) та розрахунок ```total``` перевіряються автоматично