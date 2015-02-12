<?php
/******************************************
* @Modified on April 5, 2013
* @Package: RAND
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">
	<h2>RAND California Medi-Cal Glossary </h2><br/>

		<p>RAND California Medi-Cal Statistics provides a snapshot estimate of who in California was on Medi-Cal in a particular year for the month of January. This is only an estimate since a person goes on Medi-Cal one month at a time and is considered to be on Medi-Cal if that person was eligible on only the first or the last day of a particular month. In short, persons on Medi-Cal are considered to be on Medi-Cal the whole month even if they are eligible only one day of that month.</p><br/>

		<p>The estimates of the number of persons on Medi-Cal for the year is derived by querying California Department of Health Services Monthly Medi-Cal Eligibility Files (MMEF). Each of these files has information on who was on Medi-Cal for about the last twelve months. Thus, the year 2000 figures estimate those eligible for Medi-Cal for the year 2000.</p><br/>

		<p>The following defines Medi-Cal categories or programs as described in Medi-Cal statistics found on RAND California. Users should contact the California Department of Health Services for more information.</p>

		<table cellpadding="6" border="1" class="data-table">
    <tbody><tr class="gray">
	<th valign="top">Category/Program</th>
	<th valign="top">Definition</th>
    </tr>
    <tr>
        <td valign="top">100 Percent Program. Child &ndash; United
        States Citizen, Lawful Permanent Resident/PRUCOL/</td>
        <td valign="top">100 Percent Program. Child &ndash; United
        States Citizen, Lawful Permanent Resident/PRUCOL/.
        Provides full benefits to otherwise eligible children,
        ages 6 to 19 and beyond 19 when inpatient status began
        before the 19th birthday and family income is at or below
        100 percent of federal poverty level.</td>
    </tr>
    <tr>
        <td valign="top">100 Percent Program. Child &ndash;
        Undocumented/Nonimmigrant Status/[IRCA Amnesty Alien]</td>
        <td valign="top">100 Percent Program. Child &ndash;
        Undocumented/Nonimmigrant Status/[IRCA Amnesty Alien].
        Covers emergency and pregnancy related services to
        otherwise eligible children, ages 6 to 19 and beyond 19
        when inpatient status begins before the 19th birthday and
        family income is at or below 100 percent e federal
        poverty level.</td>
    </tr>
    <tr>
        <td valign="top">100 Percent Program. Child &ndash;
        Undocumented/Nonimmigrant Status/IRCA Amnesty Alien</td>
        <td valign="top">100 Percent Program. Child &ndash;
        Undocumented/Nonimmigrant Status/IRCA Amnesty Alien.
        Covers emergency and pregnancy-related services only to
        otherwise eligible children ages 6 to 19 and beyond 19
        when inpatient status begins before the 19th birthday and
        family income is at or below 100 percent e federal
        poverty level.</td>
    </tr>
    <tr>
        <td valign="top">133 Percent Program. Child-United States
        Citizen, Permanent Resident Alien/PRUCOL Alien</td>
        <td valign="top">133 Percent Program. Child-United States
        Citizen, Permanent Resident Alien/PRUCOL Alien. Provides
        full Medi-Cal benefits to children ages 1 up to 6 and
        beyond 6 years when inpatient status, which began before
        6th birthday, continues, and family income is at or below
        133 percent of the federal poverty level.</td>
    </tr>
    <tr>
        <td valign="top">133 Percent Program. Child Undocumented/
        Nonimmigrant Alien (but otherwise eligible)</td>
        <td valign="top">133 Percent Program. Child Undocumented/
        Nonimmigrant Alien (but otherwise eligible). Provides
        emergency services only for children ages 1 up to 6 and
        beyond 6 years when inpatient status, which began before
        6th birthday, continues, and family income is at or below
        133 percent of the federal poverty level.</td>
    </tr>
    <tr>
        <td valign="top">133 Percent Program. Child
        Undocumented/Nonimmigrant Alien (but otherwise eligible
        except for excess property)</td>
        <td valign="top">133 Percent Program. Child
        Undocumented/Nonimmigrant Alien (but otherwise eligible
        except for excess property). Provides emergency services
        only for children ages 1 up to 6 and beyond 6 years when
        inpatient status, which began before 6th birthday,
        continues, and family income is at or below 133 percent
        of the federal poverty level.</td>
    </tr>
    <tr>
        <td valign="top">133 Percent Program. Child &ndash; United
        States Citizen, Permanent Resident Alien/PRUCOL Alien</td>
        <td valign="top">133 Percent Program. Child &ndash; United
        States Citizen, Permanent Resident Alien/PRUCOL Alien.
        Provides full-scope Medi-Cal benefits to children ages 1
        up to 6 and beyond 6 years when inpatient status, which
        began before 6th birthday, continues, and family income
        is at or below 133 percent of the federal poverty level.</td>
    </tr>
    <tr>
        <td valign="top">250 Percent Program Working Disabled</td>
        <td valign="top">250 Percent Program Working Disabled.
        Provides full-scope Medi-Cal benefits to working disabled
        recipients who meet the requirements of the 250 Percent
        Program.</td>
    </tr>
    <tr>
        <td valign="top">60-Day Postpartum Program</td>
        <td valign="top">60-Day Postpartum Program. Provides
        Medi-Cal at no SOC to women who, while pregnant, were
        eligible for, applied for, and received Medi-Cal
        benefits. They may continue to be eligible for all
        postpartum services and family planning. This coverage
        begins on the last day of pregnancy and ends the last day
        of the month in which the 60th day occurs,</td>
    </tr>
    <tr>
        <td valign="top">Additional TMC</td>
        <td valign="top">Additional TMC &ndash; Additional Six
        Months Continuing Eligibility. Covers persons
        discontinued from AFDC due to the expiration of the $30
        plus 1/3 disregard, increased earnings or hours of
        employment, but eligible for Medi-Cal only, may receive
        this extension of TMC.</td>
    </tr>
    <tr>
        <td valign="top">Adoption Assistance Program</td>
        <td valign="top">Adoption Assistance Program. A cash
        grant program to facilitate the adoption of hard-to-place
        children who would require permanent foster care
        placement without such assistance. Also indicates a
        program for AAP children for whom there is a state-only
        AAP agreement between any state other than California and
        adoptive parent(s).</td>
    </tr>
    <tr>
        <td valign="top">Adoption Assistance Program/Aid for
        Adoption of Children</td>
        <td valign="top">Adoption Assistance Program/Aid for
        Adoption of Children. Covers cash grant children
        receiving Medi-Cal by virtue of eligibility to AAP/AAC
        benefits.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Disabled Widow/ers</td>
        <td valign="top">Aid to Disabled Widow/ers . Covers
        persons who began receiving Title II SSA before age 60
        who were eligible for and receiving SSI/SSP and Title II
        benefits concurrently and were subsequently discontinued
        from SSI/SSP but would be eligible to receive SSI/S if
        their Title II disabled widow/ers reduction factor and
        subsequent COLAs were disregarded.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent
        Children-FG (State only)</td>
        <td valign="top">AFDC-FG (State only). Provides aid to
        families in which a child is deprived because of the
        absence, incapacity or death of either parent, who does
        not meet all federal requirements, but State rules
        require the individual(s) be aided. This population is
        the same as aid code 32, except that they are exempt from
        the ADFC grant reductions on behalf of the Assistance
        Payments Demonstration Project/California Work Pays
        Demonstration Project.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent
        Children-FU (State only)</td>
        <td valign="top">AFDC-FU (State only). Provides aid to
        pregnant women (before their last trimester) who meet the
        federal definition of an unemployed parent but are not
        eligible because there are no other children in the home.
        This population is the same as aid code 33, except that
        they are exempt from the ADFC grant reductions on behalf
        of the Assistance Payments Demonstration
        Project/California Work Pays Demonstration Project.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent
        Children&ndash; Mandatory Coverage Group Section 1931(b) </td>
        <td valign="top">AFDC &ndash; Mandatory Coverage Group
        Section 1931(b) . Section 1931 requires Medi-Cal be
        provided to low-income families who meet the requirements
        of the Aid to Families with Dependent Children (AFDC)
        State Plan in effect July 16, 1996.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent Children
        Unemployed Parent</td>
        <td valign="top">AFDC Unemployed Parent &ndash; Aid to
        families in which a child is deprived because of the
        unemployment of a parent living in the home and the
        unemployed parent meets all federal AFDC eligibility
        requirements. This population is the same as aid code 35,
        except that they are exempt from the ADFC grant
        reductions on behalf of the Assistance Payments
        Demonstration Project/California Work Pays Demonstration
        Project.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent Children
        &ndash; Family Group</td>
        <td valign="top">Aid to Families with Dependent Children
        &ndash; Family Group in which the child(ren) is deprived
        because of the absence, incapacity or death of either
        parent. This population is the same as aid code 30,
        except that they are exempt from the AFDC grant
        reductions on behalf of the Assistance Payments
        Demonstration Project/California Work Pays Demonstration
        Project.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent
        Children-FG</td>
        <td valign="top">AFDC-FG . Provides aid to families with
        dependent children in a family group in which the
        child(ren) is deprived because of the absence, incapacity
        or death of either parent.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent Children
        -FG (State only)</td>
        <td valign="top">AFDC-FG (State only). Provides aid to
        families in which a child is deprived because of the
        absence, incapacity, or death of either parent, who does
        not meet all federal requirements, but State rules
        require the individual(s) be aided.</td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent Children
        Unemployed Parent</td>
        <td valign="top">AFDC &ndash; Unemployed Parent
        (State-only program). Provides aid to pregnant women
        (before their last trimester) who meet the federal
        definition of an unemployed parent but are not eligible
        because there are no other children in the home. </td>
    </tr>
    <tr>
        <td valign="top">Aid to Families with Dependent
        Children-U</td>
        <td valign="top">AFDC-U. Provides aid to families in
        which a child is deprived because of unemployment of a
        parent living in the home, and the unemployed parent
        meets all federal AFDC eligibility requirements.</td>
    </tr>
    <tr>
	<td valign="top">Aid to Families with Dependent Children-MN</td>
        <td valign="top">AFDC-MN . Covers families with
        deprivation of parental care or support who do not wish
        or are not eligible for a cash grant, but are eligible
        for Medi-Cal only. SOC required of the beneficiaries.</td>
    </tr>
    <tr>
	<td valign="top">Aid to Families with Dependent Children-FC Voluntarily Placed</td>
        <td valign="top">AFDC-FC Voluntarily Placed. Provides
        financial assistance for those children who are in need
        of substitute parenting and who have been voluntarily
        placed in foster care.</td>
    </tr>
    <tr>
	<td valign="top">Aid to Families with Dependent Children-FC/Non-Fed</td>
        <td valign="top">AFDC-FC/Non-Fed. Provides financial
        assistance for those children who are in need of
        substitute parenting and who have been placed in foster
        care.</td>
    </tr>
    <tr>
	<td valign="top">Aid to Families with Dependent Children-FC/Fed</td>
        <td valign="top">AFDC-FC/Fed. Provides financial
        assistance for those children who are in need of
        substitute parenting and who have been placed in foster
        care.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Aged SSI/SSP</td>
        <td valign="top">SSI/SSP Aid to the Aged. A cash
        assistance program administered by the SSA which pays a
        cash grant to needy persons 65 years of age or older.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Aged&ndash; LTC</td>
        <td valign="top">Aid to the Aged &ndash; LTC. Covers
        persons 65 years of age or older who are medically needy
        and in LTC status.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Aged&ndash; Medically Needy.</td>
        <td valign="top">Aid to the Aged &ndash; Medically Needy.
        Covers persons 65 years of age or older who do not wish
        or are not eligible for a cash grant, but are eligible
        for Medi-Cal only.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Aged&ndash; Pickle Eligibles</td>
        <td valign="top">Aid to the Aged &ndash; Pickle Eligibles.
        Covers persons 65 years of age or older who were eligible
        for and receiving SSI/SSP and Title II benefits
        concurrently in any month since April 1977 and were
        subsequently discontinued from SSI/SSP but would be
        eligible to receive SSI/SSP if their Title II
        cost-of-living increases were disregarded. These persond
        are eligible for Medi-Cal benefits as public assistance
        recipients in accordance with the provisions in the Lynch
        v. Rank lawsuit.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Aged&ndash; Medically Needy,
        SOC</td>
        <td valign="top">Aid to the Aged &ndash; Medically Needy,
        SOC. Covers persons 65 years of age or older who do not
        wish or are not eligible for a cash grant, but are
        eligible for Medi-Cal only. SOC required.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Aged&ndash; IHSS.</td>
        <td valign="top">Aid to the Aged &ndash; IHSS. Covers aged
        IHSS cash recipients, 65 years of age or older, who are
        not eligible for SSI/SSP cash benefits.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Blind-SSI/SSP</td>
        <td valign="top">SSI/SSP Aid to the Blind. A cash
        assistance program, administered by the SSA, which pays a
        cash grant to needy blind persons of any age.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Blind&ndash; LTC Status</td>
        <td valign="top">Aid to the Blind &ndash; LTC Status.
        Covers persons who meet the federal criteria for
        blindness, are medically needy, and are in LTC status.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Blind&ndash; Medically Needy</td>
        <td valign="top">Aid to the Blind &ndash; Medically Needy.
        Covers persons who meet the federal criteria for
        blindness who do not wish or are not eligible for a cash
        grant, but are eligible for Medi-Cal only.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Blind&ndash; Pickle Eligibles</td>
        <td valign="top">Aid to the Blind &ndash; Pickle
        Eligibles. Covers persons who meet the federal criteria
        for blindness and are covered by the provisions of the
        Lynch v. Rank lawsuit. (See aid code 16 for definition of
        Pickle eligibles.)</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Blind&ndash; Medically Needy,
        SOC</td>
        <td valign="top">Aid to the Blind &ndash; Medically Needy,
        SOC. Covers persons who meet the federal criteria for
        blindness who do not wish or are not eligible for a cash
        grant, but are eligible for Medi-Cal only. SOC is
        required of the beneficiaries.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Blind&ndash; IHSS</td>
        <td valign="top">Aid to Blind &ndash; IHSS. Covers persons
        who meet the federal definition of blindness and are
        eligible for IHSS. (See aid code 18 for definition of
        eligibility for IHSS.)</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled&ndash; DDS Waiver</td>
        <td valign="top">Aid to the Disabled &ndash; DDS Waiver.
        Covers persons who qualify for the Department of
        Developmental Services Regional Waiver.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled&ndash; Model Waiver </td>
        <td valign="top">Aid to the Disabled &ndash; Model Waiver.
        Covers persons who qualify for the Model Waiver.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled-SSI/SSP</td>
        <td valign="top">SSI/SSP Aid to the Disabled. A cash
        assistance program administered by the SSA that pays a
        cash grant to needy persons who meet the federal
        definition of disability.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled&ndash; LTC Status</td>
        <td valign="top">Aid to the Disabled &ndash; LTC Status.
        Covers persons who meet the federal definition of
        disability who are medically needy and in LTC status.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled&ndash; Medically
        Needy</td>
        <td valign="top">Aid to the Disabled &ndash; Medically
        Needy. Covers persons who meet the federal definition of
        disability and do not wish or are not eligible for cash
        grant, but are eligible for Medi-Cal only.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the DisabledSubstantial Gainful
        Activity/Aged, Blind, Disabled &ndash; Medically Needy
        IHSS</td>
        <td valign="top">Aid to the Disabled Substantial Gainful
        Activity/Aged, Blind, Disabled &ndash; Medically Needy
        IHSS. Covers persons who (a) were once determined to be
        disabled in accordance with the provisions of the SSI/SSP
        program and were eligible for SSI/SSP but became
        ineligible becasue of engagement in substanial gainful
        activity as defined in Title XVI regulations. They must
        also continue to suffer from the physical or mental
        impairment that was the basis of the disability
        determination or (b) are aged, blind or disabled
        medically needy and have the costs of IHSS deducted from
        their monthly income.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled Pickle Eligibles</td>
        <td valign="top">Aid to the Disabled Pickle Eligibles.
        Covers persons who meet the federal definition of
        disability and are covered by the provisions of the Lynch
        v. Rank lawsuit. No age limit for this aid code.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled&ndash; Medically
        Needy, SOC</td>
        <td valign="top">Aid to the Disabled &ndash; Medically
        Needy, SOC. SOC is required of the beneficiaries.</td>
    </tr>
    <tr>
        <td valign="top">Aid to the Disabled IHSS</td>
        <td valign="top">Aid to the Disabled IHSS. Covers persons
        who meet the federal definition of disability and are
        eligible for IHSS. </td>
    </tr>
    <tr>
        <td valign="top">Aid to Undocumented Aliens</td>
        <td valign="top">Aid to Undocumented Aliens in LTC Not
        PRUCOL. Covers undocumented aliens in LTC not Permanently
        Residing Under Color Of Law (PRUCOL). LTC services:
        State-only funds; emergency and pregnancy-related
        services: State and federal funds. Recipients will remain
        in this aid code even if they leave LTC.</td>
    </tr>
    <tr>
        <td valign="top">Asset Waiver Program Infant &ndash;
        Undocumented/Nonimmigrant Alien</td>
        <td valign="top">Asset Waiver Program. Infant &ndash;
        Undocumented/Nonimmigrant Alien. Provides emergency
        services only for infants up to age one year and
        continues beyond one year when inpatient status, which
        began before first birthday, continues and family income
        is between 185 percent and 200 percent of the Federal
        poverty level (State-only program).</td>
    </tr>
    <tr>
        <td valign="top">Asset Waiver Program Pregnant- United
        States Citizen, Permanent Resident Alien/PRUCOL Alien or
        Undocumented/Nonimmigrant Alien</td>
        <td valign="top">Asset Waiver Program. United States
        Citizen, Permanent Resident Alien/PRUCOL Alien or
        Undocumented/Nonimmigrant Alien (but otherwise eligible).
        Provides family planning, pregnancy-related, and
        postpartum services under the state-only funded expansion
        of the Medi-Cal program a pregnent woman having income
        between 185 percent and 200 percent of the Federal
        poverty level (State-only program).</td>
    </tr>
    <tr>
        <td valign="top">Asset Waiver Program Pregnant</td>
        <td valign="top">Asset Waiver Program. Provides family
        planning, pregnancy-related, and postpartum services for
        amnesty aliens under the state-only funded expansion of
        the Medi-Cal program for a pregnant woman having income
        between 185 percent and 200 percent of the Federal
        poverty level (State-only program).</td>
    </tr>
    <tr>
        <td valign="top">Asset Waiver Program Infant</td>
        <td valign="top">Asset Waiver Program. Provides full
        Medi-Cal benefits to infants up to 1 year, and beyond 1
        year when inpatient status, which began before 1st
        birthday, continues and family income is between 185
        percent and 200 percent of the federal poverty level
        (State-only program).</td>
    </tr>
    <tr>
        <td valign="top">California Alternative Assistance
        Program&ndash; Aid to Families with Dependent Children,
        Family Group</td>
        <td valign="top">California Alternative Assistance
        Program &ndash; Aid to Families with Dependent Children,
        Family Group. Individuals who have declined a federal
        cash grant and instead will receive child care assistance
        and Medi-Cal.</td>
    </tr>
    <tr>
        <td valign="top">California Alternative Assistance
        Program&ndash; Aid to Families with Dependent Children,
        Unemployed Parent Group</td>
        <td valign="top">California Alternative Assistance
        Program &ndash; Aid to Families with Dependent Children,
        Unemployed Parent Group. Individuals who have declined a
        federal cash grant and instead will receive child care
        assistance and Medi-Cal.</td>
    </tr>
    <tr>
        <td valign="top">CalWORKS Legal Immigrant&mdash;Family
        Group</td>
        <td valign="top">CalWORKS LEGAL IMMIGRANT &ndash; FAMILY
        GROUP. Provides aid to families in which a child is
        deprived because of the absence, incapacity or death of
        either parent.</td>
    </tr>
    <tr>
        <td valign="top">CalWORKS Legal Immigrant&mdash;Unemployed</td>
        <td valign="top">CalWORKS LEGAL IMMIGRANT &ndash;
        UNEMPLOYED . Provides aid to families in which a child is
        deprived because of the unemployment of a parent living
        in the home.</td>
    </tr>
    <tr>
        <td valign="top">Children Supported by Public Funds</td>
        <td valign="top">Children Supported by Public Funds.
        Children whose needs are met in whole or in part by
        public funds other than AFDC-FC.</td>
    </tr>
    <tr>
        <td valign="top">CMSP MI &ndash; Restricted</td>
        <td valign="top">CMSP. MI &ndash; Restricted. Covers
        persons who have undetermined immigration status.</td>
    </tr>
    <tr>
        <td valign="top">CMSP Companion Aid Code</td>
        <td valign="top">CMSP Companion Aid Code. Covers persons
        eligible for certain benefits under the Medi-Cal Program
        and other benefits under CMSP. 8F is used in conjunction
        with Medi-Cal aid codes 52, 53 and 57 to facilitate the
        payment of claims for covered benefits. 8F will appear as
        a special aid code and will entitle the eligible cilent
        to full-scope CMSP coverage for those services not
        covered by Medi-Cal.</td>
    </tr>
    <tr>
        <td valign="top">CMSP, MI-A </td>
        <td valign="top">CMSP, MI-A. Covers medically indigent
        adults aged 21 and over but under 65 years who meet the
        eligibility requirements of medically indigent.</td>
    </tr>
    <tr>
        <td valign="top">CMSP, MI-A/Disability Pending </td>
        <td valign="top">CMSP, MI-A/Disability Pending. Covers
        medically indigent adults aged 21 and over but under 65
        years who meet the eligibility requirements of medically
        indigent and have a pending Medi-Cal disability
        application.</td>
    </tr>
    <tr>
        <td valign="top">Continuing Medi-Cal Eligibility</td>
        <td valign="top">Continuing Medi-Cal Eligibility .
        Edwards v. Kizer court order provides for uninterrupted,
        no SOC Medi-Cal benefits for families discontinued from
        AFDC until the family's eligibility or ineligibility for
        Medi-Cal only has been determined and an appropriate
        Notice of Action sent.</td>
    </tr>
    <tr>
        <td valign="top">Continuing TMC</td>
        <td valign="top">Continuing TMC. Provides an additional
        six months of continuing emergency and pregnancy-related
        TMC benefits (no SOC) to qualifying aid code 3T
        recipients.</td>
    </tr>
    <tr>
        <td valign="top">Disabled Adult Child Blindness</td>
        <td valign="top">Disabled Adult Child(ren)/Blindness.</td>
    </tr>
    <tr>
        <td valign="top">Disabled Adult Child Disabled</td>
        <td valign="top">Disabled Adult Child(ren)/Disabled.</td>
    </tr>
    <tr>
        <td valign="top">Emergency Assistance Program</td>
        <td valign="top">Emergency Assistance Program . Covers
        juvenile and child welfare probation cases placed in
        foster care.</td>
    </tr>
    <tr>
        <td valign="top">Entrant Cash Assistance</td>
        <td valign="top">Entrant Cash Assistance. Provides ECA
        benefits to Cuban/Haitian entrants, including
        unaccompanied children who are eligible, during their
        first eight months in the United States. (For entrants,
        the month begins with their date of parole.)
        Unaccompanied children are not subject to the eight-month
        limitation provision.</td>
    </tr>
    <tr>
        <td valign="top">Four Month Continuing</td>
        <td valign="top">Four Month Continuing. Provides four
        months of emergency and pregnancy-related benefits (no
        SOC) for aliens without SIS who are no longer eligible
        for Section 1931(b) due to the collection or increased
        collection of child/spousal support.</td>
    </tr>
    <tr>
        <td valign="top">Four-Month Continuing Eligibility</td>
        <td valign="top">Four-Month Continuing Eligibility.
        Covers persons discontinued from AFDC due to the
        increased collection of child/spousal support payments
        but eligible for Medi-Cal only.</td>
    </tr>
    <tr>
        <td valign="top">Income Disregard Program Pregnant United
        States Citizen/Permanent Resident Alien/PRUCOL Alien</td>
        <td valign="top">Income Disregard Program. Pregnant
        United States Citizen/Permanent Resident Alien/PRUCOL
        Alien. Provides family planning, pregnancy-related and
        postpartum services for any age female if family income
        is at or below 200 percent of the federal poverty level</td>
    </tr>
    <tr>
        <td valign="top">Income Disregard Program. Infant&ndash;
        United States Citizen, Permanent Resident Alien/PRUCOL
        Alien.</td>
        <td valign="top">Income Disregard Program. Infant &ndash;
        United States Citizen, Permanent Resident Alien/PRUCOL
        Alien. Provides full Medi-Calbenefits to infants up to
        one year old and continues beyond one year when inpatient
        status, which began before first birthday, continues and
        family income is at or below 200 percent of the federal
        poverty level.</td>
    </tr>
    <tr>
        <td valign="top">Income Disregard Program&ndash;
        Undocumented/Nonimmigrant Alien </td>
        <td valign="top">Income Disregard Program. Pregnant
        &ndash; Undocumented/Nonimmigrant Alien (but otherwise
        eligible). Provides family planning, pregnancy-related
        and postpartum services for any age female if family
        income is at or below 200 percent of the federal poverty
        level.</td>
    </tr>
    <tr>
        <td valign="top">Income Disregard Program&ndash; Amnesty
        Alien</td>
        <td valign="top">Income Disregard Program. Pregnancy
        &ndash; Amnesty Alien. Provides familyplanning,
        pregnancy-related and postpartum services to any age
        female with income at or below 200 percent of the federal
        poverty level.</td>
    </tr>
    <tr>
        <td valign="top">Income Disregard Program&ndash;
        Undocumented/Nonimmigrant Alien</td>
        <td valign="top">Income Disregard Program. Infant &ndash;
        Undocumented/Nonimmigrant Alien. Provides emergency
        services only for infants under 1 year of age and beyond
        1 year when inpatient status, which began before 1st
        birthday, continues and family income is at or below 200
        ppercent of the federal poverty level.</td>
    </tr>
    <tr>
        <td valign="top">Initial Transitional Medi-Cal</td>
        <td valign="top">Initial Transitional Medi-Cal . Provides
        six months of emergency and pregnancy-related initial TMC
        benefits (no SOC) for aliens who do not have satisfactory
        immigration status (SIS) and have been discontinued from
        Section 1931(b) due to increased earnings from
        employment.</td>
    </tr>
    <tr>
        <td valign="top">Initial Transitional Medi-Cal&ndash; Six
        Months Continuing Eligibility </td>
        <td valign="top">Initial Transitional Medi-Cal &ndash; Six
        Months Continuing Eligibility . Provides coverage to
        certain clients subsequent to AFDC cash grant
        discontinuance due to increased earnings, increased hours
        of employment or loss of the $30 and 1/3 disregard.</td>
    </tr>
    <tr>
        <td valign="top">Kinship Guardianship Assistance Payment</td>
        <td valign="top">Kinship Guardianship Assistance Payment.
        Federal program for children in relative placement
        receiving cash assistance. Also may be State-only program
        for children in relative placement receiving cash
        assistance.</td>
    </tr>
    <tr>
        <td valign="top">Medi-Cal Dialysis Only Program</td>
        <td valign="top">Medi-Cal Dialysis Only Program/Medi-Cal
        Dialysis Supplement Program. Covers persons of any age
        who are eligible only for dialysis and related services.</td>
    </tr>
    <tr>
        <td valign="top">Medi-Cal N/A Family P.A.C.T.</td>
        <td valign="top">Medi-Cal N/A Family P.A.C.T.
        Comprehensive family planning services for low income
        residents of California with no other source of health
        care coverage.</td>
    </tr>
    <tr>
        <td valign="top">Medi-Cal TPN Only Program</td>
        <td valign="top">Medi-Cal TPN Only Program/Medi-Cal TPN
        Supplement Program. Covers persons of any age who are
        eligible for parenteral hyperalimentation and related
        services and persons of any age who are eligible under
        the Medically Needy or Medically Indigent Programs.</td>
    </tr>
    <tr>
        <td valign="top">Medi-Cal Tuberculosis Program</td>
        <td valign="top">Medi-Cal Tuberculosis Program. Covers
        individuals who are TB-infected for TB-related outpatient
        services only.</td>
    </tr>
    <tr>
        <td valign="top">Medically Indigent</td>
        <td valign="top">Medically Indigent &ndash; LTC. Covers
        persons age 21 or older and under 65 years of age who are
        residing in a Skilled Nursing or Intermediate Care
        Facility (SNF or ICF) and meet all other eligibility
        requirements of medically indigent, with or without SOC.</td>
    </tr>
    <tr>
        <td valign="top">MI-Adults Aid Paid Pending</td>
        <td valign="top">MI-Adults Aid Paid Pending. Aid Paid
        Pending for persons over 21 but under 65, with or without
        SOC.</td>
    </tr>
    <tr>
        <td valign="top">MI-Confirmed Pregnancy</td>
        <td valign="top">MI-Confirmed Pregnancy. Covers persons
        aged 21 years or older, with confirmed pregnancy, who
        meet the eligibility requirements of medically indigent.
        Also may covers persons aged 21 or older, with confirmed
        pregnancy, who meet the eligibility requirements of
        medically indigent but are not eligible for 185
        percent/200 percent or the MN programs.</td>
    </tr>
    <tr>
        <td valign="top">MI-Person</td>
        <td valign="top">MI-Person. Covers medically indigent
        persons under 21 who meet the eligibility requirements of
        medical indigence. Covers persons until the age of 22 who
        were in an institution for mental disease before age 21.
        Persons may continue to be eligible under aid code 82
        until age 22 if they have filed for a State hearing.</td>
    </tr>
    <tr>
        <td valign="top">MI-Person SOC</td>
        <td valign="top">MI-Person SOC. Covers medically indigent
        persons under 21 who meet the eligibility requirements of
        medically indigent.</td>
    </tr>
    <tr>
        <td valign="top">Minor Consent Program</td>
        <td valign="top">Minor Consent Program. Covers minors
        aged 12 and under 21. Limited to services related to
        Sexually Transmitted Diseases, sexual assault, drug and
        alcohol abuse, and family planning. Limited to services
        related to pregnancy and family planning.</td>
    </tr>
    <tr>
        <td valign="top">Minor Consent Program</td>
        <td valign="top">Minor Consent Program . Covers minors
        under age 12. Limited to services related to family
        planning and sexual assault.</td>
    </tr>
    <tr>
        <td valign="top">No longer Disabled Children</td>
        <td valign="top">No longer Disabled Children. Covers
        former SSI disabled children under age 18 who lost SSI
        cash benefits due to cessation of disability and who are
        appealing their cessation of SSI disability.</td>
    </tr>
    <tr>
        <td valign="top">OBRA Aliens</td>
        <td valign="top">OBRA Aliens. Covers non-immigrant and
        undocumented aliens who do not have proof of permanent
        resident alien, PRUCOL or amnesty alien status, but who
        are otherwise eligible for Medi-Cal.</td>
    </tr>
    <tr>
        <td valign="top">One-Month Healthy Families Bridge</td>
        <td valign="top">One-Month Healthy Families Bridge .
        Provides one additional calender month of health care
        benefits with no Share of Cost, through the same health
        care delivery system, to Medi-Cal-eligible children
        meeting the criteria of the HF Bridging Program.</td>
    </tr>
    <tr>
        <td valign="top">Personal Responsibility and Work
        Opportunity Reconciliation Act/ No Longer Disabled
        Recipients</td>
        <td valign="top">Personal Responsibility and Work
        Opportunity Reconciliation Act/No Longer Disabled
        Recipients. Former SSI disabled recipients (adults and
        children not in aid code 6R) who are appealing their
        cessation of SSI disability. Also covers children under
        age 18 who lost SSI cash benefits on or after July 1,
        1997, due to PRWORA of 1996, which provides a stricter
        definition of disability for children.</td>
    </tr>
    <tr>
        <td valign="top">Presumptive Eligibility&ndash; Pregnancy
        Verification</td>
        <td valign="top">Presumptive Eligibility&ndash; Pregnancy
        Verification. This option allows the Qualified Provider
        to make a determination of PE for outpatient prenatal
        care services based on preliminary income information. 7F
        is valid for pregnancy test, initial visit, and services
        associated with the intial visit. Persons placed in 7F
        have pregnancy test results that are negagtive.</td>
    </tr>
    <tr>
        <td valign="top">Presumptive Eligibility</td>
        <td valign="top">Presumptive Eligibility&ndash; Ambulatory
        Prenatal Care Services. This option allows the Qualified
        Provider to make a determination of PE for outpatient
        prenatal care services based on preliminary income
        information. 7G is valid for Ambulatory Prenatal Care
        Services. Persons placed in 7G have pregnancy test
        results that are positive.</td>
    </tr>
    <tr>
        <td valign="top">Qualified Medicare Beneficiary</td>
        <td valign="top">Qualified Medicare Beneficiary. Provides
        payment of Medicare Part A premium and Part A and B
        coinsurance and deductibles for eligible low income aged,
        blind, or disabled individuals.</td>
    </tr>
    <tr>
        <td valign="top">Qualified Severely Impaired Working
        Individual Program Aid Code</td>
        <td valign="top">Qualified Severely Impaired Working
        Individual Program Aid Code. Allows recipients of the
        Qualified Severely Impaired Working Individual Program to
        continue their Medi-Cal eligibility.</td>
    </tr>
    <tr>
        <td valign="top">Refugee Cash Assistance</td>
        <td valign="top">Refugee Cash Assistance. Includes
        unaccompanied children. Covers all eligible refugees
        during their first eight months in the United States.
        Unaccompanied children are not subject to the
        eighth-month limitation provision. This population is the
        same as aid code 01, except that they are exempt from
        grant reductions on behalf of the Assistance Payments
        Demonstration Project/California Work Pays Demonstration
        Project.</td>
    </tr>
    <tr>
        <td valign="top">Refugee Medical Assistance/Entrant
        Medical Assistance</td>
        <td valign="top">Refugee Medical Assistance/Entrant
        Medical Assistance. Covers refugees and entrants who need
        Medi-Cal and who do not qualify for or want cash
        assistance.</td>
    </tr>
    <tr>
        <td valign="top">Second Year Transitional Medi-Cal</td>
        <td valign="top">Second Year Transitional Medi-Cal.
        Provides a second year of full-scope TMC benefits for
        citizens and qualified aliens age 19 and older who have
        received six months of additional full-scope TMC benefits
        under aid code 59 and who continue to meet the
        requirements of additional TMC. (State-only program).</td>
    </tr>
    <tr>
        <td valign="top">Section 1931</td>
        <td valign="top">Section 1931(b). Provides emergency and
        pregnancy-related benefits (no SOC) for aliens without
        SIS who meet the income, resources and deprivation
        requirements of the AFDC State Plan in effect July 16,
        1996.</td>
    </tr>
</tbody></table>

		</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>