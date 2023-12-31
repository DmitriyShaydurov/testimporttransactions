# Тестовое задание

## Установка
Выполните установку проекта Laravel как обычно.

## Схема
Создайте необходимые миграции для хранения данных.

## ImporterService
Реализуйте `ImporterService`. Вы можете вносить любые необходимые изменения.

Ваша реализация должна использовать `$mapping` для нахождения нужного значения в csv файле. Это означает, что хардкодить позиции или названия колонок запрещено.

Обратите внимание, что ваша программа должна иметь возможность импортировать больше файлы, которые не смогут уместиться в памяти.

Стоит обратить внимание на следующее:
- Каждый Merchant может иметь много Batches, а каждый Batch - много Transactions
- Уникальный ключ Merchant: `Report::MERCHANT_ID`
- Уникальный ключ Batch - это комбинация `Report::MERCHANT_ID`, `Report::BATCH_DATE` и `Report::BATCH_REF_NUM`
- У транзакций нет уникального ключа
- Приветствуются оптимизации, которые позволят сэкономить память и выполнить как можно меньше запросов к БД

## Запросы
Напишите запросы в файле `queries.sql`.

# Описание Проекта

## Обзор
Проект представляет собой систему для импорта данных из CSV-файлов в базу данных. Данные организованы в три основные сущности: `Merchant`, `Batch`, и `Transaction`. Проект оптимизирован для работы с большими файлами и минимизации использования памяти.



## ImporterService
`ImporterService` является основным компонентом, который отвечает за обработку CSV-файлов. 
он использует несколько сервисов
- **CSVReader**: Компонент для чтения CSV-файла. С использованием библиотеки `League\Csv\Reader` файл читается построчно, что позволяет работать с файлами большого размера.
- **DataProcessor**: Компонент для обработки строк CSV. Преобразует строки CSV в структурированные данные для `Merchant`, `Batch`, и `Transaction`.
- **Data Inserter (dataInserter)**: Этот компонент отвечает за вставку данных (таких как merchants, batches, и transactions) в базу данных. Он использует метод `insertData`, который берет подготовленные массивы данных и вставляет их в соответствующие таблицы БД. Эта оптимизация позволяет группировать операции вставки, что может существенно уменьшить количество запросов к БД и улучшить общую производительность импорта.
- **Выбор `BUFFER_SIZE`**: Параметр `BUFFER_SIZE` контролирует количество записей, которые будут обработаны перед вставкой данных в базу данных. Подбор оптимального значения для этого параметра может зависеть от нескольких факторов, таких как размер памяти сервера, размеры обрабатываемых файлов и специфика БД. Маленькое значение может привести к частым, но менее затратным операциям вставки, в то время как большое значение может уменьшить количество запросов к БД, но потребовать больше оперативной памяти. Тестирование с различными значениями `BUFFER_SIZE` на целевом оборудовании может помочь найти оптимальный баланс между использованием памяти и производительностью.


## Оптимизации
- **Reading File Line-by-Line**: Вместо загрузки всего файла в память, CSV-файл читается построчно с использованием class `CSVReader`. Это обеспечивает возможность обработки очень больших файлов, которые могут не уместиться в памяти, так как в любой момент времени в памяти хранится только одна строка файла.
- **Batch Processing**: Данные обрабатываются пакетами, определяемыми размером BUFFER_SIZE. Пакетная обработка экономит память и ускоряет запись в БД.
- **Passing by Reference**: В `ImporterService`, массивы `$merchants`, `$batches`, и `$transactions` передаются по ссылке (`&$merchants`, `&$batches`, `&$transactions`) в метод `processRecord`. Это позволяет методу модифицировать оригинальные массивы без создания новых копий, что экономит память и делает код более эффективным.

## Overengineering

Этот проект является тестовым, и в его реализации было намеренно применено усложнение архитектуры. Принцип KISS  не применялся в данном случае, чтобы продемонстрировать определенные архитектурные и технические решения.

MappingService был одним из элементов, введенных в проект с намерением показать сложное взаимодействие и маппинг данных. Его необходимость может быть обоснована следующими аргументами:

1. **Гибкость**: MappingService позволяет настраивать маппинг полей CSV к полям БД, что может быть полезно в случае изменений в структуре данных.
2. **Повторное использование**: Логика маппинга выделена в отдельный сервис, что позволяет использовать её в разных частях проекта без дублирования кода.
3. **Тестирование**: Отделение логики маппинга в отдельный сервис упрощает тестирование этой части логики.

Этот подход может казаться избыточным для простого или малого проекта, но он может быть обоснован в контексте сложного приложения с разветвленной логикой и множеством зависимостей.

## CsvGeneratorController

`CsvGeneratorController` в текущем виде служит примером кода, который, вообще говоря, не следует рекомендациям по написанию хорошего кода. Он был написан на скорую руку исключительно в целях проверки и не является частью основного задания. Однако для приближения его к соответствию принципам SOLID, можно предложить следующие улучшения:

### 1. Выделение Логики Генерации CSV
Сейчас весь код, отвечающий за генерацию CSV, находится внутри контроллера. Это нарушает принцип единственной обязанности (SRP). Можно вынести эту логику в отдельный сервис.

### 2. Интерфейс для Генерации CSV
Введение интерфейса для генерации CSV облегчит замену реализации и улучшит соответствие принципу подстановки Барбары Лисков (LSP).

### 3. Использование Зависимости Через Конструктор
Для более гибкого управления зависимостями можно внедрять зависимости через конструктор, следуя принципу инверсии зависимостей (DIP).

### 4. Рефакторинг Методов Контроллера
Методы внутри контроллера должны быть чистыми и ответственными только за взаимодействие с HTTP-запросом и ответом. Вся бизнес-логика должна быть вынесена.

Эти улучшения приведут код `CsvGeneratorController` к соответствию с SOLID принципами и сделают его более чистым, гибким, и легко поддерживаемым.

# Установка и Запуск Проекта

## Клонирование Репозитория

Для начала работы с проектом вам необходимо склонировать репозиторий с GitHub, используя следующую команду:

```bash
git clone git@github.com:DmitriyShaydurov/testimporttransactions.git
```

## Установка Зависимостей

Перейдите в каталог проекта и установите необходимые зависимости, используя команду:

```bash
composer install
```

## Настройка Базы Данных

Не забудьте настроить соединение с базой данных, отредактировав файл `.env` в корне проекта. Обатите внимание на BUFFER_SIZE=1000 в `.env.example`.

## Запуск Миграций

Для создания необходимых таблиц в базе данных выполните миграции:

```bash
php artisan migrate
```


## Работа с Роутами

Проект включает два основных роута:

1. **Генерация CSV Файла**: Вы можете сгенерировать CSV файл, обратившись к роуту:

```
/generate-csv
```


2. **Импорт в Базу Данных**: После генерации CSV файла вы можете выполнить импорт данных в базу данных через роут:
```
/import
```

Теперь вы готовы работать с проектом!

## Заключение
Проект представляет собой эффективную и гибкую систему для импорта данных из CSV-файлов. Он оптимизирован для работы с большими файлами и построен с учетом принципов SOLID, что обеспечивает его надежность и легкость в обслуживании.

