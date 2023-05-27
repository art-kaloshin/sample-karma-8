# Karma 8
## Решение

### Описание решения
По условиям у нас 5 000 000+ пользователей, из них 20% имеют подписку. Примерно миллион. Таким образом в день в среднем будет отправляться 30-40 тысяч писем.
При задержке на отправку в 10 секунд мы одним сендером можем отправить примерно 8500 писем в день. Выход - шлём в несколько потоков.

Шаг 1. Выбираем всех пользователей которым надо сегодня послать уведомление.
Если пользователь подтвердил емейл, то его не проверяем. Иначе проверяем и результат сохраняем в БД (Если в первый раз натравить на БД, то получится затык, но в обычной эксплуатации почты будут проверяться достаточно равномерно и не большими количествами) Предполагаем, что сервис у нас работает давно и база набирается планомерно. В противном случае надо переписать валидацию емейлов по приципу отправки на фоновые процессы.

Шаг 2. Из собраных задач по уведомлениям формируем задачи для отправщика писем. Выделяем каждому отпавщику по 7000 уведомлений. Таким образом у нас получится 5-7 задач для отправщиков.

Шаг 3. Запускаем фоновые процессы для отпавки писем по сформированным на шаге 2 задачам.

### Описание скриптов

- **php init.php** - Создание таблиц
- **php seed.php** - Наполнение таблиц
- **php userProcessor.php** - Обработка пользователей для отправки почты выполняется раз в сутки. Cron: " 0 0 * * * ".
- **php jobProcessor.php** - Обработка почтовых задач, фактически выполняет задачи по отправке. Cron: " 0 30 * * *"
- **executeJob.php** - Занимается фоновой рассылкой сообщений пользователям.

Комментарии конечно прописаны, но в целом функции имеют понятные имена по всем правилам CleanCode

## Задание
### PHP Developer Test Cases

Вы разрабатываете сервис для рассылки уведомлений об истекающих подписках.

За один и за три дня до истечения срока подписки, нужно отправить письмо пользователю с текстом "{username}, your subscription is expiring soon".

### Имеем следующее
1. Таблица в DB с пользователями (5 000 000+ строк):
   - username: Имя пользователя
   - email: Емейл
   - validts: unix timestamp до которого действует ежемесячная подписка, либо 0 если подписки нет
   - confirmed: 0 или 1 в зависимости от того, подтвердил ли пользователь свой емейл по ссылке (пользователю после регистрации приходит письмо с уникальный ссылкой на указанный емейл, если он нажал на ссылку в емейле в этом поле устанавливается 1)
   - checked: Была ли проверка емейла на валидацию (1) или не было (0)
   - valid: Является ли емейл валидным (1) или нет (0)
2. Около 80% пользователей не имеют подписки.
3. Только 15% пользователей подтверждают свой емейл (поле confirmed).
4. Внешняя функция check_email( $email )
   Проверяет емейл на валидность (на валидный емейл письмо точно дойдёт) и возвращает 0 или 1. Функция работает от 1 секунды до 1 минуты. Вызов функции стоит 1 руб.
5. Функция send_email( $from, $to, $text )
   Отсылает емейл. Функция работает от 1 секунды до 10 секунд.

### Ограничения
1. Необходимо регулярно (два раза в месяц) отправлять емейлы об истечении срока подписки на те емейлы, на которые письмо точно дойдёт
2. Можно использовать cron
3. Можно создать необходимые таблицы в DB или изменить
   существующие
4. Для функций check_email и send_email нужно написать "заглушки"
5. По возможности, не стоит использовать ООП

### Преимуществом будет
1. Чистый и читаемый код
2. Комментарии к коду
3. Код размещенный в GitHub
   
Этим тестовым заданием мы хотим понять образ вашего мышления, и умение найти подход к решению задач.
