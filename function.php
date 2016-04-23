<?php

/**
* 
*/
class payroll
{
	public $con;
	
	function __construct()
	{
		try {
				$this->con = new PDO("mysql:host=localhost;dbname=payroll",'root','');
				$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $e) {
				echo "database connection error";
			}
	}

	function login($data){
		session_start();

		$user=$_POST['user'];
		$pass=$_POST['pass'];
		//if(!empty($user) && !empty($pass)){
			$sql="SELECT * FROM user";
			$result=$this->con->query($sql);
			foreach ($result as $row) {
				if($user==$row['user'] && $pass==$row['pass']){
				$_SESSION['success']=1;
				return 1;
				}else{
					$_SESSION['success']=0;
					return 0;
				}
			}
			
		}
	

	function insert($data){
		$fullname=$_POST['fullname'];
		$basicsalary=$_POST['basicsalary'];
		$houserent=($basicsalary*40)/100;
		$medical=1000;
		$providentfund=2000;
		$totalsalary=$basicsalary+$houserent+$medical-($providentfund);

		$sql="INSERT INTO employee (fullname,basicsalary,houserent,medical,providentfund,totalsalary)
				VALUES('$fullname','$basicsalary','$houserent','$medical','$providentfund','$totalsalary')";
		$result=$this->con->exec($sql);
		if($result){
			$_SESSION['msg']= '<p class="alert alert-success">Data insert Successfull</p>';
			return 1;
		}else{
			$_SESSION['msg']='<p class="alert alert-success">Sorry! Data insert unsuccessfull</p>';
			return 0;
		}

	}
        
        
        function view($id=''){
            if($id==''){
            $sql="SELECT * FROM employee";
            $result=$this->con->query($sql);
            
            foreach ($result as $row){
                $data[]=$row;
            }
            return $data;
        }
        if(!empty($id)){
            $sql="SELECT * FROM employee WHERE id='$id'";
            $result=$this->con->query($sql);
            
            foreach ($result as $row){
                $data[]=$row;
            }
        
            return $data;
            }
        }
        
        function delete($id){
            $sql= "DELETE FROM employee WHERE id='$id'";
            $result=$this->con->query($sql);
            return true;
       }
       
       function update($id,$data){
           
		$fullname=$_POST['fullname'];
		$basicsalary=$_POST['basicsalary'];
		$houserent=($basicsalary*40)/100;
		$medical=1000;
		$providentfund=2000;
		$totalsalary=$basicsalary+$houserent+$medical-($providentfund);
               $sql= "UPDATE employee SET "
                       . "fullname='$fullname',"
                       . "basicsalary='$basicsalary',"
                       . "houserent='$houserent',"
                       . "medical='$medical',"
                       . "providentfund='$providentfund',"
                       . "totalsalary='$totalsalary' "
                       . "WHERE id='$id'";
               $result=$this->con->exec($sql);
		if($result){
			$_SESSION['msg']= '<p class="alert alert-success">Data Update Successfull</p>';
			return 1;
		}else{
			$_SESSION['msg']='<p class="alert alert-success">Sorry! Data Update unsuccessfull</p>';
			return 0;
		}
       }
}



