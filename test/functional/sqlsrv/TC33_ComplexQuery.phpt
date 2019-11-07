--TEST--
Complex Query Test
--DESCRIPTION--
Verifies the behavior of INSERT queries with and without the IDENTITY flag set.
--ENV--
PHPT_EXEC=true
--SKIPIF--
<?php require('skipif_versions_old.inc'); ?>
--FILE--
<?php

require_once('MsCommon.inc');

function complexQuery()
{
    $testName = "Statement - Complex Query";
    startTest($testName);

    setup();
    $conn1 = AE\connect();

    $tableName = 'TC33test';
    $columns = array(new AE\ColumnMeta('int', 'c1_int', "IDENTITY"),
                     new AE\ColumnMeta('tinyint', 'c2_tinyint'),
                     new AE\ColumnMeta('smallint', 'c3_smallint'),
                     new AE\ColumnMeta('bigint', 'c4_bigint'),
                     new AE\ColumnMeta('varchar(512)', 'c5_varchar'));
    AE\createTable($conn1, $tableName, $columns);

    // SET IDENTITY_INSERT ON/OFF only works at execute or run time and not at parse time
    // because a prepared statement runs in a separate context
    // https://technet.microsoft.com/en-us/library/ms188059(v=sql.110).aspx
    $query = "SET IDENTITY_INSERT [$tableName] ON;";
    $stmt = sqlsrv_query($conn1, $query);
    if (!$stmt) {
        die("Unexpected execution outcome for \'$query\'.");
    }
    
    // expect this to pass
    $inputs = array("c1_int" => -204401468, "c2_tinyint" => 168, "c3_smallint" => 4787, "c4_bigint" =>1583186637, "c5_varchar" => "î<ÄäC~zããa.Oa._ß£*©<u_ßßCÃoa äãäÐßa+OühäobUa:zB_CÖ@~UÄz+ÃîÐ//Z@üo_:r,o¢ÃrßzoZß*ßªªå~ U¢a>£ÃZUÄ/ä_ZãðäåhüCã+/.obî|ößß,ð¢ðð:ÄÐ:*/>+/¢aö.öÄ<ð:>äO~*~ßÄzå¢<ª£ðý.O,>Ü,åbü@böhýC*<<hbÖä*o©¢h¢Ðüa+A/_@b/ÃBýBªß@ã~zÖZýC@äU_ßUßhvU*a@ÃðÄ:ªZAßAb£U_¢ßbãä:üåãorýÃßª_ãÐÖªzãðåãoaü <ß~zZªaB.+åA¢ãÖ><î:/Ur î¢UßåOaÄ:a|++ª©.r~:/+ä|©ýo++v_@BZ:©©AßCð.©/Ab<,îß>UãÜÜöbb|ßÐß£:î<<bîöa+,<_aÄ._ª>Ü<|ÖzÃz@>¢ª:a,CÜr__ª.<öÜCã+UÖU¢_üzü bÃ~ßo|, .î,b/U>äýaBZ@Ü£: bÖvýb>Ã/ÜÃ@üÖ/äb¢+r:Zß>ÐÜ|üu©ßZAC:Cßh *.Ã££_ýîu|Urå.:aAUv@u>@<Öü.<ãZ böZAÜÖ£oüÐä*,ü:ðä");
    $stmt = insertTest($conn1, $tableName, true, $inputs);

    $query = "SET IDENTITY_INSERT [$tableName] OFF;";
    $stmt = sqlsrv_query($conn1, $query);
    if (!$stmt) {
        die("Unexpected execution outcome for \'$query\'.");
    }

    // expect this to fail
    $inputs = array("c1_int" => 1264768176, "c2_tinyint" => 111, "c3_smallint" => 23449, "c4_bigint" =>1421472052, "c5_varchar" => "uå©C@bðUOv~,©v,BZÜ*oh>zb_åÐä<@*OOå_Ö<ãuß/oßr <ðãbÜUßÜÃÖÄ~¢~£ bÜ©î.uÜÐ¢ª:|_ÐüÄBÐbüåßÃv@,<CßOäv~:+,CZîvhC/oßUuößa<å>©/Ub,+AÐ©î:ÖrýB+~~ßßßãÜ+_<vO@ ßÃüÖîaCzÐîå@:rý.~vh~r.ÃbÃã©å_îCär BÖÜ:BbUväåöZ+|,CîaAöC,aîbb*UÜßßA hCu¢hOb ð|ßC.<C<.aBßvuÃÖå,AÐa>ABðöU/O<ÖãüªOãuß£~uÖ+ßÄrbî/:ÖÖo  /_ÃO:uÃzðUvã£Aã_BÐ/>UCr,Äå aÄÐaÃ£vÖZ@ªr*_::~/+.å~ð©aÄßbz*z<~î©ªrU~O+Z|A<_Büß©¢ö ::.Übýüßr/örh¢:ääU äOA~Aîr<¢äv¢Ä+hC/vßoUª+Oãªã*ð¢Bö.Zbh/ä,åä>*öðßUßý>aªbBbvßãÖ/bã|ýÖ u.zý©~äðzÐU.UA*a*.¢>î rß ~Cüßaö+rª~ß@aã/ÐCß*a,ªÄbb<o+v.åu<£B<îBZßåu£/_>*~");
    $stmt = insertTest($conn1, $tableName, false, $inputs);

    // expect this to pass
    $query = "SET IDENTITY_INSERT [$tableName] ON; SQL; SET IDENTITY_INSERT [$tableName] OFF;";
    if (AE\isColEncrypted()){
        // When AE is enabled, SQL types must be specified for sqlsrv_query
        $inputs = array("c1_int" => array(-411114769, null, null, SQLSRV_SQLTYPE_INT), 
                        "c2_tinyint" => array(198, null, null, SQLSRV_SQLTYPE_TINYINT),
                        "c3_smallint" => array(1378, null, null, SQLSRV_SQLTYPE_SMALLINT),
                        "c4_bigint" => array(140345831, null, null, SQLSRV_SQLTYPE_BIGINT), 
                        "c5_varchar" => array("Ü@ßaörÃªA*ÐüßA>_hOüv@|h~O<¢+*ÃÐCbazÜaåZ/Öö:ýãuöÐaz£ÐAh+u+rß:| U*¢ªåßÄÐ_vî@@~ChÐö_å*AAýBö¢B,ßbßå.ÃB+u*CAvÜ,ã>ªßCU<åî©ürz¢@ör¢*Öub¢BåaÜ@ª.äBv¢o~ ßýo oîu/>ÜÐÄ,ð,ðaOÖå>ðC:öZ>ßåð©<ð¢+£r.bO.©,uAßr><ov:,ÄßîåÃ+å./||CUÜÜ_ÖÄªh~<ã_å/hbý Ä©uBuß<Ö@boÖýBãCÜA/öÄ:© ßUü*ývuß.Bãååo_übýr_üß>ÐÃÜ£B¢AªvaîvýßCÜUß  åvöuª><îÐUC*aÖU©rªhr+>|äýî|oðröÐ£<ª<Ö|AªohäAî_vu~:~£Ãhü+ÃBuÄð ü@Z+Ä@hÖî¢|@bU£_ü/£ |:¢zb>@Uß©  Ãão Ö@ãÐBã_öBOBÄÐhCÜb~Ö>îü rýåüUzuãrbzß/ªîUÐð©uå.ß@£__vBb©/Ür¢Öuåz£ä*å£/*ÃO", null, null, SQLSRV_SQLTYPE_VARCHAR(512)));
        $stmt = insertTest($conn1, $tableName, true, $inputs, $query);
    } else {
        $inputs = array("c1_int" => -411114769, "c2_tinyint" => 198, "c3_smallint" => 1378, "c4_bigint" => 140345831, "c5_varchar" => "Ü@ßaörÃªA*ÐüßA>_hOüv@|h~O<¢+*ÃÐCbazÜaåZ/Öö:ýãuöÐaz£ÐAh+u+rß:| U*¢ªåßÄÐ_vî@@~ChÐö_å*AAýBö¢B,ßbßå.ÃB+u*CAvÜ,ã>ªßCU<åî©ürz¢@ör¢*Öub¢BåaÜ@ª.äBv¢o~ ßýo oîu/>ÜÐÄ,ð,ðaOÖå>ðC:öZ>ßåð©<ð¢+£r.bO.©,uAßr><ov:,ÄßîåÃ+å./||CUÜÜ_ÖÄªh~<ã_å/hbý Ä©uBuß<Ö@boÖýBãCÜA/öÄ:© ßUü*ývuß.Bãååo_übýr_üß>ÐÃÜ£B¢AªvaîvýßCÜUß  åvöuª><îÐUC*aÖU©rªhr+>|äýî|oðröÐ£<ª<Ö|AªohäAî_vu~:~£Ãhü+ÃBuÄð ü@Z+Ä@hÖî¢|@bU£_ü/£ |:¢zb>@Uß©  Ãão Ö@ãÐBã_öBOBÄÐhCÜb~Ö>îü rýåüUzuãrbzß/ªîUÐð©uå.ß@£__vBb©/Ür¢Öuåz£ä*å£/*ÃO");
        $stmt = insertTest($conn1, $tableName, true, $inputs, $query);
    }

    $stmt1 = selectFromTable($conn1, $tableName);
    $rowCount = rowCount($stmt1);
    sqlsrv_free_stmt($stmt1);

    if ($rowCount != 2) {
        die("Table $tableName has $rowCount rows instead of 2.");
    }

    dropTable($conn1, $tableName);

    sqlsrv_close($conn1);

    endTest($testName);
}

function insertTest($conn, $tableName, $expectedOutcome, $inputs, $query = null)
{
    $stmt = null;
    if (!AE\isColEncrypted()) {
        $insertSql = AE\getInsertSqlComplete($tableName, $inputs);
        if (! is_null($query)) {
            $sql = str_replace("SQL", $insertSql, $query);
        } else {
            $sql = $insertSql;
        }
        $stmt = sqlsrv_query($conn, $sql);
        $actualOutcome = ($stmt !== false);
    } else {
        // must bind parameters
        $insertSql = AE\getInsertSqlPlaceholders($tableName, $inputs);
        $params = array();
        foreach ($inputs as $key => $input) {
            array_push($params, $inputs[$key]);
        }
        if (! is_null($query)) {
            // this contains a batch of sql statements, 
            // with set identity_insert on or off 
            // thus, sqlsrv_query should be called
            $sql = str_replace("SQL", $insertSql, $query);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $actualOutcome = ($stmt !== false);
        } else {
            // just a regular insert, so use sqlsrv_prepare
            $sql = $insertSql;
            $actualOutcome = true;
            $stmt = sqlsrv_prepare($conn, $sql, $params);
            if ($stmt) {
                $result = sqlsrv_execute($stmt);
                $actualOutcome = ($result !== false);
            }
        }
    }
    if ($actualOutcome != $expectedOutcome) {
        die("Unexpected execution outcome for \'$sql\'.");
    }
}

try {
    complexQuery();
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
--EXPECT--
Test "Statement - Complex Query" completed successfully.
