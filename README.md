There are a couple of useful classes which can help to work with yii framework

Now there are presented only one class Debug

You can use it in any code of project safely

```PHP
<?php

$int = 5;
$str = 'Hi';
$array = ['123', 232, 'test'];
$obj = new StdClass();
$obj->testprop = 12;


// yii 1
Debug::bc($int, $str, $array, $obj);

// yii 2
\app\components\Debug::bc($int, $str, $array, $obj);


```

Open browser console (developed for Google Chrome, so other browser console representation can be shown incorrect) 