<?php
/******************************************
* @Modified on April 5, 2013
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

		<h2>CalPERS Contracting Agency Benefits</h2><br/>
		
			<p>
			<table border=1 class="collapse">
				<tr class="thead_gr">
					<th>Category</th><th>Definition(s)</th>
				</tr>
				<tr>
					<td>Coverage Group</td>
					<td>As defined.</td>
				</tr>
				<tr>
					<td>Coverage Type</td>
					<td>As defined.</td>
				</tr>
				<tr>
					<td>Benefit Formula</td>
					<td>Defined as x% * salary * years of service; e.g., 3% at 55 equals for a retiree at age 55 with 30 years service equals 3 * 30 * final salary.  See] Benefit Formulas below for specifics.</td>
				</tr>
				<tr>
					<td>Final Compensation Offsett</td>
					<td>Y = Final compensation used to calculate the retirement benefit is offset by $133.33 (or by 1/3 if the final compensation is less than $400). N = No offset is applied to the final compensation used to calculate the retirement benefit.</td>
				</tr>
				<tr>
					<td>Final Compensation Period</td>
					<td>1 = retirement benefit is based on a one-year final average salary; 3 = retirement benefit is based on a three-year final average salary.</td>
				</tr>
				<tr>
					<td>COLA (In %)</td>
					<td>CalPERS retirees and survivors receive a COLA of 2% per year on a compounded basis, no greater than the cumulative change in the consumer price index since the date of retirement. Some contracting agencies provide increases of 3%, 4%, or 5% (also limited to the cumulative increase in the consumer price index).</td>
				</tr>
				<tr>
					<td>Pre-Retirement Option 2</td>
					<td>Y = employer has contracted for this benefit, which provides, upon death of a member prior to retirement, an allowance equal to the retirement benefit the member would have received had he or she retired on the date of death and elected Option 2 Settlement. A retiree who elects Option 2 Settlement receives an allowance that has been reduced so that it will continue to be paid to a surviving beneficiary after the retireeUs death.</td>
				</tr>
				<tr>
					<td>Sick Leave Credit</td>
					<td>Y = agency has contracted for its employees to receive additional service credit for unused sick leave.</td>
				</tr>
				<tr>
					<td>Post Retirement Survivor Allowance</td>
					<td>Y = employer has contracted for this benefit, which provides, upon death of a member after retirement or disability, a continued allowance to the surviving spouse until death or remarriage, or to surviving children or dependent parents.</td>
				</tr>
				<tr>
					<td>Ordinary Disability Increase</td>
					<td>Benefit applies to future ordinary disability retirements of miscellaneous members and of non-job-related disability retirements of Safety members. The current statutory level of disability retirement benefits for members with at least five years of credited service (1.8% of final compensation for each year of service, with a 33-1/3% maximum) would be raised to a minimum benefit of 30% of final compensation for five years of service and increased by 1% of final compensation for each additional year of service to a maximum benefit of 50% of final compensation. Under no circumstances may the disability retirement allowance be more than the service retirement allowance if the member were to continue in employment and retire at age 60.</td>
				</tr>
				<tr>
					<td>1959 Survivor Benefit</td>
					<td>This optional benefit provides the following monthly allowance to beneficiaries of a member who dies prior to retirement. This benefit is in addition to the basic death benefit or the 1957 survivor benefit, but would be reduced by the amount of the industrial death benefits, if payable.</td>
				</tr>
				<tr>
					<td>Member Contribution Rate</td>
					<td>The contribution rate paid by members as a percent of payroll. Some agencies use a fixed dollar amount, determined annually. These rates are designated as 'Varies.'</td>
				</tr>
				<tr>
					<td>Employer Contribution Rate (In %)</td>
					<td>The contribution rate that is paid by the contracting agency as a percentage of payroll or a fixed dollar amount determined annually.</td>
				</tr>
			</table><br/>
			<a name="pools"></a>
			<h2>CalPERS Contracting Agency Benefit Formulas</h2><br/>

			<table border=1 class="collapse">

				<tr class="thead_gr">
					<th>Benefit Formula/Risk Pool</th>
					<th colspan=2>Employees</th>
				</tr>
				<tr>
					<td>2% at 50</td>
					<td colspan=2>Local Safety Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>% per year of service</td>
				</tr>
				<tr>
					<td></td><td>50 and older</td><td>   3.00%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>3% at 55</td><td colspan=2>Local Safety Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>    % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50 </td><td>    2.40%</td>
				</tr>
				<tr>
					<td></td><td>52 </td><td>    2.64%</td>
				</tr>
				<tr>
					<td></td><td>54 </td><td>    2.88%</td>
				</tr>
				<tr>
					<td></td><td>55 and older</td><td>   3.00%</td>
				</tr>
				<tr class="thead_gr">
					<td colspan=3>
				<tr>
					<td>2% at 50</td><td colspan=2>Local Safety Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>    % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50</td><td>     2.00%</td>
				</tr>
				<tr>
					<td></td><td>52</td><td>     2.28%</td>
				</tr>
				<tr>
					<td></td><td>54</td><td>     2.56%</td>
				</tr>
				<tr>
					<td></td><td>55 and older</td><td>   2.70%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>0.5% at age 55</td><td colspan=2>Local Safety Members</td>
				</tr>
				<tr>
					<td></td><td>Entry age</td><td>      % per year of service</td>
				</tr>
				<tr>
					<td></td><td>25</td><td>     1.67%</td>
				</tr>
				<tr>
					<td></td><td>30</td><td>     2.00%</td>
				</tr>
				<tr>
					<td></td><td>35 and older</td><td>   2.50%</td>
				</tr>
				<tr>
					<td colspan=3 class="ind1em">Note: If a member retires between the ages of 50 and 55, the above percent factors must be&nbsp;&nbsp;<br>discounted as follows:</td>
				</tr>
				<tr>
					<td> </td><td>Age</td><td>   Discount factor</td>
				</tr>
				<tr>
					<td> </td><td>50</td><td>    0.713</td>
				</tr>
				<tr>
					<td></td><td>52 </td><td>    0.814</td>
				</tr>
				<tr>
					<td></td><td>54 </td><td>    0.933</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>2% at 55</td><td colspan=2>Local Safety Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>    % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50 </td><td>    1.43%</td>
				</tr>
				<tr>
					<td></td><td>52 </td><td>    1.63%</td>
				</tr>
				<tr>
					<td></td><td>55 and older</td><td>   2.00%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>2% at 55</td><td colspan=2>Local Misc. Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>    % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50 </td><td>    1.43%</td>
				</tr>
				<tr>
					<td></td><td>55 </td><td>    2.00%</td>
				</tr>
				<tr>
					<td></td><td>60 </td><td>    2.26%</td>
				</tr>
				<tr>
					<td></td><td>63 and older </td><td>  2.42%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>2.5% at 55</td><td colspan=2>Local Safety Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>    % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50 </td><td>    2.00%</td>
				</tr>
				<tr>
					<td></td><td>52 </td><td>    2.20%</td>
				</tr>
				<tr>
					<td></td><td>55 and older </td><td>  2.50%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>2% at 60</td><td colspan=2>Local Misc. Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>    % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50 </td><td>    1.09%</td>
				</tr>
				<tr>
					<td></td><td>55 </td><td>    1.46%</td>
				</tr>
				<tr>
					<td></td><td>60 </td><td>    2.00%</td>
				</tr>
				<tr>
					<td></td><td>63 and older</td><td>   2.42%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>1.25% at 60</td><td colspan=2>Local Safety Members</td>
				</tr>
				<tr>
					<td></td><td>Age </td><td>   % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50  </td><td>   0.62%</td>
				</tr>
				<tr>
					<td></td><td>55  </td><td>   0.87%</td>
				</tr>
				<tr>
					<td></td><td>60  </td><td>   1.25%</td>
				</tr>
				<tr>
					<td></td><td>65  </td><td>   1.75%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>2.5% at 55</td><td colspan=2>Local Misc. Members</td>
				</tr>
				<tr>
					<td></td><td>Age </td><td>   % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50  </td><td>   2.00%</td>
				</tr>
				<tr>
					<td></td><td>52  </td><td>   2.20%</td>
				</tr>
				<tr>
					<td></td><td>55 and older </td><td>  2.50%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>2.7% at 55</td><td colspan=2>Local Misc. Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>    % per year of service</td>
				</tr>
				<tr>
					<td></td><td>50</td><td>     2.00%</td>
				</tr>
				<tr>
					<td></td><td>52</td><td>     2.28%</td>
				</tr>
				<tr>
					<td></td><td>55 and older</td><td>   2.70%</td>
				</tr>
				<tr class="thead_gr"><td colspan=3>
				<tr>
					<td>3% at 60</td><td colspan=2>Local Misc. Members</td>
				</tr>
				<tr>
					<td></td><td>Age</td><td>% per year of service</td>
				</tr>
				<tr>
					<td></td><td>50</td><td>2.00%</td>
				</tr>
				<tr>
					<td></td><td>52</td><td>2.20%</td>
				</tr>
				<tr>
					<td></td><td>54</td><td>2.40%</td>
				</tr>
				<tr>
					<td></td><td>56</td><td>2.60%</td>
				</tr>
				<tr>
					<td></td><td>58</td><td>2.80%</td>
				</tr>
				<tr>
					<td></td><td>60</td><td>3.00%</td>
				</tr>

			</table>
			</p>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>