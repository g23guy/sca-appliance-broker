<?PHP include 'checklogin.php';?>
<?PHP //echo "<!-- Modified: Date       = 2014 May 12 -->\n"; ?>
<HTML>
<HEAD>
<?PHP
	include 'sca-config.php';
	$DefaultTop = 30;
	$DefaultRowStart = 0;
	$DefaultArchiveType = 'a';
	$Top = $_GET['top'];
	$rowStart = $_GET['row'];
	$archiveType = $_GET['atp'];

	if ( isset($Top) ) {
		if ( ! is_numeric($Top) ) { $Top = $DefaultTop; } 
	} else { 
		$Top = $DefaultTop;
	}
	if ( isset($rowStart) ) {
		if ( ! is_numeric($rowStart) ) { $rowStart = $DefaultRowStart; } 
	} else { 
		$rowStart = $DefaultRowStart;
	}
	switch ($archiveType) {
	case 'p':
	case 'a':
	case 'd':
	case 'e':
	case 't':
		break;
	default:
		$archiveType = $DefaultArchiveType;
		break;
	}

	$rowNext = $rowStart + $Top;
	$rowPrev = $rowStart - $Top;
	//echo "<!-- Variable: StatsRefresh    = $StatsRefresh -->\n";
	//echo "<!-- Variable: archiveType     = $archiveType -->\n";
	//echo "<!-- Variable: Top             = $Top -->\n";
	//echo "<!-- Variable: rowStart        = $rowStart -->\n";
	//echo "<!-- Variable: rowPrev         = $rowPrev -->\n";
	//echo "<!-- Variable: rowNext         = $rowNext -->\n";

	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$StatsRefresh;URL=detailarch.php?atp=$archiveType&top=$Top&row=$rowStart\">\n";
	echo "<META HTTP-EQUIV=\"Content-Style-Type\" CONTENT=\"text/css\">\n";
	echo "<LINK REL=\"stylesheet\" HREF=\"style.css\">\n";

	$Fields = "ArchiveID,Filename,ArchiveState,ArchiveEvent,ArchiveMessage,AssignedAgentID,AssignedWorkerID,ReportDate,ReportTime,ServerName,AnalysisTime,PatternsApplicable,PatternsTested";
	switch ($archiveType) {
	case 'p':
		$archiveString = "Pending";
		$query="SELECT $Fields FROM Archives WHERE ArchiveState='New' OR ArchiveState='Retry' OR ArchiveState='Assigned' ORDER BY ArchiveState DESC, ArchiveEvent DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'a':
		$archiveString = "Active";
		$query="SELECT $Fields FROM Archives WHERE ArchiveState='Downloading' OR ArchiveState='Extracting' OR ArchiveState='Identifying' OR ArchiveState='Analyzing' OR ArchiveState='Reporting' ORDER BY ArchiveState ASC, ArchiveEvent DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'd':
		$archiveString = "Done";
		$query="SELECT $Fields FROM Archives WHERE ArchiveState='Done' ORDER BY ArchiveState ASC, ArchiveEvent DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'e':
		$archiveString = "Error";
		$query="SELECT $Fields FROM Archives WHERE ArchiveState='Error' ORDER BY ArchiveState ASC, ArchiveEvent DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 't':
		$archiveString = "Total";
		$query="SELECT $Fields FROM Archives ORDER BY ArchiveEvent DESC LIMIT " . $rowStart . "," . $Top;
		break;
	}

	echo "<TITLE>SCA Archives $archiveString</TITLE>\n";
	echo "</HEAD>\n";
	echo "<BODY BGPROPERTIES=FIXED BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n";
	echo "\n<P CLASS=\"head_1\" ALIGN=\"center\">Supportconfig Analysis Appliance<BR><FONT SIZE=\"+2\">Archive Details: $archiveString</FONT></P>\n";

	$ColorRed = "#FF0000";
	$ColorYellow = "#FFFF00";
	$ColorRoyalBlue = "#1975FF";
	$ColorGreen = "#00FF00";
	$ColorWhite = "#FFFFFF";
	$ColorGray = "#EEEEEE";
	$ColorDarkGray = "#898989";
	$ColorLightGray = "#CDCDCD";
	$ColorSteelBlue = "#B0C4DE";
	$ColorPeach = "#FFCC99";
	$ColorBlack = "#000000";
	$ColorBlue = "#0000FF";

	// Create Archives table
	$Connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	if ($Connection->connect_errno) {
		echo "<P CLASS=\"head_1\" ALIGN=\"center\">SCA Database Detailed Archive Report</P>\n";
		echo "<H2 ALIGN=\"center\">Connect to Database: <FONT COLOR=\"red\">FAILED</FONT></H2>\n";
		echo "<P ALIGN=\"center\">Make sure the MariaDB database is configured properly.</P>\n";
		echo "</BODY>\n</HTML>\n";
		die();
	}
	//echo "<!-- Query: Submitted          = $query -->\n";
	$result = $Connection->query($query);
	$num = $result->num_rows;
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Rows               = $num -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}

	echo "<P ALIGN=\"center\">[ ";
	echo "<A HREF=\"index.php\">Reports</A> | ";
	echo "<A HREF=\"opstate.php\">Operations</A> | ";
	echo "<A HREF=\"patterns.php\">Create Patterns</A> | ";
	echo "<A HREF=\"docs.html\" TARGET=\"docs\">Documentation</A> ";
	echo "]<BR>\n";

	if ( $num > 0 ) {
		echo "[ ";
		echo "Paging:&nbsp;&nbsp;";
		if ( $rowStart > 0 ) {
			echo "<A HREF=\"detailarch.php?atp=$archiveType&top=$Top&row=$rowPrev\" ALT=\"Previous\">Prev</A>&nbsp;&nbsp;";
		} else {
			echo "Prev&nbsp;&nbsp;";
		}
		if ( $num >= $Top ) {
			echo "<A HREF=\"detailarch.php?atp=$archiveType&top=$Top&row=$rowNext\" ALT=\"Next\">Next</A>&nbsp;&nbsp;";
		} else {
			echo "Next&nbsp;&nbsp;";
		}
		if ( $rowStart != 0 ) {
			echo "<A HREF=\"detailarch.php?atp=$archiveType&top=$Top&row=0\" ALT=\"Top\">Top</A>&nbsp;&nbsp;| ";
		} else {
			echo "Top&nbsp;&nbsp;| ";
		}
		echo "List Top ";
		if ( $Top == 10 ) { echo "10, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=10&row=$rowStart\">10</A>, "; }
		if ( $Top == 20 ) { echo "20, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=20&row=$rowStart\">20</A>, "; }
		if ( $Top == 30 ) { echo "30, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=30&row=$rowStart\">30</A>, "; }
		if ( $Top == 50 ) { echo "50, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=50&row=$rowStart\">50</A>, "; }
		if ( $Top == 100 ) { echo "100, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=100&row=$rowStart\">100</A>"; }
		echo " ]</P>\n";
	} else {
		echo "[ Paging:  Prev  Next  Top  | List Top 10, 20, 30, 50, 100 ]</P>\n";
	}

	echo "\n<TABLE ALIGN=\"center\" WIDTH=100% CELLPADDING=2>\n";
	echo "<TR ALIGN=\"left\" CLASS=\"head_2\">";
	echo "<TH>&nbsp;</TH>";
	echo "<TH>Server</TH>";
	echo "<TH>Status</TH>";
	echo "<TH>Status Date</TH>";
	echo "<TH>Report Date</TH>";
	echo "<TH ALIGN=\"center\">ID</TH>";
	echo "<TH WIDTH=\"30%\">Archive Filename</TH>";
	echo "<TH>Patterns</TH>";
	echo "<TH>Applied</TH>";
	echo "<TH ALIGN=\"center\">Analysis<br>(H:M:S)</TH>";
	echo "<TH>Archive Message</TH>";
	echo "</TR>\n";

	$i=0;
	while ( $row_cell = $result->fetch_row() ) {
		$ArchiveID = htmlspecialchars($row_cell[0]);
		$Filename = htmlspecialchars($row_cell[1]);
		$ArchiveState = htmlspecialchars($row_cell[2]);
		$ArchiveEvent = htmlspecialchars($row_cell[3]);
		$ArchiveMessage = htmlspecialchars($row_cell[4]);
		$AssignedAgentID = htmlspecialchars($row_cell[5]);
		$AssignedWorkerID = htmlspecialchars($row_cell[6]);
		$ReportDate = htmlspecialchars($row_cell[7]);
		$ReportTime = htmlspecialchars($row_cell[8]);
		$ServerName = htmlspecialchars($row_cell[9]);
		$AnalysisTime = htmlspecialchars($row_cell[10]);
		$PatternsApplicable = htmlspecialchars($row_cell[11]);
		$PatternsTested = htmlspecialchars($row_cell[12]);

		// Set row color
		if ( $i%2 == 0 ) {
			$row_color="tdGrey";
		} else {
			$row_color="tdGreyLight";
		}

		//Create table rows with data
		echo "<TR ALIGN=\"left\" CLASS=\"$row_color\">";
		switch ($ArchiveState) {
		case 'New':
		case 'Pending':
		case 'Done':
		case 'Error':
			echo "<TD ALIGN=\"center\" STYLE=\"background:$ColorRed; color:$ColorWhite;\"><A HREF=\"deletereport.php?atp=$archiveType&aid=$ArchiveID&snm=$ServerName\" TITLE=\"Click to Delete Report\">X</A></TD>";
			break;
		default:
			echo "<TD>&nbsp;</TD>";
		}
		echo "<TD>$ServerName</TD>";
		echo "<TD>$ArchiveState</TD>";
		echo "<TD>$ArchiveEvent</TD>";
		if ( isset($AnalysisTime) ) {
			echo "<TD><A HREF=\"reportfull.php?aid=$ArchiveID\" TARGET=\"$ServerName\" TITLE=\"SCA Report for $ServerName\">$ReportDate $ReportTime</A></TD>";
		} else {
			echo "<TD></TD>";
		}
		echo "<TD ALIGN=\"right\">$AssignedAgentID:$AssignedWorkerID:$ArchiveID</TH>";
		echo "<TD>$Filename</TD>";
		echo "<TD>$PatternsApplicable</TD>";
		echo "<TD>$PatternsTested</TD>";
		if ( isset($AnalysisTime) ) {
			$AnalysisTimeString = gmdate("H:i:s", $AnalysisTime);
			echo "<TD ALIGN=\"center\">$AnalysisTimeString</TD>";
		} else {
			echo "<TD></TD>";
		}
		echo "<TD>$ArchiveMessage</TD>";
		echo "</TR>\n";
	}
	$result->close();
	$Connection->close();
	echo "</TABLE>\n";

	if ( $num > 0 ) {
		echo "<P ALIGN=\"center\">[ ";
		echo "Paging:&nbsp;&nbsp;";
		if ( $rowStart > 0 ) {
			echo "<A HREF=\"detailarch.php?atp=$archiveType&top=$Top&row=$rowPrev\" ALT=\"Previous\">Prev</A>&nbsp;&nbsp;";
		} else {
			echo "Prev&nbsp;&nbsp;";
		}
		if ( $num >= $Top ) {
			echo "<A HREF=\"detailarch.php?atp=$archiveType&top=$Top&row=$rowNext\" ALT=\"Next\">Next</A>&nbsp;&nbsp;";
		} else {
			echo "Next&nbsp;&nbsp;";
		}
		if ( $rowStart != 0 ) {
			echo "<A HREF=\"detailarch.php?atp=$archiveType&top=$Top&row=0\" ALT=\"Top\">Top</A>&nbsp;&nbsp;| ";
		} else {
			echo "Top&nbsp;&nbsp;| ";
		}
		echo "List Top ";
		if ( $Top == 10 ) { echo "10, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=10&row=$rowStart\">10</A>, "; }
		if ( $Top == 20 ) { echo "20, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=20&row=$rowStart\">20</A>, "; }
		if ( $Top == 30 ) { echo "30, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=30&row=$rowStart\">30</A>, "; }
		if ( $Top == 50 ) { echo "50, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=50&row=$rowStart\">50</A>, "; }
		if ( $Top == 100 ) { echo "100, "; } else { echo "<A HREF=\"detailarch.php?atp=$archiveType&top=100&row=$rowStart\">100</A>"; }
		echo " ]</P>\n";
	}
	echo "</BODY>\n";
	echo "</HTML>\n";
?>

