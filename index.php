<?php
error_reporting(E_ALL);

interface DatabaseConnectionInterface
{

    /**
     * Подключение к СУБД
     *
     * @param string $host         Адрес хоста
     * @param string $login        Логин
     * @param string $password     Пароль
     * @param string $databaseName Имя базы данных
     *
     * @return void
     */
    public function connect($host, $login, $password, $databaseName);

    /**
     * Получение объекта подключения к СУБД
     *
     * @returns \PDO
     * @throws \RuntimeException При отсутствии подключения к БД
     */
    public function getConnection();

}

class DBConnection implements DatabaseConnectionInterface 
{
    private $dbh;
    
    public function connect($host, $login, $password, $databaseName) 
    {
        try {
         $this->dbh = new PDO('mysql:host='.$host.';dbname='.$databaseName.';charset=UTF8', $login, $password);            
        } catch (PDOException $e) {
             echo $e->getMessage();
        }       

    }

    public function getConnection() 
    {
        if(!$this->dbh) {
            throw new Exception('Нет подключения');
        }
        return $this->dbh;
    }
}

class DataFromDB {
    public $query = 'SELECT pages.title AS section , goods.title AS title
        FROM goods join pages ON pages.id=goods.page
        WHERE pages.active != 0
        ORDER BY pages.sortorder, goods.sortorder;';

    public function printData($dbh) {
        try {
            $needle;
            foreach($dbh->query($this->query) as $row) {
               if (!(in_array($needle, $row))){
                echo $row['section']."\n";
               }
               $needle = $row['section'];
               echo "    ".$row['title']."\n";

           }
        } catch(Exception $e) {
            echo "Соединение не установлено";
        }
    }

}

class EvenNumbers {
    var $string = '';

    public function stringOfNum($startOfRange, $endOfRange) {
        if ($startOfRange%2!=0) ++$startOfRange;
        for ($i=$startOfRange; $i<=$endOfRange; $i+=2){
            $this->string .= $i.' ';
        }
        $this->string = trim($this->string).'\n';
        return $this->string;
    }

}


$host = '127.0.0.1';
$login = 'php-junior';
$password = 'php-junior';
$databaseName = 'phpJunior';

$connect = new DBConnection;
$connect->connect($host, $login, $password, $databaseName);
//$connect->getConnection();

$data = new DataFromDB;
$data->printData($connect->getConnection());