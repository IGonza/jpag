<jpaginate>
	<loadJQUERY>false</loadJQUERY>
	<!--<loadStartPage>No</loadStartPage>-->
	<maxResults>
		<number>10</number>
		<number>20</number>
		<number>50</number>
		<number>100</number>
	</maxResults>

	<filters visible="1">
		<multifilter>
			<type>menu</type>
			<fieldName>
				<field>c.outsidecustomernumber:Customer Number</field>
			</fieldName>
			<fieldName>
				<field>c.companyname:Company Name</field>
			</fieldName>
			<fieldName>
				<field>c.firstname:First Name</field>
			</fieldName>
			<fieldName>
				<field>c.lastname:Last Name</field>
			</fieldName>
			<fieldName>
				<field>c.email:Email</field>
			</fieldName>
			<fieldName>
				<field>a.addresspostcode:Zip</field>
				<sql>SELECT c.customerid, DATE_FORMAT(c.datecreated,"%Y-%m-%d") as datecreated, c.firstname,c.lastname,c.email,c.customerflagged, a.* FROM (*CDB*).`4tbl_customers` c LEFT JOIN (*CDB*).`4tbl_customers_addresses` a ON c.customerid = a.customerid  WHERE 2</sql>
			</fieldName>
			<fieldName>
				<field>a.addressstreet1:Address</field>
				<sql>SELECT c.customerid, DATE_FORMAT(c.datecreated,"%Y-%m-%d") as datecreated, c.firstname,c.lastname,c.email,c.customerflagged, a.* FROM (*CDB*).`4tbl_customers` c LEFT JOIN (*CDB*).`4tbl_customers_addresses` a ON c.customerid = a.customerid  WHERE 2</sql>
			</fieldName>
			<fieldName>
				<field>CONCAT(a.`countrycode`,a.`areacode`,a.`prefix`,a.`suffix`):Phone</field>
				<sql>SELECT c.customerid, DATE_FORMAT(c.datecreated,"%Y-%m-%d") as datecreated, c.firstname,c.lastname,c.email,c.customerflagged, CONCAT(a.`countrycode`,a.`areacode`,a.`prefix`,a.`suffix`) AS phonenumber FROM (*CDB*).`4tbl_customers` c LEFT JOIN (*CDB*).`4tbl_customers_commo` a ON c.customerid = a.customerid  WHERE 2</sql>
			</fieldName>
			<fieldName>
				<field>ccnumber:CC Number</field>
				<sql>SELECT c.customerid, DATE_FORMAT(c.datecreated,"%Y-%m-%d") as datecreated, c.firstname,c.lastname,c.email,c.customerflagged, a.* FROM (*CDB*).`4tbl_customers` c LEFT JOIN (*CDB*).`4tbl_customers_pay_cc` a ON c.customerid = a.customerid  WHERE 2</sql>
			</fieldName>
			<fieldName>
				<field>gw_authcode:Auth Code</field>
				<sql>SELECT c.customerid, DATE_FORMAT(c.datecreated,"%Y-%m-%d") as datecreated, c.firstname,c.lastname,c.email,c.customerflagged, s.gw_authcode FROM (*CDB*).`4tbl_customers` c, (*CDB*).`4tbl_orders` o, (*CDB*).`4tbl_transactions_apis_sale` s WHERE c.customerid = o.customerid AND o.orderid = s.orderid</sql>
			</fieldName>
			<fieldName>
				<field>c.customerid:Customer ID</field>
				<compare>equal</compare>
			</fieldName>
			<defaultValue>1</defaultValue>
		</multifilter>
        
        
	</filters>

	
	<!--<mainSQL>SELECT c.customerid,c.firstname,c.lastname,c.email,c.customerflagged, DATE_FORMAT(c.datecreated,"%Y-%m-%d") as datecreated, CONCAT(cc.countrycode,cc.areacode,cc.prefix,cc.suffix) AS `phone`, ca.addresscity, ca.regionsid, ca.addresspostcode, ca.countriesid FROM (*CDB*).`4tbl_customers` c LEFT JOIN (*CDB*).`4tbl_customers_commo` cc ON c.defaultcommoid = cc.commoid LEFT JOIN (*CDB*).`4tbl_customers_addresses` ca ON c.billingaddressid = ca.addressid WHERE 1 </mainSQL>-->
	<mainSQL>SELECT c.customerid, c.outsidecustomernumber, DATE_FORMAT(c.datecreated,"%Y-%m-%d") as datecreated, CONCAT(cc.countrycode,cc.areacode,cc.prefix,cc.suffix) AS `phonenumber`, c.firstname,c.lastname,c.email,c.customerflagged FROM (*CDB*).`4tbl_customers` c LEFT JOIN (*CDB*).`4tbl_customers_commo` cc ON c.defaultcommoid = cc.commoid WHERE 1 </mainSQL>
	<tableID>customerid</tableID>
	<default_sort>4,asc</default_sort>
	<columns>
		<column style="width:10px;">
			<content plugin="rowNumbers"></content>
		</column>
        <column style="width:17px">
			<content plugin="flagger" class="flagger">{*customerflagged*}</content>
		</column>
        <column style="width:17px">
			<content applyFunction="jp_encrypt">555863/{*customerid*}||main16x16 icon2105</content>
		</column>
		<column style="width:17px;">
			<content applyFunction="jp_encrypt">555806/{*customerid*}||main16x16 icon534</content>
		</column>
        <column title="Customer#" style="width:100px;" sort="outsidecustomernumber">
			<content>{*outsidecustomernumber*}</content>
		</column>
		
        <column title="Name">
			<content>{*firstname*} {*lastname*}</content>
		</column>
		<column title="CC Number">
			<content>{*ccnumber*}</content>
		</column>
		<column title="Auth Code">
			<content>{*gw_authcode*}</content>
		</column>
		<column title="Address">
			<content>{*addressstreet1*}</content>
		</column>
		<column title="Zip">
			<content>{*addresspostcode*}</content>
		</column>
		<column title="Email" style="width:200px;" sort="email">
			<content>{*email*}</content>
		</column>
		<column title="Number">
			<content applyFunction="jp_formatPhone">{*phonenumber*}</content>
		</column>
		<column title="Added" sort="datecreated" style="width:90px;">
			<content applyFunction="jp_formatDate">{*datecreated*}</content>
		</column>
	</columns>

	<plugins>
		<plugin>
			<name>rowNumbers</name>
		</plugin>
		<plugin>
			<name>simpleStyleEffects</name><!-- effect can be : rollover, OddEvenRow -->
			<effect>rollover</effect>
		</plugin>
		<plugin>
			<name>flagger</name>
			<sql class="flagger">UPDATE (*CDB*).`4tbl_customers` SET `customerflagged` = IF (`customerflagged` > 0, 0, 1) WHERE `customerid` = **row_id** LIMIT 1</sql>
		</plugin>
		
	</plugins>
	
	<pagination>
		<pageTotal>20</pageTotal>
		<showFirstLink>1</showFirstLink>
		<showLastLink>1</showLastLink>
		<showPrevLink>1</showPrevLink>
		<showNextLink>1</showNextLink>
		<centerGroup>2</centerGroup>
		<leftGroup>3</leftGroup>
		<rightGroup>3</rightGroup>
		<linkFormat>pageNumber</linkFormat>
	</pagination>
	
	<text>
		<no_results>no results are present.</no_results>
		<showing>Showing:</showing>
		<of>of</of>
		<total_results> Total Results</total_results>
	</text>
	 
</jpaginate>