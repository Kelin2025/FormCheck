# О FormCheck
FormCheck - простой PHP-класс для проверки корректности введенных данных.

Небольшой пример проверки $_POST-данных из формы:

    $form = new FormCheck($_POST);
    $form
     ->check('Логин','login')->type('login')->min(3)->max(50)
     ->check('Пароль','password')->type('password')->min(3)->max(50)
     ->check('Повтор пароля','password2')->equal('password')
     ->check('Почтовый ящик','email')->type('mail')->max(100)
      ->check('Имя','name')->type('name')->min(2)->max(50)
     ->check('Фамилия','surname')->type('name')->min(2)->max(50)
     ->check('Отчество','lastname')->type('name')->min(2)->max(50);
    print_r($form->errors);`
Результат:
  array (size=7)
  'login' => 
    array (size=2)
      0 => string 'Поле "Логин" должно содержать только английские буквы, цифры и символ нижнего подчеркивания.' (length=169)
      1 => string 'Поле "Логин" не должно быть короче 3 символов.' (length=81)
  'password' => 
    array (size=1)
      0 => string 'Поле "Пароль" содержит недопустимые символы.' (length=81)
  'password2' => 
    array (size=1)
      0 => string 'Поле "Повтор пароля" не совпадает с полем "Пароль"' (length=89)
  'email' => 
    array (size=1)
      0 => string 'Поле "Почтовый ящик" содержит недопустимые символы.' (length=94)
  'name' => 
    array (size=1)
      0 => string 'Поле "Имя" должно содержать только английские или русские буквы, символ пробела и дефис.' (length=160)
  'surname' => 
    array (size=1)
      0 => string 'Поле "Фамилия" должно содержать только английские или русские буквы, символ пробела и дефис.' (length=168)
  'test' => 
    array (size=1)
      0 => string 'Поле "Тест" равно 111' (length=34)
