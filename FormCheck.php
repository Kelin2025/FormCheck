<?
  /* FormCheck.Class.php
   * @author webmaster.kelin@yandex.ru
   * @link https://github.com/Kelin2025/FormCheck
  */

  Class FormCheck {
    public $input = [];         // Массив входных данных
    public $errors = [];        // Массив ошибок
    public $field = '';         // Текущее поле
    public $key = '';           // Текущий ключ в массиве
    public $is_empty = false;   // Пусто или нет

    // Записываем входные данные
    public function __construct($input){
      $this->input = $input;
    }

    // Выбираем поле, с которым будем работать
    public function check($field,$key,$must_be_filled=true){
      $this->field = $field;
      $this->key = $key;
      if($this->has_key($key)){
        $this->is_empty = false;
      }
      else {
        $this->is_empty = true;
        if($must_be_filled == true) $this->add_error('не может быть пустым');
      }
      return $this;
    }

    // Проверка по типу
    public function type($type){
      if($this->is_empty == true) return $this;
      switch($type){
        case 'login':
          $match = '/^([A-Za-z0-9_]+)$/';
          $error = 'должно содержать только английские буквы, цифры и символ нижнего подчеркивания.';
          break;
        case 'name':
          $match = '/^([A-Za-zа-яА-ЯёЁ-\s]+)$/u';
          $error = 'должно содержать только английские или русские буквы, символ пробела и дефис.';
          break;
        case 'password':
          $match = '/^(?=.*[a-zA-Z]|\d)(?=.*[a-zA-Z]|\W+)(?!.*\s).*$/';
          $error = 'может состоять из английских букв, цифр и спецсимволов. Запрещено использовать только цифры.';
          break;
        case 'password-hard':
          $match = '/^(?=^.*$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
          $error = 'должно содержать как минимум одну цифру, одну заглавную и одну строчную букву английского алфавита и один спецсимвол.';
          break;
        case 'int':
          $match = '/^([1-9][0-9]*)$/';
          $error = 'должно быть целым числом.';
          break;
        case 'float':
          $match = '/\-?\d+(\.\d{0,})?/';
          $error = 'должно быть дробным числом.';
          break;
        case 'date':
          $match = '/^(19|20)\d\d-((0[1-9]|1[012])-(0[1-9]|[12]\d)|(0[13-9]|1[012])-30|(0[13578]|1[02])-31)$/';
          $error = 'должно содержать дату в формате YYYY-MM-DD.';
          break;
        case 'time':
          $match = '/^([0-1]\d|2[0-3])(:[0-5]\d){2}$/';
          $error = 'должно содержать время в формате HH:MM:SS';
          break;
        case 'email':
          $match = '/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/';
          $error = 'должно содержать корректный почтовый ящик.';
          break;
        case 'words':
          $match = '/^([а-яА-ЯёЁa-zA-Z0-9\s]*)$/u';
          $error = 'должно содержать слова без знаков препинания и цифр.';
          break;
        case 'uuid':
          $match = '/^[0-9A-Fa-f]{8}\-[0-9A-Fa-f]{4}\-[0-9A-Fa-f]{4}\-[0-9A-Fa-f]{4}\-[0-9A-Fa-f]{12}$/';
          $error = 'должно содержать корректный UUID.';
          break;
        case 'mac':
          $match = '/^([0-9a-fA-F]{2}([:-]|$)){6}$|([0-9a-fA-F]{4}([.]|$)){3}$/';
          $error = 'должно содержать корректный MAC-адрес.';
          break;
        case 'phone':
          $match = '/^[\+]\d{1,3}[\(]\d{3}[\)]\d{3}[\-]\d{2}[\-]\d{2}$/';
          $error = 'должно содержать номер в формате +9(999)999-99-99';
          break;
        case 'color':
          $match = '/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/';
          $error = 'должно содержать цвет в HEX-формате (#ccc, #f5f5f5)';
          break;
        case 'ipv4':
          $match = '/^((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$/';
          $error = 'должно содержать корректный IPv4-адрес (255.255.255.255).';
          break;
        case 'ipv6':
          $amtch = '/^((^|:)([0-9a-fA-F]{0,4})){1,8}$/';
          $error = 'должно содержать корректный IPv6-адрес (2001:db8:0:0:0:ff00:42:8329).';
          break;
      }
      if(!preg_match($match,$this->input[$this->key])) $this->add_error($error);
      return $this;
    }

    // Регулярное выражение
    public function reg($regexp){
      if($this->is_empty == true) return $this;
      if(!preg_match($regexp,$this->input[$this->key])) $this->add_error('содержит недопустимые символы');
      return $this;
    }

    // Минимальная длина
    public function min($length){
      if($this->is_empty == true) return $this;
      if(strlen($this->input[$this->key]) < $length) $this->add_error('не должно быть короче '.$length.' символов.');
      return $this;
    }

    // Максимальная длина
    public function max($length){
      if($this->is_empty == true) return $this;
      if(strlen($this->input[$this->key]) > $length) $this->add_error('не должно быть длиннее '.$length.' символов.');
      return $this;
    }

    // Идентичность
    public function equal($field,$key){
      if($this->is_empty == true) return $this;
      if($this->errors[$this->key] !== $this->errors[$key]) $this->add_error('не совпадает с полем "'.$field.'"');
      return $this;
    }

    // Кастомная проверка
    public function custom($callback){
      if($this->is_empty == true) return $this;
      call_user_func($callback,$this,$this->input[$this->key],$this->field);
      return $this;
    }

    // Добавляем ошибку
    public function add_error($error){
      if(!isset($this->errors[$this->key])) $this->errors[$this->key] = [];
      $this->errors[$this->key][] = 'Поле "'.$this->field.'" '.$error;
    }

    // Возвращаем массив ошибок
    // Если нет аргумента - возвращаем массив, в котором для каждого поля отдельный массив ошибок
    // Если есть - возвращаем массив, в котором все ошибки в одном массиве
    public function get_errors($in_one_array=false){
      if($in_one_array == false) return $this->errors;
      $arr = [];
      foreach($this->errors as $field){
        $arr = array_merge($arr,$field);
      }
      return $arr;
    }

    // Проверить наличие элемента в массиве
    public function has_key($key){
      return isset($this->input[$key]) ? true : false;
    }

    // Проверить наличие элементов в массиве
    public function is_empty(){
      return !is_array($this->input) || count($this->input) == 0 ? true : false;
    }

    // Проверить наличие ошибок
    public function has_errors(){
      return count($this->errors) > 0 ? true : false;
    }

  }

?>
