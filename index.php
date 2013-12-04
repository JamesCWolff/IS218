<?php

	//print_r(PDO::getAvailableDrivers());  
	
	$host = 'sql2.njit.edu';
	$dbname = 'jcw6';
	$user = 'jcw6';
	$pass = 'fennel93';
	
	$program = new program();
	
	class program
	{
		function __construct()
      	{
      		$page = 'homepage';
			$arg = NULL;
			if(isset($_REQUEST['page'])) {
  				$page = $_REQUEST['page'];
			}
			if(isset($_REQUEST['arg'])) {
  				$arg  = $_REQUEST['arg'];
			}
       
			//echo $page;
        	$page = new $page($arg);
		}

    
    	function __destruct()
		{
      	}
  	}
	abstract class page
	{
  		public $content;
		public $title = '';

		function menu() {
			//$session = new session();
			
			
			$menu .= '<a href="./index.php">Homepage</a> ';
			$menu .= '<a href="./index.php?page=highestEnrollment">Highest Enrollment</a> ';
			$menu .= '<a href="./index.php?page=highestLiabilities">Highest Liabilities</a> ';
			$menu .= '<a href="./index.php?page=highestAssets">Highest Assets</a> ';
			$menu .= '<a href="./index.php?page=highestRevenue">Highest Revenue</a> ';
			$menu .= '<a href="./index.php?page=highestRevenuePerStudent">Highest Revenue Per Student</a> ';
			$menu .= '<a href="./index.php?page=highestAssetsPerStudent">Highest Assets Per Student</a> ';
			$menu .= '<a href="./index.php?page=highestLiabilitiesPerStudent">Highest Liabilities Per Student</a> ';
			$menu .= '<a href="./index.php?page=highestIncreasedLiabilities">Highest Increase In Liabilities</a> ';
			$menu .= '<a href="./index.php?page=highestIncreasedEnrollment">Highest Increase In Enrollment</a> ';
			$menu .= '<a href="./index.php?page=searchState">Search By State</a> ';
			$menu .= '<a href="./index.php?page=infoTable&filter=Enrollment">Table</a> ';
			
			
			return $menu;
		}
		
		function title() {
			if($this->title != '')
				$page_title .= '<h1>' .$this->title .'</h1>';
			return $page_title;
		}

		function __construct($arg = NULL)
		{
			if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				$this->get();
			}
			else
			{
				$this->post();
			}
		}
		function get()
		{
       
		}
		function post()
		{
		}
		function __destruct() {

			echo $this->content;
		} 
		
		public function getQuery($STR){
			
			//$session = new session();
			
			//print_r('check2');
			
			$host = 'sql2.njit.edu';
			$dbname = 'jcw6';
			$user = 'jcw6';
			$pass = 'fennel93';
			
			try {  
		
				# MySQL with PDO_MYSQL
				$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
				  
				$STH = $DBH->query($STR);
				
				# setting the fetch mode  
				$STH->setFetchMode(PDO::FETCH_ASSOC);  


				return $this->buildTable($STH);
				
				
			}  
			catch(PDOException $e) {  
			    echo $e->getMessage();  
			}
		}
		
		
		
		public function buildTable($STH){
			
			$table = '<TABLE border="1">';

			$first = TRUE;

			while($row = $STH->fetch()) {
				
				if ($first)
				{
					$colNames = array();
					$table .= '<tr>';
					foreach($row as $col => $r)
					{
						array_push($colNames, $col);
						$table .= '<td>' . $col . '</td>';
					}
					$table .= '</tr>';
					
					$first = FALSE;
				}
				
				$table .= '<tr>';
				
				foreach($colNames as $cn)
				{
					$table .= '<td>' .$row[$cn] .'</td>';  
				}
				$table .= '</tr>';
				
			}
			$table .= '</TABLE>';
			
			return $table;
		}
		
 
	}
	class homepage extends page
  	{
		function get()
		{
			$this->title = 'Home';
			$this->content .= $this->menu();
			$this->content .= $this->title();
		}
  	}
	
	class highestEnrollment extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Enrollment';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, en10.EFYTOTLT + en11.EFYTOTLT as Enrollment 
					FROM hd left outer join (en10, en11) on (hd.UNITID = en10.UNITID and hd.UNITID = en11.UNITID) 
					order by Enrollment DESC 
					LIMIT 10;";
			
			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestLiabilities extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Liabilities';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, fa10.TOTLIB + fa11.TOTLIB as Liabilities 
					FROM hd, fa10, fa11 
					where hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID 
					order by Liabilities DESC 
					LIMIT 10;";
			
			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestAssets extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Assets';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, fa10.TOTNAS + fa11.TOTNAS as Assets 
			FROM hd, fa10, fa11 
			where hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID 
			order by Assets DESC 
			LIMIT 10;";
			
			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestRevenue extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Revenue';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, fa10.TOTREV + fa11.TOTREV as Revenue 
			FROM hd, fa10, fa11 
			where hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID 
			order by Revenue DESC 
			LIMIT 10;";

			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestRevenuePerStudent extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Revenue Per Student';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, (fa10.TOTREV/en10.EFYTOTLT) + (fa11.TOTREV/en11.EFYTOTLT) as RevenuePerStudent 
			FROM hd, fa10, fa11, en10, en11
			where hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID and hd.UNITID = en10.UNITID and hd.UNITID = en11.UNITID
			order by RevenuePerStudent DESC 
			LIMIT 10;";

			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestAssetsPerStudent extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Net Assets Per Student';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, (fa10.TOTNAS/en10.EFYTOTLT) + (fa11.TOTNAS/en11.EFYTOTLT) as AssetsPerStudent
			FROM hd, fa10, fa11, en10, en11
			where hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID and hd.UNITID = en10.UNITID and hd.UNITID = en11.UNITID
			order by AssetsPerStudent DESC 
			LIMIT 10;";

			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestLiabilitiesPerStudent extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Liabilities Per Student';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, (fa10.TOTLIB/en10.EFYTOTLT) + (fa11.TOTLIB/en11.EFYTOTLT) as LiabilitiesPerStudent 
			FROM hd, fa10, fa11, en10, en11
			where hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID and hd.UNITID = en10.UNITID and hd.UNITID = en11.UNITID
			order by LiabilitiesPerStudent DESC 
			LIMIT 10;";

			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestIncreasedLiabilities extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Precent Increase In Liabilities Between 2010 And 2011';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, fa10.TOTLIB as L2010, fa11.TOTLIB as L2011, (fa11.TOTLIB-fa10.TOTLIB/fa10.TOTLIB) * 100 as Liabilities 
			FROM hd, fa10, fa11
			where hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID
			order by Liabilities DESC 
			LIMIT 10;";

			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class highestIncreasedEnrollment extends page
  	{
		
		function get()
		{
			$this->title = 'Highest Precent Increase In Enrollment Between 2010 And 2011';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			$STR = "select hd.INSTNM as Institution, en10.EFYTOTLT as E2010, en11.EFYTOTLT as E2011, (en11.EFYTOTLT/en10.EFYTOTLT) * 100 as Enrollment 
			FROM hd, en10, en11
			where hd.UNITID = en10.UNITID and hd.UNITID = en11.UNITID
			order by Enrollment DESC 
			LIMIT 10;";

			$this->content .= $this->getQuery($STR);
		}
  	}
	
	class searchState extends page
  	{
		
		function get()
		{
			//print_r($_REQUEST['state']);
			
			$this->title = 'Search by state abbreviation';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			$this->content .= $this->registerForm();
			
			if (isset($_REQUEST['state'])){
				$STR = "select INSTNM as Institution, STABBR as State 
				FROM hd
				where STABBR = '" .$_REQUEST['state'] ."'"
				."order by Institution
				;";
				
				$this->content .= $this->getQuery($STR);
			}
			
		}
		
		public function registerForm() {
			
			$form .= '<form action="index.php?page=searchState" method="post">
				<P>
				<LABEL for="state">State: </LABEL>
				<INPUT type="text" name="state"><BR>
				<INPUT type="submit" value="Send">
				</P>
				</form>
				';
				
			return $form;
		}
		
		function post() {
			foreach($_POST as $state => $st)
			{
				header('Location: ./index.php?page=searchState&'.$state .'=' .$st);
				end;
			}
		}
  	}
	
	class infoTable extends page
  	{
		function get()
		{
			$this->title = 'Table View';
			$this->content .= $this->menu();
			$this->content .= $this->title();
			
			//$STR = "select * from hd limit 10";
			
			$STR = "select hd.INSTNM as Institution,
				en10.EFYTOTLT + en11.EFYTOTLT as Enrollment,
				fa10.TOTLIB + fa11.TOTLIB as Liabilities,
				fa10.TOTNAS + fa11.TOTNAS as Assets,
				fa10.TOTREV + fa11.TOTREV as Revenue ,
				(fa10.TOTNAS/en10.EFYTOTLT) + (fa11.TOTNAS/en11.EFYTOTLT) as AssetsPerStudent,
				(fa10.TOTREV/en10.EFYTOTLT) + (fa11.TOTREV/en11.EFYTOTLT) as RevenuePerStudent,
				(fa10.TOTLIB/en10.EFYTOTLT) + (fa11.TOTLIB/en11.EFYTOTLT) as LiabilitiesPerStudent
				FROM hd left outer join (en10, en11, fa10, fa11) on (hd.UNITID = en10.UNITID and hd.UNITID = en11.UNITID and hd.UNITID = fa10.UNITID and hd.UNITID = fa11.UNITID) 
				order by " .$_REQUEST['filter'] ." DESC 
				LIMIT 5;";
			
			
			//$this->content .= $this->getQuery($STR);
			
			$this->content .= $this->setQuery($STR);
			
			
			
		}
		
		public function setQuery($STR){
			
			$host = 'sql2.njit.edu';
			$dbname = 'jcw6';
			$user = 'jcw6';
			$pass = 'fennel93';
			
			try {  
		
				# MySQL with PDO_MYSQL
				$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
				  
				$STH = $DBH->query($STR);
				
				# setting the fetch mode  
				$STH->setFetchMode(PDO::FETCH_ASSOC);
				
				return $this->buildRevTable($STH);
				
				
			}  
			catch(PDOException $e) {  
			    echo $e->getMessage();  
			}
		}
		
		public function buildRevTable($STH)
		{
			$r=0;
			$c=0;
			
			while($row = $STH->fetch())
			{
				$r = 0;
				foreach($row as $col => $value)
				{
					if($c == 0)
					{
						$a[$r][$c] = $col;
					}
					
					$a[$r][$c+1] = $value;
					$r++;
				}
				$c++;
			}
			
			$table = '<TABLE border="1">';
			
			for($y = 0; $y<$r;$y++)
			{
				$table .= '<tr>';
				
				for($x = 0; $x<$c+1;$x++)
				{
					if($x == 0 && $y != 0)
					{
						$f = $a[$y][$x];
						$table .= "<td> <a href=./index.php?page=infoTable&filter=$f>" . $a[$y][$x] . "</a></td>";
					}
					else
					$table .= '<td>' . $a[$y][$x] . '</td>';
				}
				$table .= '</tr>';
			}
			$table .= '</TABLE">';
			
			return $table;
		}
  	}
	
?>