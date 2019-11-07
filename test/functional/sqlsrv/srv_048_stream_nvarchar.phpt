--TEST--
Streaming nvarchar(max) with sqlsrv_fetch_array()
--SKIPIF--
--FILE--
<?php

require_once("MsCommon.inc");

// connect
$conn = connect(array("CharacterSet"=>"utf-8"));
if (!$conn) {
    fatalError("Connection could not be established.\n");
}

// Create table
$sql = "CREATE TABLE #Table (c1 NVARCHAR(max))";
$stmt = sqlsrv_query($conn, $sql);
if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

// Insert data
$data = "Первые публикации об объектно-ориентированных базах данных появились в середине 80-х годов. Поддержка сложных объектов. В системе должна быть предусмотрена возможность создания составных объектов за счет применения конструкторов составных объектов. Необходимо, чтобы конструкторы объектов были ортогональны, то есть любой конструктор можно было применять к любому объекту. Поддержка индивидуальности объектов. Все объекты должны иметь уникальный идентификатор, который не зависит от значений их атрибутов. Поддержка инкапсуляции. Корректная инкапсуляция достигается за счет того, что программисты обладают правом доступа только к спецификации интерфейса методов, а данные и реализация методов скрыты внутри объектов. Поддержка типов и классов. Требуется, чтобы в ООБД поддерживалась хотя бы одна концепция различия между типами и классами. (Термин «тип» более соответствует понятию абстрактного типа данных. В языках программирования переменная объявляется с указанием её типа. Компилятор может использовать эту информацию для проверки выполняемых с переменной операций на совместимость с её типом, что позволяет гарантировать корректность программного обеспечения. С другой стороны класс является неким шаблоном для создания объектов и предоставляет методы, которые могут применяться к этим объектам. Таким образом, понятие «класс» в большей степени относится ко времени исполнения, чем ко времени компиляции.) Поддержка наследования типов и классов от их предков. Подтип, или подкласс, должен наследовать атрибуты и методы от его супертипа, или суперкласса, соответственно. Перегрузка в сочетании с полным связыванием. Методы должны применяться к объектам разных типов. Реализация метода должна зависеть от типа объектов, к которым данный метод применяется. Для обеспечения этой функциональности связывание имен методов в системе не должно выполняться до времени выполнения программы. Вычислительная полнота. Язык манипулирования данными должен быть языком программирования общего назначения. Набор типов данных должен быть расширяемым. Пользователь должен иметь средства создания новых типов данных на основе набора предопределенных системных типов. Более того, между способами использования системных и пользовательских типов данных не должно быть никаких различий.Первые публикации об объектно-ориентированных базах данных появились в середине 80-х годов. Поддержка сложных объектов. В системе должна быть предусмотрена возможность создания составных объектов за счет применения конструкторов составных объектов. Необходимо, чтобы конструкторы объектов были ортогональны, то есть любой конструктор можно было применять к любому объекту. Поддержка индивидуальности объектов. Все объекты должны иметь уникальный идентификатор, который не зависит от значений их атрибутов. Поддержка инкапсуляции. Корректная инкапсуляция достигается за счет того, что программисты обладают правом доступа только к спецификации интерфейса методов, а данные и реализация методов скрыты внутри объектов. Поддержка типов и классов. Требуется, чтобы в ООБД поддерживалась хотя бы одна концепция различия между типами и классами. (Термин «тип» более соответствует понятию абстрактного типа данных. В языках программирования переменная объявляется с указанием её типа. Компилятор может использовать эту информацию для проверки выполняемых с переменной операций на совместимость с её типом, что позволяет гарантировать корректность программного обеспечения. С другой стороны класс является неким шаблоном для создания объектов и предоставляет методы, которые могут применяться к этим объектам. Таким образом, понятие «класс» в большей степени относится ко времени исполнения, чем ко времени компиляции.) Поддержка наследования типов и классов от их предков. Подтип, или подкласс, должен наследовать атрибуты и методы от его супертипа, или суперкласса, соответственно. Перегрузка в сочетании с полным связыванием. Методы должны применяться к объектам разных типов. Реализация метода должна зависеть от типа объектов, к которым данный метод применяется. Для обеспечения этой функциональности связывание имен методов в системе не должно выполняться до времени выполнения программы. Вычислительная полнота. Язык манипулирования данными должен быть языком программирования общего назначения. Набор типов данных должен быть расширяемым. Пользователь должен иметь средства создания новых типов данных на основе набора предопределенных системных типов. Более того, между способами использования системных и пользовательских типов данных не должно быть никаких различий.";
$sql = "INSERT INTO #Table VALUES (N'$data')";
$stmt = sqlsrv_query($conn, $sql);
if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch
$sql = "SELECT * FROM #Table";
$stmt = sqlsrv_query($conn, $sql);
if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

// Compare output
$row = sqlsrv_fetch_array($stmt);
echo ($row['c1'] == $data) ? "True\n" : "False\n";

// Close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

print "Done";
?>

--EXPECT--
True
Done