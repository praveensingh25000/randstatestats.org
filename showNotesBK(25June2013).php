<?php
/******************************************
* @Modified on june 22, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/
?>
<p>&nbsp;</p>
<p class="fontbld">
	<?php if(($dbid == '20' || $dbid == '26') && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
	<strong>Note:&nbsp;</strong>Data returned as persons per square mile.
	<?php } else if($dbid == '12' && $_SESSION['databaseToBeUse'] == 'rand_texas'){ ?>
	<strong>*</strong>=Data withheld to limit disclosure, <strong>X</strong>=Not applicable.
	<?php } else if($dbid == '75' && $_SESSION['databaseToBeUse'] == 'rand_california'){ ?>
	<strong>N</strong>=Number, <strong>R</strong>=rate.
	<?php } else if($dbid == '117' && $_SESSION['databaseToBeUse'] == 'rand_california') { ?>
	<strong>*</strong>=Data masked for cells with less than 16 observations due to HIPAA and/or 42 CFR restrictions.<br/><strong>**</strong>=Column totals not shown when only one row has a cell size that is not displayed due to HIPAA rules.
	<?php } else if($dbid == '135' && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
	<Strong>(B): </strong> Base figure too small to meet statistical standards for reliability of a derived figure.<br/><br/>
	<strong>NA</strong> = Not Available.
	<?php } else if($dbid == '122' && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
	(b) - For the years indicated, the laws in Arizona, Arkansas, California, Colorado, Kentucky, Minnesota, Ohio, Utah, and Wisconsin applied only to women and minors.<br/>
	(c) - Rates applicable to employers of four or more.<br/>
	(d) - Rates applicable to employers of six or more. In West Virginia, applicable to employers of six or more in one location.<br/>
	(e) - Rates applicable to employers of two or more.<br/>
	(f) - For the years 1988 to 1990, Minnesota had a two tier schedule with the higher rate applicable to employers covered by the FLSA and the lower rate to employers not covered by the FLSA.<br/>
	(g) - Minnesota sets a lower rate for enterprises with annual receipts of less than $500,000 ($4.90, January 1, 1998-January 1, 2005). The dollar amount prior to September 1, 1997 was $362,500 ($4.00 - January 1, 1991-January 1, 1997); Montana sets a lower rate for businesses with gross annual sales of $110,000 or less ($4.00 - January 1, 1992-January 1, 2005); Ohio sets a lower rate for employers with gross annual sales from $150,000 to $500,000 ($3.35 - January 1, 1991-January 1, 2005) and for employers with gross annual sales under $150,000 ($2.50 - January 1, 1991-January 1, 2005); Oklahoma sets a lower rate for employers of fewer than 10 full-time employees at any one location and for those with annual gross sales of less than $100,000 ($2.00, January 1, 1991-January 1, 2005); and the U.S. Virgin Islands sets a lower rate for businesses with gross annual receipts of less than $150,000 ($4.30, January 1, 1991-January 1, 2005).<br/>
	(h) - In the District of Columbia, wage orders were replaced by a statutory minimum wage on October 1, 1993. A $5.45 minimum rate remained in effect for the laundry and dry cleaning industry as the result of the grandfather clause.<br/>
	(i) - In Puerto Rico, separate minimum rates are in effect for almost 350 non-farm occupations by industry Mandatory Decrees. Rates are higher than those in the range listed in effect in a few specific occupations.<br/>
	(j) - In the U.S. Virgin Islands, implementation of an indexed rate, which was to have started January 1, 1991, was delayed.<br/><br/>
	<strong>NA</strong> = Not Available.
	<?php } else if(($dbid == '166') && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?> 
	<strong>Note:&nbsp;</strong>Each year denotes the last year of a decade, i.e., 1829 represents 1820 to 1829.
	<?php } else if(($dbid == '141' || $dbid == '189' || $dbid == '187' || $dbid == '186' || $dbid == '185' || $dbid == '184' || $dbid == '183' || $dbid == '182' || $dbid == '181' || $dbid == '180' || $dbid == '54' || $dbid == '175' || $dbid == '76' || $dbid == '17' || $dbid == '19' || $dbid == '18' || $dbid == '75' || $dbid == '96' || $dbid == '5' || $dbid == '35' || $dbid == '108' || $dbid == '114') && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
	<strong>NA</strong> = Not Available.
	<?php } else if(($dbid == '74' || $dbid == '156' ) && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
	<strong>D,*</strong> =Data withheld to limit disclosure, <strong>X</strong> =Not applicable, <strong>NA</strong> = Not Available.
	<?php } else if(($dbid == '50' || $dbid == '51' || $dbid == '53' || $dbid == '57') && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
	<strong>*</strong> = Data withheld for confidentiality reasons.
	<?php } else if($dbid == '52' && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
	<strong>*</strong> = Data withheld for confidentiality reasons;
	<strong>NA</strong> = Not Available.
	<?php } else { ?>
	<strong>D,*</strong> =Data withheld to limit disclosure, <strong>X</strong> =Not applicable, <strong>NA</strong> = Not Available.
	<?php } ?>
</p>