<?php

/**
* Запись истории вызова для API-шлюзов
*/
class Prodom_Api_History extends Prodom_Type {

    /**
    * имя метода
    * 
    * @var string
    */
    public $method = null;
    
    /**
    * затраченное время
    * 
    * @var float
    */
    public $elapsed = null;

    /**
    * код ответа
    * 
    * @var int
    */
    public $code = null;
    
    public static function dump() {
        ?>
        <!doctype html>
        <html>
        <head>
            <style>
                body {font-family: "Arial";}
                table {
                    border-collapse: collapse;
                }
                table, th, td {
                    border: 1px solid #bbb;
                }
                th, td {
                    padding: .3em;
                }
                th {
                    background: #bbb;
                    color: #fff;
                }
                td {
                    font-family: "Courier new";
                }
            </style>
        </head>
        <body>
        <table border="1">
            <thead>
                <tr>
                    <th>Метод</th>
                    <th>Код ответа</th>
                    <th>Затраченное время</th>
                </tr>
            </thead>
            <tbody>
                <?$history = Prodom_Api_Client::__getHistory()?>
                <?foreach($history as $record){?>
                    <tr>
                        <td><?=$record->method?></td>
                        <td><?=$record->code?></td>
                        <td><?=$record->elapsed?></td>
                    </tr>
                <?}?>
            </tbody>
        </table>
        </body>
        </html>
        <?
        die();
    }

}