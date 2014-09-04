<?php

	// 2014-09-04
	session_start();
	error_reporting(-1);

?>
<!DOCTYPE html>
	<html>
	<head>
	    <title>Seitenverwaltung</title>
	    <meta charset="UTF-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <style>
	    	#container
	    	{
	    		width: 70%;
	    		margin:5em auto;
				font-family:'Helvetica Neue', Helvetica, Arial, Verdana, sans-serif;
	    		font-size:12px;
	    		color:#444;
	    		font-weight:300;
	    	}
	    	table
	    	{
	    		margin: 0 auto;
	    	}
	    	table,
	    	td,
	    	th
	    	{
	    		border: 1px solid #666;
	    		padding: 10px 10px 10px 10px;
	    	}
	    	th,
	    	td
	    	{
	    		min-width: 200px;
	    	}
	    	a:link,
	    	a:active,
	    	a:visited
	    	{
	    		text-decoration: none;
	    		color:#444;
	    	}
	    	a:hover
	    	{
	    		text-decoration: none;
	    		color:#ff0066;
	    	}
	    </style>
	</head>
		<body>
		
			<div id="container">
				<?php
		
					/*
					 * OPEN DATABASE CONNECTION START
					 * */
				    try
				    {
				        $DBCON = new PDO('mysql:host=localhost;dbname=seitenverwaltung','root','root',array(PDO::ATTR_PERSISTENT => true));
				        $DBCON -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				    }
				    catch (PDOException $e)
				    {
						die('<p>Fehler bei der Datenbankverbindung!</p>');
				    }
					/*
					 * OPEN DATABASE CONNECTION START
					 * */
		
					/*
					 * SELECT ALL PAGES START
					 * */
				    try
				    {
						$PAGES 			= $DBCON -> query('SELECT * FROM seitenverwaltung ORDER BY page_order;');
						$PAGES_COUNTR 	= 0;
						$PAGES_COUNTR 	= $PAGES -> rowCount();
				    }
				    catch(PDOException $e)
				    {
						die('<p>Fehler beim auslesen aller Seiten!</p>');
				    }
					/*
					 * SELECT ALL PAGES START
					 * */
		
					/*
					 * DISPLAY THE NEW PAGE FORM START
					 * */
					if(isset($_GET['create_new_page']) && $_GET['create_new_page'] == 'true')
					{
						echo '<h3>Eine neue Seite anlegen</h3>';
						echo '<form action="./index.php" method="post" enctype="application/x-www-form-urlencoded" autocomplete="off">';
						echo '<input type="hidden" name="create_new_page" value="try">';
						echo '<input type="hidden" name="pages_countr" value="' . $PAGES_COUNTR . '">';
						echo '<br>Name:<br><input type="text" name="new_page_name" id="new_page_name" size="50" required="required" autofocus><br>';

						if($PAGES_COUNTR > 0)
						{
							echo '<br>Neue Seite einordnen:<br><select type="text" name="new_page_order" id="new_page_order">';
							echo '<option value="">Bitte w&auml;hlen</option>';
							echo '<option value="1">vor Seite</option>';
							echo '<option value="2">als Unterseite von</option>';
							echo '<option value="3">nach Seite</option>';
							echo '</select><br>';
							echo '<select type="text" name="new_page_order_page_id" id="new_page_order_page_id">';
							echo '<option value="">Bitte w&auml;hlen</option>';

								foreach($PAGES as $PAGE1)
								{
									echo '<option value="' . $PAGE1['page_id'] . '">' . $PAGE1['page_name'] . '</option>';
								}

							echo '</select><br>';

						}
						else
						{
							echo '<input type="hidden" name="new_page_order" id="new_page_order" value="">';
							echo '<input type="hidden" name="new_page_order_page_id" id="new_page_order_page_id" value="0">';
						}

						echo '<br><input type="submit" value="Seite anlegen">';
						echo '</form>';
						echo '<br><br><br><br>';
					}	
					/*
					 * DISPLAY THE NEW PAGE FORM END
					 * */

					/*
					 * POSTPROCESSING CREATE NEW PAGE START
					 * */
					 if(isset($_POST['create_new_page']) && $_POST['create_new_page'] == 'try')
					 {
						$_NEW_PAGE_ORDER = $_POST['new_page_order'];
						$_NEW_PAGE_ORDER_PAGE_ID = $_POST['new_page_order_page_id'];

						if($_POST['pages_countr'] > 0)
						{
							if($_NEW_PAGE_ORDER == '')
							{
								$_ALL_PAGES_COUNTR = $_POST['pages_countr'];
								$_NEW_PAGE_ID = NULL;
								$_NEW_PAGE_MOTHER = 0;
								$_NEW_PAGE_LEVEL = 1;
								$_NEW_PAGE_ORDER = ($_ALL_PAGES_COUNTR + 1);
								$_NEW_PAGE_DATETIME = date('Y-m-d H:i:s');
								$_NEW_PAGE_NAME = $_POST['new_page_name'];
								$_NEW_PAGE_URL = 'PAGE URL';
								$_NEW_PAGE_TEMPLATE = 'PAGE TEMPLATE';
								$_NEW_PAGE_TITLE = 'PAGE TITLE';
								$_NEW_PAGE_DESCRIPTION = 'PAGE DESCRIPTION';
								$_NEW_PAGE_KEYWORDS = 'PAGE KEYWORDS';
								$_NEW_PAGE_ACTIVE = 1;
								$_NEW_PAGE_NAV_VISIBLE = 1;
								$_NEW_PAGE_TEMP_MARKER = 0;
	
							    try
							    {
									$QUERY = 'INSERT INTO seitenverwaltung SET page_mother = :NEW_PAGE_MOTHER, page_level = :NEW_PAGE_LEVEL, page_order = :NEW_PAGE_ORDER, page_datetime = :NEW_PAGE_DATETIME, page_name = :NEW_PAGE_NAME, page_url = :NEW_PAGE_URL, page_template = :NEW_PAGE_TEMPLATE, page_title = :NEW_PAGE_TITLE, page_description = :NEW_PAGE_DESCRIPTION, page_keywords = :NEW_PAGE_KEYWORDS, page_active = :NEW_PAGE_ACTIVE, page_nav_visible= :NEW_PAGE_NAV_VISIBLE, page_temp_marker= :NEW_PAGE_TEMP_MARKER;';
					                $STMT = $DBCON -> prepare($QUERY);
									$STMT -> bindParam(':NEW_PAGE_MOTHER', $_NEW_PAGE_MOTHER, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_LEVEL', $_NEW_PAGE_LEVEL, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_ORDER', $_NEW_PAGE_ORDER, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_DATETIME', $_NEW_PAGE_DATETIME, \PDO::PARAM_STR);
					                $STMT -> bindParam(':NEW_PAGE_NAME', $_NEW_PAGE_NAME, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_URL', $_NEW_PAGE_URL, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_TEMPLATE', $_NEW_PAGE_TEMPLATE, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_TITLE', $_NEW_PAGE_TITLE, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_DESCRIPTION', $_NEW_PAGE_DESCRIPTION, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_KEYWORDS', $_NEW_PAGE_KEYWORDS, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_ACTIVE', $_NEW_PAGE_ACTIVE, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_NAV_VISIBLE', $_NEW_PAGE_NAV_VISIBLE, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_TEMP_MARKER', $_NEW_PAGE_TEMP_MARKER, \PDO::PARAM_INT);
					                $STMT -> execute();
	
									echo '<p><strong>Die Seite wurde erfolgreich erstellt | <a href="./index.php">OK</a></strong></p>';
							    }
							    catch(PDOException $e)
							    {
									die('<p>1) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
							    }
							}
							else if($_NEW_PAGE_ORDER == '1' && $_NEW_PAGE_ORDER_PAGE_ID != '')
							{
							    try
							    {
									$QUERY = 'SELECT page_order, page_level FROM seitenverwaltung WHERE page_id = :PAGE_ID LIMIT 1;';
					                $STMT = $DBCON -> prepare($QUERY);
									$STMT -> bindParam(':PAGE_ID', $_NEW_PAGE_ORDER_PAGE_ID, \PDO::PARAM_INT);
					                $STMT -> execute();
									$RES = $STMT -> fetch();

									$QUERY2 = 'UPDATE seitenverwaltung SET page_order = (page_order + 1) WHERE page_order >= :ACT_PAGE_ORDER;';
					                $STMT2 = $DBCON -> prepare($QUERY2);
									$STMT2 -> bindParam(':ACT_PAGE_ORDER', $RES['page_order'], \PDO::PARAM_INT);
					                $STMT2 -> execute();

									$_ALL_PAGES_COUNTR = $_POST['pages_countr'];
									$_NEW_PAGE_ID = NULL;
									$_NEW_PAGE_MOTHER = 0;
									$_NEW_PAGE_LEVEL = $RES['page_level'];
									$_NEW_PAGE_ORDER = $RES['page_order'];
									$_NEW_PAGE_DATETIME = date('Y-m-d H:i:s');
									$_NEW_PAGE_NAME = $_POST['new_page_name'];
									$_NEW_PAGE_URL = 'PAGE URL';
									$_NEW_PAGE_TEMPLATE = 'PAGE TEMPLATE';
									$_NEW_PAGE_TITLE = 'PAGE TITLE';
									$_NEW_PAGE_DESCRIPTION = 'PAGE DESCRIPTION';
									$_NEW_PAGE_KEYWORDS = 'PAGE KEYWORDS';
									$_NEW_PAGE_ACTIVE = 1;
									$_NEW_PAGE_NAV_VISIBLE = 1;
									$_NEW_PAGE_TEMP_MARKER = 0;

								    try
								    {
										$QUERY3 = 'INSERT INTO seitenverwaltung SET page_mother = :NEW_PAGE_MOTHER, page_level = :NEW_PAGE_LEVEL, page_order = :NEW_PAGE_ORDER, page_datetime = :NEW_PAGE_DATETIME, page_name = :NEW_PAGE_NAME, page_url = :NEW_PAGE_URL, page_template = :NEW_PAGE_TEMPLATE, page_title = :NEW_PAGE_TITLE, page_description = :NEW_PAGE_DESCRIPTION, page_keywords = :NEW_PAGE_KEYWORDS, page_active = :NEW_PAGE_ACTIVE, page_nav_visible= :NEW_PAGE_NAV_VISIBLE, page_temp_marker= :NEW_PAGE_TEMP_MARKER;';
						                $STMT3 = $DBCON -> prepare($QUERY3);
										$STMT3 -> bindParam(':NEW_PAGE_MOTHER', $_NEW_PAGE_MOTHER, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_LEVEL', $_NEW_PAGE_LEVEL, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_ORDER', $_NEW_PAGE_ORDER, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_DATETIME', $_NEW_PAGE_DATETIME, \PDO::PARAM_STR);
						                $STMT3 -> bindParam(':NEW_PAGE_NAME', $_NEW_PAGE_NAME, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_URL', $_NEW_PAGE_URL, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_TEMPLATE', $_NEW_PAGE_TEMPLATE, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_TITLE', $_NEW_PAGE_TITLE, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_DESCRIPTION', $_NEW_PAGE_DESCRIPTION, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_KEYWORDS', $_NEW_PAGE_KEYWORDS, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_ACTIVE', $_NEW_PAGE_ACTIVE, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_NAV_VISIBLE', $_NEW_PAGE_NAV_VISIBLE, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_TEMP_MARKER', $_NEW_PAGE_TEMP_MARKER, \PDO::PARAM_INT);
						                $STMT3 -> execute();

										echo '<p><strong>Die Seite wurde erfolgreich erstellt | <a href="./index.php">OK</a></strong></p>';
								    }
								    catch(PDOException $e)
								    {
										die('<p>2) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
								    }
							    }
							    catch(PDOException $e)
							    {
									die('<p>3) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
							    }
							}
							else if($_NEW_PAGE_ORDER == '2' && $_NEW_PAGE_ORDER_PAGE_ID != '')
							{
							    try
							    {
									$QUERY = 'SELECT page_order, page_level FROM seitenverwaltung WHERE page_id = :PAGE_ID LIMIT 1;';
					                $STMT = $DBCON -> prepare($QUERY);
									$STMT -> bindParam(':PAGE_ID', $_NEW_PAGE_ORDER_PAGE_ID, \PDO::PARAM_INT);
					                $STMT -> execute();
									$RES = $STMT -> fetch();

									$QUERY2 = 'UPDATE seitenverwaltung SET page_order = (page_order + 1) WHERE page_order > :ACT_PAGE_ORDER;';
					                $STMT2 = $DBCON -> prepare($QUERY2);
									$STMT2 -> bindParam(':ACT_PAGE_ORDER', $RES['page_order'], \PDO::PARAM_INT);
					                $STMT2 -> execute();

									$_ALL_PAGES_COUNTR = $_POST['pages_countr'];
									$_NEW_PAGE_ID = NULL;
									$_NEW_PAGE_MOTHER = $_NEW_PAGE_ORDER_PAGE_ID;
									$_NEW_PAGE_LEVEL = ($RES['page_level'] + 1);
									$_NEW_PAGE_ORDER = ($RES['page_order'] + 1);
									$_NEW_PAGE_DATETIME = date('Y-m-d H:i:s');
									$_NEW_PAGE_NAME = $_POST['new_page_name'];
									$_NEW_PAGE_URL = 'PAGE URL';
									$_NEW_PAGE_TEMPLATE = 'PAGE TEMPLATE';
									$_NEW_PAGE_TITLE = 'PAGE TITLE';
									$_NEW_PAGE_DESCRIPTION = 'PAGE DESCRIPTION';
									$_NEW_PAGE_KEYWORDS = 'PAGE KEYWORDS';
									$_NEW_PAGE_ACTIVE = 1;
									$_NEW_PAGE_NAV_VISIBLE = 1;
									$_NEW_PAGE_TEMP_MARKER = 0;

								    try
								    {
										$QUERY3 = 'INSERT INTO seitenverwaltung SET page_mother = :NEW_PAGE_MOTHER, page_level = :NEW_PAGE_LEVEL, page_order = :NEW_PAGE_ORDER, page_datetime = :NEW_PAGE_DATETIME, page_name = :NEW_PAGE_NAME, page_url = :NEW_PAGE_URL, page_template = :NEW_PAGE_TEMPLATE, page_title = :NEW_PAGE_TITLE, page_description = :NEW_PAGE_DESCRIPTION, page_keywords = :NEW_PAGE_KEYWORDS, page_active = :NEW_PAGE_ACTIVE, page_nav_visible= :NEW_PAGE_NAV_VISIBLE, page_temp_marker= :NEW_PAGE_TEMP_MARKER;';
						                $STMT3 = $DBCON -> prepare($QUERY3);
										$STMT3 -> bindParam(':NEW_PAGE_LEVEL', $_NEW_PAGE_LEVEL, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_MOTHER', $_NEW_PAGE_MOTHER, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_ORDER', $_NEW_PAGE_ORDER, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_DATETIME', $_NEW_PAGE_DATETIME, \PDO::PARAM_STR);
						                $STMT3 -> bindParam(':NEW_PAGE_NAME', $_NEW_PAGE_NAME, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_URL', $_NEW_PAGE_URL, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_TEMPLATE', $_NEW_PAGE_TEMPLATE, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_TITLE', $_NEW_PAGE_TITLE, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_DESCRIPTION', $_NEW_PAGE_DESCRIPTION, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_KEYWORDS', $_NEW_PAGE_KEYWORDS, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_ACTIVE', $_NEW_PAGE_ACTIVE, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_NAV_VISIBLE', $_NEW_PAGE_NAV_VISIBLE, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_TEMP_MARKER', $_NEW_PAGE_TEMP_MARKER, \PDO::PARAM_INT);
						                $STMT3 -> execute();

										echo '<p><strong>Die Seite wurde erfolgreich erstellt | <a href="./index.php">OK</a></strong></p>';
								    }
								    catch(PDOException $e)
								    {
										die('<p>4) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
								    }
							    }
							    catch(PDOException $e)
							    {
									die('<p>5) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
							    }
							}
							else if($_NEW_PAGE_ORDER == '3' && $_NEW_PAGE_ORDER_PAGE_ID != '')
							{
							    try
							    {
									$QUERY = 'SELECT page_order, page_level FROM seitenverwaltung WHERE page_id = :PAGE_ID LIMIT 1;';
					                $STMT = $DBCON -> prepare($QUERY);
									$STMT -> bindParam(':PAGE_ID', $_NEW_PAGE_ORDER_PAGE_ID, \PDO::PARAM_INT);
					                $STMT -> execute();
									$RES = $STMT -> fetch();

									$QUERY2 = 'UPDATE seitenverwaltung SET page_order = (page_order + 1) WHERE page_order > :ACT_PAGE_ORDER;';
					                $STMT2 = $DBCON -> prepare($QUERY2);
									$STMT2 -> bindParam(':ACT_PAGE_ORDER', $RES['page_order'], \PDO::PARAM_INT);
					                $STMT2 -> execute();

									$_ALL_PAGES_COUNTR = $_POST['pages_countr'];
									$_NEW_PAGE_ID = NULL;
									$_NEW_PAGE_MOTHER = 0;
									$_NEW_PAGE_LEVEL = $RES['page_level'];
									$_NEW_PAGE_ORDER = ($RES['page_order'] + 1);
									$_NEW_PAGE_DATETIME = date('Y-m-d H:i:s');
									$_NEW_PAGE_NAME = $_POST['new_page_name'];
									$_NEW_PAGE_URL = 'PAGE URL';
									$_NEW_PAGE_TEMPLATE = 'PAGE TEMPLATE';
									$_NEW_PAGE_TITLE = 'PAGE TITLE';
									$_NEW_PAGE_DESCRIPTION = 'PAGE DESCRIPTION';
									$_NEW_PAGE_KEYWORDS = 'PAGE KEYWORDS';
									$_NEW_PAGE_ACTIVE = 1;
									$_NEW_PAGE_NAV_VISIBLE = 1;
									$_NEW_PAGE_TEMP_MARKER = 0;

								    try
								    {
										$QUERY3 = 'INSERT INTO seitenverwaltung SET page_mother = :NEW_PAGE_MOTHER, page_level = :NEW_PAGE_LEVEL, page_order = :NEW_PAGE_ORDER, page_datetime = :NEW_PAGE_DATETIME, page_name = :NEW_PAGE_NAME, page_url = :NEW_PAGE_URL, page_template = :NEW_PAGE_TEMPLATE, page_title = :NEW_PAGE_TITLE, page_description = :NEW_PAGE_DESCRIPTION, page_keywords = :NEW_PAGE_KEYWORDS, page_active = :NEW_PAGE_ACTIVE, page_nav_visible= :NEW_PAGE_NAV_VISIBLE, page_temp_marker= :NEW_PAGE_TEMP_MARKER;';
						                $STMT3 = $DBCON -> prepare($QUERY3);
										$STMT3 -> bindParam(':NEW_PAGE_MOTHER', $_NEW_PAGE_MOTHER, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_LEVEL', $_NEW_PAGE_LEVEL, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_ORDER', $_NEW_PAGE_ORDER, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_DATETIME', $_NEW_PAGE_DATETIME, \PDO::PARAM_STR);
						                $STMT3 -> bindParam(':NEW_PAGE_NAME', $_NEW_PAGE_NAME, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_URL', $_NEW_PAGE_URL, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_TEMPLATE', $_NEW_PAGE_TEMPLATE, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_TITLE', $_NEW_PAGE_TITLE, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_DESCRIPTION', $_NEW_PAGE_DESCRIPTION, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_KEYWORDS', $_NEW_PAGE_KEYWORDS, \PDO::PARAM_STR);
										$STMT3 -> bindParam(':NEW_PAGE_ACTIVE', $_NEW_PAGE_ACTIVE, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_NAV_VISIBLE', $_NEW_PAGE_NAV_VISIBLE, \PDO::PARAM_INT);
										$STMT3 -> bindParam(':NEW_PAGE_TEMP_MARKER', $_NEW_PAGE_TEMP_MARKER, \PDO::PARAM_INT);
						                $STMT3 -> execute();

										echo '<p><strong>Die Seite wurde erfolgreich erstellt | <a href="./index.php">OK</a></strong></p>';
								    }
								    catch(PDOException $e)
								    {
										die('<p>6) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
								    }
							    }
							    catch(PDOException $e)
							    {
									die('<p>7) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
							    }
							}
							else
							{
								$_ALL_PAGES_COUNTR = $_POST['pages_countr'];
								$_NEW_PAGE_ID = NULL;
								$_NEW_PAGE_LEVEL = 1;
								$_NEW_PAGE_MOTHER = 0;
								$_NEW_PAGE_ORDER = ($_ALL_PAGES_COUNTR + 1);
								$_NEW_PAGE_DATETIME = date('Y-m-d H:i:s');
								$_NEW_PAGE_NAME = $_POST['new_page_name'];
								$_NEW_PAGE_URL = 'PAGE URL';
								$_NEW_PAGE_TEMPLATE = 'PAGE TEMPLATE';
								$_NEW_PAGE_TITLE = 'PAGE TITLE';
								$_NEW_PAGE_DESCRIPTION = 'PAGE DESCRIPTION';
								$_NEW_PAGE_KEYWORDS = 'PAGE KEYWORDS';
								$_NEW_PAGE_ACTIVE = 1;
								$_NEW_PAGE_NAV_VISIBLE = 1;
								$_NEW_PAGE_TEMP_MARKER = 0;

							    try
							    {
									$QUERY = 'INSERT INTO seitenverwaltung SET page_mother = :NEW_PAGE_MOTHER, page_level = :NEW_PAGE_LEVEL, page_order = :NEW_PAGE_ORDER, page_datetime = :NEW_PAGE_DATETIME, page_name = :NEW_PAGE_NAME, page_url = :NEW_PAGE_URL, page_template = :NEW_PAGE_TEMPLATE, page_title = :NEW_PAGE_TITLE, page_description = :NEW_PAGE_DESCRIPTION, page_keywords = :NEW_PAGE_KEYWORDS, page_active = :NEW_PAGE_ACTIVE, page_nav_visible= :NEW_PAGE_NAV_VISIBLE, page_temp_marker= :NEW_PAGE_TEMP_MARKER;';
					                $STMT = $DBCON -> prepare($QUERY);
									$STMT -> bindParam(':NEW_PAGE_LEVEL', $_NEW_PAGE_LEVEL, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_MOTHER', $_NEW_PAGE_MOTHER, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_ORDER', $_NEW_PAGE_ORDER, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_DATETIME', $_NEW_PAGE_DATETIME, \PDO::PARAM_STR);
					                $STMT -> bindParam(':NEW_PAGE_NAME', $_NEW_PAGE_NAME, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_URL', $_NEW_PAGE_URL, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_TEMPLATE', $_NEW_PAGE_TEMPLATE, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_TITLE', $_NEW_PAGE_TITLE, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_DESCRIPTION', $_NEW_PAGE_DESCRIPTION, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_KEYWORDS', $_NEW_PAGE_KEYWORDS, \PDO::PARAM_STR);
									$STMT -> bindParam(':NEW_PAGE_ACTIVE', $_NEW_PAGE_ACTIVE, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_NAV_VISIBLE', $_NEW_PAGE_NAV_VISIBLE, \PDO::PARAM_INT);
									$STMT -> bindParam(':NEW_PAGE_TEMP_MARKER', $_NEW_PAGE_TEMP_MARKER, \PDO::PARAM_INT);
					                $STMT -> execute();

									echo '<p><strong>Die Seite wurde erfolgreich erstellt | <a href="./index.php">OK</a></strong></p>';
							    }
							    catch(PDOException $e)
							    {
									die('<p>8) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
							    }
							}
						}
						else
						{
							// KEINE BEREITS ANGELEGTEN SEITEN VORHANDEN
							$_NEW_PAGE_ID = NULL;
							$_NEW_PAGE_LEVEL = 1;
							$_NEW_PAGE_ORDER = 1;
							$_NEW_PAGE_MOTHER = 0;
							$_NEW_PAGE_DATETIME = date('Y-m-d H:i:s');
							$_NEW_PAGE_NAME = $_POST['new_page_name'];
							$_NEW_PAGE_URL = 'PAGE URL';
							$_NEW_PAGE_TEMPLATE = 'PAGE TEMPLATE';
							$_NEW_PAGE_TITLE = 'PAGE TITLE';
							$_NEW_PAGE_DESCRIPTION = 'PAGE DESCRIPTION';
							$_NEW_PAGE_KEYWORDS = 'PAGE KEYWORDS';
							$_NEW_PAGE_ACTIVE = 1;
							$_NEW_PAGE_NAV_VISIBLE = 1;
							$_NEW_PAGE_TEMP_MARKER = 0;

						    try
						    {
								$QUERY = 'INSERT INTO seitenverwaltung SET page_mother = :NEW_PAGE_MOTHER, page_level = :NEW_PAGE_LEVEL, page_order = :NEW_PAGE_ORDER, page_datetime = :NEW_PAGE_DATETIME, page_name = :NEW_PAGE_NAME, page_url = :NEW_PAGE_URL, page_template = :NEW_PAGE_TEMPLATE, page_title = :NEW_PAGE_TITLE, page_description = :NEW_PAGE_DESCRIPTION, page_keywords = :NEW_PAGE_KEYWORDS, page_active = :NEW_PAGE_ACTIVE, page_nav_visible= :NEW_PAGE_NAV_VISIBLE, page_temp_marker= :NEW_PAGE_TEMP_MARKER;';
				                $STMT = $DBCON -> prepare($QUERY);
								$STMT -> bindParam(':NEW_PAGE_LEVEL', $_NEW_PAGE_LEVEL, \PDO::PARAM_INT);
								$STMT -> bindParam(':NEW_PAGE_MOTHER', $_NEW_PAGE_MOTHER, \PDO::PARAM_INT);
								$STMT -> bindParam(':NEW_PAGE_ORDER', $_NEW_PAGE_ORDER, \PDO::PARAM_INT);
								$STMT -> bindParam(':NEW_PAGE_DATETIME', $_NEW_PAGE_DATETIME, \PDO::PARAM_STR);
				                $STMT -> bindParam(':NEW_PAGE_NAME', $_NEW_PAGE_NAME, \PDO::PARAM_STR);
								$STMT -> bindParam(':NEW_PAGE_URL', $_NEW_PAGE_URL, \PDO::PARAM_STR);
								$STMT -> bindParam(':NEW_PAGE_TEMPLATE', $_NEW_PAGE_TEMPLATE, \PDO::PARAM_STR);
								$STMT -> bindParam(':NEW_PAGE_TITLE', $_NEW_PAGE_TITLE, \PDO::PARAM_STR);
								$STMT -> bindParam(':NEW_PAGE_DESCRIPTION', $_NEW_PAGE_DESCRIPTION, \PDO::PARAM_STR);
								$STMT -> bindParam(':NEW_PAGE_KEYWORDS', $_NEW_PAGE_KEYWORDS, \PDO::PARAM_STR);
								$STMT -> bindParam(':NEW_PAGE_ACTIVE', $_NEW_PAGE_ACTIVE, \PDO::PARAM_INT);
								$STMT -> bindParam(':NEW_PAGE_NAV_VISIBLE', $_NEW_PAGE_NAV_VISIBLE, \PDO::PARAM_INT);
								$STMT -> bindParam(':NEW_PAGE_TEMP_MARKER', $_NEW_PAGE_TEMP_MARKER, \PDO::PARAM_INT);
				                $STMT -> execute();

								echo '<p><strong>Die Seite wurde erfolgreich erstellt | <a href="./index.php">OK</a></strong></p>';
						    }
						    catch(PDOException $e)
						    {
								die('<p>9) Fehler beim anlegen einer neuen Seite: <strong>' . $e . '</strong>!</p>');
						    }
						}
					 }
					/*
					 * POSTPROCESSING CREATE NEW PAGE END
					 * */







 					/*
					 * POSTPROCESSING DELETE PAGE START
					 * */
					if(isset($_GET['delete_page_id']) && $_GET['delete_page_id'] != '' && $_GET['page_level'] != '' && $_GET['page_order'] != '')
					{
					
						echo '<pre>';
						var_dump($_GET);
						echo '</pre>';
						
					}
 					/*
					 * POSTPROCESSING DELETE PAGE END
					 * */






					/*
					 * DISPLAYING PAGES TABLE START
					 * */
					/*
					 * SELECT ALL PAGES START
					 * */
				    try
				    {
						$PAGES 			= $DBCON -> query('SELECT * FROM seitenverwaltung ORDER BY page_order;');
						$PAGES_COUNTR 	= 0;
						$PAGES_COUNTR 	= $PAGES -> rowCount();
				    }
				    catch(PDOException $e)
				    {
						die('<p>Fehler beim auslesen aller Seiten!</p>');
				    }
					/*
					 * SELECT ALL PAGES START
					 * */

					echo '<h3>' . $PAGES_COUNTR . ' Seiten | <a href="./index.php?create_new_page=true" title="Eine neue Seite erstellen">+</a></h3>';
					
					if($PAGES_COUNTR > 0)
					{
						echo '<table>';
						echo '<thead>';
						echo '<tr>';
						echo '<th>';
						echo 'Name';
						echo '</th>';
						echo '<th>';
						echo 'Level';
						echo '</th>';
						echo '<th>';
						echo 'Sortierung';
						echo '</th>';
						echo '<th>';
						echo 'Mother';
						echo '</th>';
						echo '<th>';
						echo 'ID';
						echo '</th>';
						echo '<th style="text-align:center">';
						echo 'Aktionen';
						echo '</th>';
						echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
		
							foreach($PAGES AS $PAGE)
							{
								echo '<tr>';
								echo '<td style="text-align:left">';

                                if($PAGE['page_level'] > 1)
                                {
                                    for($i = 1; $i <= $PAGE['page_level']; $i++)
                                    {
                                        echo '&#160;&#160;&#160;';
                                    }
                                }

								echo $PAGE['page_name'];
								echo '</td>';
								echo '<td style="text-align:left">';

                                if($PAGE['page_level'] > 1)
                                {
                                    for($i = 1; $i <= $PAGE['page_level']; $i++)
                                    {
                                        echo '&#160;&#160;&#160;';
                                    }
                                }

								echo $PAGE['page_level'];
								echo '</td>';
								echo '<td style="text-align:right">';
								echo $PAGE['page_order'];
								echo '</td>';
								echo '<td style="text-align:center">';
								echo $PAGE['page_mother'];
								echo '</td>';
								echo '<td style="text-align:center">';
								echo $PAGE['page_id'];
								echo '</td>';
								echo '<td style="text-align:center">';
								echo '<a href="./index.php?delete_page_id=' . $PAGE['page_id'] . '&page_level=' . $PAGE['page_level'] . '&page_order=' . $PAGE['page_order'] . '&page_mother=' . $PAGE['page_mother'] . '">Delete</a>';
								echo '</td>';
								echo '</tr>';
							}
		
						echo '<tbody>';
						echo '</table>';
					}
					else
					{
						echo '<p>Keine Seiten vorhanden!</p>';
					}
					/*
					 * DISPLAYING PAGES TABLE END
					 * */

					/*
					 * CLOSE DATABASE CONNECTION START
					 * */
					$DBCON -> NULL;
					/*
					 * CLOSE DATABASE CONNECTION END
					 * */

				?>
			</div> <!-- // EOF CONTAINER -->

		</body>
	</html>