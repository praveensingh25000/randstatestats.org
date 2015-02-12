<?php
/******************************************
* @Modified on March 22, 2012
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">

	<h2>Texas School District Category Defintions</h2><br/>

<p>School district categories are defined below.</p><br/>

<p class="bblue">Summary Categories</p><br/>

<table>
<table cellspacing=6>
<table border="1" class="collapse">
<tr>
<th height=18 class="gray"><span class="blue">Category</span></th>
<th height=18 class="gray"><span class="blue">Description(s)</span></th>

</tr>

    <tr>
        <td valign="top">EX=Exemplary, RE=Recognized;
        AA=Academically Acceptable; AU=Academically Unacceptable;
        US=Academically Unacceptable due to Special Accreditation
        Investigation; DI=Suspended due to Data Inquiry;
        CS=Charter</td>
        <td valign="top">Rating of the district in October;
        EX=Exemplary, RE=Recognized; AA=Academically Acceptable;
        AU=Academically Unacceptable; US=Academically
        Unacceptable due to Special Accreditation Investigation;
        DI=Suspended due to Data Inquiry; CS=Charter</td>
    </tr>
    <tr>
        <td valign="top">Number of schools</td>
        <td valign="top">A count of schools in a district that
        have a unique state-assigned nine-digit identifier and
        had students enrolled as of October.</td>
    </tr>
    <tr>
        <td valign="top">Number of students</td>
        <td valign="top">The number of students in membership as
        of October at any grade. Membership is defined as the
        count of students enrolled with an average daily
        attendance status code that is not equal to zero.
        Students with a status code of zero, meaning enrolled but
        not </td>
    </tr>
    <tr>
        <td valign="top">Attendance rate (K-12 only)</td>
        <td valign="top">The total number of days, summed for all
        students, that students were present in 1998&#150;99
        divided by the total number of days students were in
        membership in. Only students in grades 1&#150;12 are
        included in the calculations.</td>
    </tr>
    <tr>
        <td valign="top">Dropouts, grades 7-12 percent of total</td>
        <td valign="top">The total number of students reported as
        dropouts in grades 7&#150;12 expressed as a percent of
        the total number of students in attendance at any time
        during the year in grades 7&#150;12. The TEA deletes from
        the count any student who was erroneously reported as a </td>
    </tr>
    <tr>
        <td valign="top">Dropouts, all students grades 9-12
        percent of total</td>
        <td valign="top">The number of students who began 9th
        grade and were identified as dropouts on or before their
        expected graduation year. This count is expressed as a
        percent of the final number of students in the cohort
        after four years. Students who transfer out or trans</td>
    </tr>
    <tr>
        <td valign="top">Number of graduates</td>
        <td valign="top">The number of students who graduated
        during the school year, including the summer. This count
        includes 12th grade graduates, as well as graduates from
        other grades. Counts of graduates identified as receiving
        special education services are included in thi</td>
    </tr>
    <tr>
        <td valign="top">Graduates percent of total</td>
        <td valign="top">The number of students who began 9th
        grade and graduated before or by the end of their
        expected graduation year of. This count is expressed as a
        percent of the final number of students in the cohort
        after four years. Students who transfer out or transfer </td>
    </tr>
    <tr>
        <td valign="top">Students per total staff</td>
        <td valign="top">The total number of students divided by
        the total staff FTE count.</td>
    </tr>
    <tr>
        <td valign="top">Students per teacher</td>
        <td valign="top">The total number of students divided by
        the total teacher FTE count. </td>
    </tr><br/>
</table>

<p align="left">&nbsp;</p>

<p align="left" class="bblue">Assessment</span></p><br/>

<table>
<table cellspacing=6>
<table border="1" class="collapse">
<tr>
<th height=18 class="gray"><span class="blue">Category</span></th>
<th height=18 class="gray"><span class="blue">Description(s)</span></th>
    <tr>
        <td>Category</td>
        <td>Definition(s)</td>
    </tr>
    <tr>
        <td valign="top">Percent students passing all TAAS tests</td>
        <td valign="top">The total number of students who passed
        all the TAAS tests they attempted expressed as a
        percentage of the total number of students who took one
        or more tests. The performance of students tested in
        grades 3&#150;8 and 10 in reading and mathematics, and
        grades </td>
    </tr>
    <tr>
        <td valign="top">Percent students passing TAAS reading
        tests</td>
        <td valign="top">For all grades, the total number of
        students who passed TAAS reading, expressed as a
        percentage of the total number of students tested in
        reading. The performance of tested special education
        students and students taking the Spanish TAAS in grades
        3&#150;6 are </td>
    </tr>
    <tr>
        <td valign="top">Percent students passing TAAS writing
        tests</td>
        <td valign="top">For all grades, the total number of
        students who passed TAAS writing, expressed as a
        percentage of the total number of students tested in
        writing. The performance of tested special education
        students and students taking the Spanish 4th grade
        writing test </td>
    </tr>
    <tr>
        <td valign="top">Percent students passing TAAS math tests</td>
        <td valign="top">For all grades, the number of students
        who passed TAAS mathematics, expressed as a percentage of
        the total number of students tested in mathematics. The
        performance of tested special education students and
        students taking the Spanish TAAS in grades 3&#150;6 ar</td>
    </tr>
    <tr>
        <td valign="top">Percent African-American students
        passing TAAS reading tests</td>
        <td valign="top">For all grades and the subject areas of
        TAAS reading, writing, and mathematics,the number of
        African American students who passed all the tests they
        attempted, expressed as a percentage of the total number
        of African American students tested on at least o</td>
    </tr>
    <tr>
        <td valign="top">Percent Hispanic students passing TAAS
        reading tests</td>
        <td valign="top">For all grades and the subject areas of
        TAAS reading, writing, and mathematics,the number of
        Hispanic students who passed all the tests they
        attempted, expressed as a percentage of the total number
        of Hispanic students tested on at least one examination. </td>
    </tr>
    <tr>
        <td valign="top">Percent White students passing TAAS
        reading tests</td>
        <td valign="top">For all grades and the subject areas of
        TAAS reading, writing, and mathematics,the number of
        White students who passed all the tests they attempted,
        expressed as a percentage of the total number of White
        students tested on at least one examination. The pe</td>
    </tr>
    <tr>
        <td valign="top">Percent other race students passing TAAS
        reading tests</td>
        <td valign="top">For all grades and the subject areas of
        TAAS reading, writing, and mathematics,the number of
        Asian/Pacific Islander and Native American students who
        passed all the tests they attempted, expressed as a
        percentage of the total number of Asian/Pacific Island</td>
    </tr>
    <tr>
        <td valign="top">Percent students passing TAAS reading
        tests</td>
        <td valign="top">For all grades and the subject areas of
        TAAS reading, writing, and mathematics,the number of
        economically disadvantaged students who passed all the
        tests they attempted, expressed as a percentage of the
        total number of economically disadvantaged students </td>
    </tr>
    <tr>
        <td valign="top">Percent students taking SAT or ACT</td>
        <td valign="top">The number of graduates who took either
        the College Board's SAT I or the ACT, Inc. (Enhanced) ACT
        assessment, expressed as a percent of all graduates. The
        count of graduates in the denominator does not include
        special education graduates; however, special</td>
    </tr>
    <tr>
        <td valign="top">Percent students scoring above criterion
        on SAT or ACT</td>
        <td valign="top">The number of examinees who, on their
        most recent test, scored at or above the criterion score
        (1110 on the SAT I or 24 on the ACT) expressed as a
        percent of all examinees.</td>
    </tr>
    <tr>
        <td valign="top">Average SAT score</td>
        <td valign="top">The sum of the mathematics and verbal
        SAT I scores for all students divided by the number of
        examinees. Total scores for the SAT I range from 400 to
        1600. These results include only the most recent scores
        received on an SAT I test taken anytime during the</td>
    </tr>
    <tr>
        <td valign="top">Average ACT score</td>
        <td valign="top">The average of the ACT composite scores
        (an average of English, mathematics, reading, and science
        reasoning portions of the ACT), created by summing the
        composite scores and dividing by the number of ACT
        examinees. Composite scores for the ACT range from </td>
    </tr>
</table><br/>

<p align="left">&nbsp;</p>

<p align="left" class="bblue">District Finances</span></p><br/>

<table>
<table cellspacing=6>
<table border="1" class="collapse">
<tr>
<th height=18 class="gray"><span class="blue">Category</span></th>
<th height=18 class="gray"><span class="blue">Description(s)</span></th>
    <tr>
        <td valign="top">Taxable property value per pupil</td>
        <td valign="top">The district's total taxable property
        value in the previous fiscal year divided by the total
        number of students in the district in the current school
        year. This measure is often referred to as
        &#147;wealth.&#148; In addition to the traditional
        measure of property v</td>
    </tr>
    <tr>
        <td valign="top">Equalized total tax rate</td>
        <td valign="top">The sum of the district maintenance and
        operation (M&amp;O) and debt service (Interest &amp;
        Sinking fund) effective tax rates. The components of this
        total rate are calculated by dividing the previous year
        levy amounts by the CPTD property value for the previous</td>
    </tr>
    <tr>
        <td valign="top">State aid per pupil</td>
        <td valign="top">The amount of state money distributed to
        the school district from the Foundation School Fund
        divided by the district's total count of students in
        membership. Beginning this year, state aid includes the
        Instructional Facilities Allotment (IFA) and the Exis</td>
    </tr>
    <tr>
        <td valign="top">Total revenue</td>
        <td valign="top">The total for all revenue budgeted in
        the general fund (199, including state food services),
        the National School Breakfast and Lunch Program (240,
        701), and the debt service funds (599). Special Revenue
        Funds (including shared services arrangements) and t</td>
    </tr>
    <tr>
        <td valign="top">Total revenue per pupil</td>
        <td valign="top">Total revenue divided by total students.</td>
    </tr>
    <tr>
        <td valign="top">Percent revenue from state sources</td>
        <td valign="top">Revenue from state sources, such as per
        capita and foundation program payments, revenue from
        other state-funded programs and revenue from other state
        agencies, expressed as a percent of total revenue. State
        revenue includes Teacher Retirement System benef</td>
    </tr>
    <tr>
        <td valign="top">Percent revenue from local sources</td>
        <td valign="top">Revenue from local taxes, other local
        sources, and intermediate sources expressed as a percent
        of total revenue.</td>
    </tr>
    <tr>
        <td valign="top">Percent revenue from federal sources</td>
        <td valign="top">Revenue received directly from the
        federal government, from other state agencies, or
        distributed by the TEA for career and technology
        education, for programs for educationally disadvantaged
        children, for food service programs, and for other
        federal progra</td>
    </tr>
    <tr>
        <td valign="top">Prior year fund balance</td>
        <td valign="top">The amount of unreserved, undesignated
        surplus fund balance that existed at the end of the
        previous school year. In most districts, this amount is
        equivalent to the fund balance at the beginning of
        current year.</td>
    </tr>
    <tr>
        <td valign="top">Current year fund balance share of
        expenditures</td>
        <td valign="top">The amount of surplus fund balance
        expressed as a percent of the total budgeted expenditures
        (for the general fund) for the current year.</td>
    </tr>
    <tr>
        <td valign="top">Total expenditures</td>
        <td valign="top">Budgeted outlays of money for all
        functions and objects, except for expenditures budgeted
        in fund 600, the Capital Projects Fund. Expenditures that
        are budgeted in the Special Revenues Funds, including
        shared services arrangements, are also not included. </td>
    </tr>
    <tr>
        <td valign="top">Instructional expenditures share of
        total</td>
        <td valign="top">The percentage of total expenditures
        budgeted for instruction in the district. Instructional
        expenditures include all activities dealing directly with
        the interaction between teachers and students, including
        instruction aided with computers; and, expendit</td>
    </tr>
    <tr>
        <td valign="top">Central administration expenditures
        share of total</td>
        <td valign="top">The percentage of total expenditures
        budgeted for central administration in the district.
        Central administrative expenditures include the general
        administration of the district, instructional leadership,
        and data processing services. </td>
    </tr>
    <tr>
        <td valign="top">School administrative expenditures share
        of total</td>
        <td valign="top">The percentage of total expenditures
        budgeted for the administration of the schools in the
        district. These are expenditures for directing and
        managing a school.</td>
    </tr>
    <tr>
        <td valign="top">Physical plant expenditures share of
        total</td>
        <td valign="top">The percentage of total expenditures
        budgeted for keeping the physical plant and grounds in
        effective working condition. This includes security and
        monitoring services designed to keep student and staff
        surroundings safe.</td>
    </tr>
    <tr>
        <td valign="top">Other operating expenditures share of
        total</td>
        <td valign="top">The percentage of total expenditures
        budgeted for all other operating costs in the district.
        Other operating expenditures include student support
        services, student transportation, food services,
        cocurricular/extracurricular activities, and curriculum
        and </td>
    </tr>
    <tr>
        <td valign="top">Non-operating expenditures share of
        total</td>
        <td valign="top">The percentage of total expenditures
        budgeted for non-operating costs in the district.
        Non-operating expenditures include capital outlay not
        made from the Capital Projects Fund (fund 600); debt
        service expenditures; and community services
        expenditures.</td>
    </tr>
    <tr>
        <td valign="top">Total operating expenditures</td>
        <td valign="top">The sum of all expenditures budgeted for
        the operation of the district. Operating expenses include
        payroll, professional and contracted services, and
        supplies and materials. Operating expenditures are a
        subset of total expenditures; they do not include de</td>
    </tr>
    <tr>
        <td valign="top">Operating expenditures per pupil</td>
        <td valign="top">Total operating expenditures divided by
        total students.</td>
    </tr>
    <tr>
        <td valign="top">Instructional expenditures</td>
        <td valign="top">The sum of budgeted expenditures for all
        activities dealing directly with the interaction between
        teachers and students, including instruction aided with
        computers; and, expenditures to provide resources for
        juvenile justice alternative education programs</td>
    </tr>
    <tr>
        <td valign="top">Instructional expenditures per pupil</td>
        <td valign="top">Budgeted instructional expenditures
        divided by total students.</td>
    </tr>
    <tr>
        <td valign="top">Regular education expenditures share of
        total</td>
        <td valign="top">Expenditures budgeted for the regular
        education program expressed as a percent of total
        instructional expenditures.</td>
    </tr>
    <tr>
        <td valign="top">Special education expenditures share of
        total</td>
        <td valign="top">Expenditures budgeted for the special
        education program expressed as a percent of total
        instructional expenditures.</td>
    </tr>
    <tr>
        <td valign="top">Compensatory education expenditures
        share of total</td>
        <td valign="top">Expenditures budgeted for the
        compensatory education program expressed as a percent of
        total instructional expenditures.</td>
    </tr>
    <tr>
        <td valign="top">Bilingual-ESL education expenditures
        share of total</td>
        <td valign="top">Expenditures budgeted for the bilingual
        education and ESL programs expressed as a percent of
        total instructional expenditures.</td>
    </tr>
    <tr>
        <td valign="top">Career and technology education
        expenditures share of total</td>
        <td valign="top">Expenditures budgeted for the career and
        technology education program expressed as a percent of
        total instructional expenditures.</td>
    </tr>
    <tr>
        <td valign="top">Gifted and talented (GATE) education
        expenditures share of total</td>
        <td valign="top">Expenditures budgeted for the gifted and
        talented education program expressed as a percent of
        total instructional expenditures.</td>
    </tr>
</table><br/>

<p>&nbsp;</p>

<p class="bblue">Student Demographics</p><br/>

<table>
<table cellspacing=6>
<table border="1" class="collapse">
<tr>
<th height=18 class="gray"><span class="blue">Category</span></th>
<th height=18 class="gray"><span class="blue">Description(s)</span></th>
    <tr>
        <td valign="top">African-American students percent of
        total</td>
        <td valign="top">Percentage of students reported as
        African American</td>
    </tr>
    <tr>
        <td valign="top">Hispanic students percent of total</td>
        <td valign="top">Percentage of students reported as
        Hispanic</td>
    </tr>
    <tr>
        <td valign="top">White students percent of total</td>
        <td valign="top">Percentage of students reported as White</td>
    </tr>
    <tr>
        <td valign="top">Other students percent of total</td>
        <td valign="top">Percentage of students reported as
        either Asian/Pacific Islander or Native American</td>
    </tr>
    <tr>
        <td valign="top">Economically disadvantaged students
        percent of total</td>
        <td valign="top">Percentage of total students reported as
        economically disadvantaged. Economically disadvantaged
        students are those who are reported as eligible for free
        or reduced-price meals under the National School Lunch
        and Child Nutrition Program, or other public as</td>
    </tr>
    <tr>
        <td valign="top">Special education students percent of
        total</td>
        <td valign="top">Students identified as participating in
        programs for students with disabilities expressed as a
        percent of total students. Students are placed in special
        education by their Admission, Review, and Dismissal (ARD)
        committee. Students in special education may</td>
    </tr>
    <tr>
        <td valign="top">Bilingual/ESL students percent of total</td>
        <td valign="top">Students identified as participating in
        bilingual education or English as a second language (ESL)
        expressed as a percent of total students. Students in
        bilingual/ESL education may also be counted in other
        special programs such as special education or care</td>
    </tr>
    <tr>
        <td valign="top">Career and technology education students
        percent of total</td>
        <td valign="top">Students identified as taking career and
        technology education courses expressed as a percent of
        total students. Students taking these courses may also be
        counted in other special programs such as special or
        bilingual education.</td>
    </tr>
    <tr>
        <td valign="top">Gifted and talented (GATE) students
        percent of total</td>
        <td valign="top">Students identified and served in
        state-approved gifted and talented programs expressed as
        a percent of total students. Students in gifted and
        talented education may also be counted in other special
        programs such as career and technology or bilingual/ESL </td>
    </tr>
</table><br/>

<p align="left">&nbsp;</p>

<p align="left" class="bblue">Teacher Salary and Demographics</p><br/>

<table>
<table cellspacing=6>
<table border="1" class="collapse">
<tr>
<th height=18 class="gray"><span class="blue">Category</span></th>
<th height=18 class="gray"><span class="blue">Description(s)</span></th></tr>
    <tr>
        <td valign="top">Staff Full-time equivalent (FTE)</td>
        <td valign="top">A count of all personnel employed by the
        school district as of the fall of the school year,
        including both professional and paraprofessional
        positions. All staff counts are expressed as fulltime
        equivalents (FTEs). The appropriate portion of an FTE is
        all</td>
    </tr>
    <tr>
        <td valign="top">Teacher full-time equivalent (FTE)</td>
        <td valign="top">The FTE count of personnel categorized
        as teachers, including special duty and permanent
        substitute teachers. Statewide, 0.6 percent of all
        teacher FTEs are categorized as permanent substitutes, a
        role that should not be confused with persons hired on a
        d</td>
    </tr>
    <tr>
        <td valign="top">Central office administrator percent of
        total staff</td>
        <td valign="top">The FTE count of personnel classified as
        administrators in the central office expressed as a
        percent of total staff FTEs. Central office
        administrators include superintendents, assistant
        superintendents, business managers, tax
        assessor-collectors, and dir</td>
    </tr>
    <tr>
        <td valign="top">All administrator percent of total staff</td>
        <td valign="top">The FTE count of personnel classified as
        school administrators expressed as a percent of total
        staff FTEs. School administrators include principals and
        assistant principals, as well as instructional officers
        and athletic directors, if reported at a specif</td>
    </tr>
    <tr>
        <td valign="top">Support staff percent of total staff</td>
        <td valign="top">The FTE count of personnel categorized
        as support staff expressed as a percent of total staff
        FTEs. Support staff are defined as therapists,
        psychologists, counselors, diagnosticians, physicians and
        nurses, librarians, department heads, registrars, and mi</td>
    </tr>
    <tr>
        <td valign="top">Teacher percent of total staff</td>
        <td valign="top">The teacher FTE count expressed as a
        percent of total staff FTEs.</td>
    </tr>
    <tr>
        <td valign="top">Educational aide share of total staff</td>
        <td valign="top">The FTE count of personnel categorized
        as educational aides, or educational aides/interpreters,
        expressed as a percent of total staff FTEs. Educational
        aides perform routine classroom tasks under the general
        supervision of a certified teacher or teaching </td>
    </tr>
    <tr>
        <td valign="top">Auxilliary staff share of total staff</td>
        <td valign="top">The FTE count of personnel categorized
        as auxiliary staff expressed as a percent of total staff
        FTEs. Auxiliary staff are those personnel reported
        without a role but with a PEIMS employment and payroll
        record. Examples include food service workers, bus dr</td>
    </tr>
    <tr>
        <td valign="top">Average staff salary, central
        administrators</td>
        <td valign="top">The sum of all the salaries of central
        administrators divided by the total FTE count of central
        administrators. The salary amount is pay for regular
        duties only; any supplements are excluded.</td>
    </tr>
    <tr>
        <td valign="top">Average staff salary, all administrators</td>
        <td valign="top">The sum of all the salaries of school
        administrators divided by the total FTE count of school
        administrators. The salary amount is pay for regular
        duties only; any supplements are excluded.</td>
    </tr>
    <tr>
        <td valign="top">Average professional staff salary</td>
        <td valign="top">The sum of all the salaries of
        professional support staff divided by the total FTE count
        of professional support staff. The salary amount is pay
        for regular duties only; any supplements are excluded.</td>
    </tr>
    <tr>
        <td valign="top">Average teacher salary</td>
        <td valign="top">The sum of all the salaries of teachers
        divided by the total FTE count of teachers. The salary
        amount is pay for regular duties only; supplemental
        payments for coaching, band and orchestra assignments,
        and club sponsorships are excluded.</td>
    </tr>
    <tr>
        <td valign="top">Percent non-white teachers</td>
        <td valign="top">The FTE count of all personnel reported
        as non-White expressed as a percent of total staff FTEs. </td>
    </tr>
    <tr>
        <td valign="top">Percent teachers with at least 1 permit</td>
        <td valign="top">The FTE count of teachers holding at
        least one permit as of the fall of the 1999&#150;2000
        school year, expressed as a percent of the total teacher
        FTE count. Teachers with multiple permits are counted
        only once. Five types of permits can be issued that
        meetdi</td>
    </tr>
    <tr>
        <td valign="top">Teachers with less than 6 years
        experience</td>
        <td valign="top">The FTE count of teachers with zero
        through five years of total professional experience
        expressed as a percent of the total teacher FTE count.
        Total years of professional experience includes
        experience earned in another Texas school district or in
        another</td>
    </tr>
    <tr>
        <td valign="top">Average years of experience</td>
        <td valign="top">A weighted average obtained by
        multiplying each teacher's FTE count by his or her years
        of experience, summing for all weighted counts, and then
        dividing by total teacher FTEs. Adjustments are made so
        that teachers with zero years of experience are approp</td>
    </tr>
    <tr>
        <td valign="top">Percent teachers with advanced degrees</td>
        <td valign="top">The FTE count of teachers with master's
        or doctorate degrees expressed as a percent of the total
        teacher FTE count.</td>
    </tr>
    <tr>
        <td valign="top">Percent teachers not returning to work</td>
        <td valign="top">The FTE count of teachers not employed
        in the district in the fall, who were employed in the
        district in the previous fall, divided by the teacher FTE
        count for the fall the previous year. Social security
        numbers of reported teachers are compared from the</td>
    </tr>
    <tr>
        <td valign="top">African-American teacher percent of
        total</td>
        <td valign="top">The FTE count of teachers reported as
        African American expressed as a percent of the total
        teacher FTE count.</td>
    </tr>
    <tr>
        <td valign="top">Hispanic teacher percent of total</td>
        <td valign="top">The FTE count of teachers reported as
        Hispanic expressed as a percent of the total teacher FTE
        count.</td>
    </tr>
    <tr>
        <td valign="top">White teacher percent of total</td>
        <td valign="top">The FTE count of teachers reported as
        White expressed as a percent of the total teacher FTE
        count.</td>
    </tr>
    <tr>
        <td valign="top">Other teacher percent of total</td>
        <td valign="top">The FTE count of teachers reported as
        Asian/Pacific Islander or Native American expressed as a
        percent of the total teacher FTE count.</td>
    </tr>
    <tr>
        <td valign="top">Percent non-special eduation teachers</td>
        <td valign="top">The FTE count of teachers who serve
        students receiving regular education instruction
        expressed as a percent of the total teacher FTE count.
        FTE values are allocated across student population types
        for teachers who serve multiple populations.</td>
    </tr>
    <tr>
        <td valign="top">Percent special eduation teachers</td>
        <td valign="top">The FTE count of teachers who serve
        students receiving special education instruction
        expressed as a percent of the total teacher FTE count.
        FTE values are allocated across student population types
        for teachers who serve multiple populations.</td>
    </tr>
    <tr>
        <td valign="top">Percent teachers involved in
        compensatory education</td>
        <td valign="top">The FTE count of teachers who serve
        students receiving compensatory education instruction
        expressed as a percent of the total teacher FTE count.
        FTE values are allocated across student population types
        for teachers who serve multiple populations.</td>
    </tr>
    <tr>
        <td valign="top">Percent teachers involved in bilingual
        eduation</td>
        <td valign="top">The FTE count of teachers who serve
        students receiving bilingual education or English as a
        second language (ESL) instruction expressed as a percent
        of the total teacher FTE count. FTE values are allocated
        across student population types for teachers who s</td>
    </tr>
    <tr>
        <td valign="top">Percent teachers involved in career and
        technology education</td>
        <td valign="top">The FTE count of teachers who serve
        students receiving career and technology education
        instruction expressed as a percent of the total teacher
        FTE count. FTE values are allocated across student
        population types for teachers who serve multiple
        populations.</td>
    </tr>
    <tr>
        <td valign="top">Percent teachers involved in GATE,
        honors, or migrant programs</td>
        <td valign="top">The FTE count of teachers who serve
        students receiving gifted and talented education
        instruction, students in honors classes, and students
        served in migrant programs, expressed as a percent of the
        total teacher FTE count. On average, 99 percent of this
        ca</td>
    </tr>
</table>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>