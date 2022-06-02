<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 

    
</head>
<body>

<div class="d-flex flex-row flex-shrink-0" >
<?php 
include "conn.php";
include 'navbar.php'; 
?>



        <div class="container" style="width: 80%;">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Sales Report</h4>
                    </div>
                    <div class="card-body">
                    
                        <form method="GET"  action="pdf_DailyReport.php"  target="_blank">
                            <div class="row">
                                <div class="col-md-3" >
                                    <div>
                                        <input type="submit" name="submit" value="Daily" class="form-control" style="background-color:#7393B3"/>
                                    </div>
                                </div>    
                                <div class="col-md-3">
                                    <a href="pdf_WeeklyReport.php" style="text-decoration:none;" target="_blank">
                                        <div>
                                            <input type="button" name="submit" value=" Weekly" class="form-control" style="background-color:#7393B3"/>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="pdf_MonthlyReport.php" style="text-decoration:none;" target="_blank">
                                        <div>
                                            <input type="button" name="submit" value="Monthly" class="form-control" style="background-color:#7393B3"/>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="pdf_QuarterlyReport.php" style="text-decoration:none;" target="_blank">
                                        <div>
                                            <input type="button" name="submit" value="Quarterly" class="form-control" style="background-color:#7393B3"/> 
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    

                    
                    <div class="card-header">
                        <h5>Custom Date</h5>
                    </div>
                    <div class="card-body">
                        <!--
                        <form action="pdf_CustomReport.php" method="GET" target="_blank">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label></label> <br>
                                      <button type="submit" class="form-control" style="color:black; background-color:#7393B3" style="background-color:#7393B3">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form> -->
                        <form action="sales.php" method="GET">
                            <div class="row pb-3 mb-3">
                            <div class="col-md-3">        
                                <div class="form-group">          
                                <label>From </label>
                                <input type="date" name="from_date" id="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } else {
                                    echo '2022-01-01';
                                } ?>" class="form-control" onchange="transactionDate()">
                                </div>          
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <label>To </label>
                                <input type="date" name="to_date" id="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } else {echo date("Y-m-d");} ?>" class="form-control" onchange="transactionDate()" min="2022-01-01">
                                </div>
                            </div>
                            <div class="col-md-3">        
                                <div class="form-group">        
                                <label></label> <br>        
                                <button type="submit" class="form-control" style="color:black; background-color:#7393B3" style="background-color:#7393B3">Filter</button>
                                </div>               
                            </div>
                            <div class="col-md-3">        
                                <div class="form-group">        
                                <label></label> <br>        
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle form-control" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Download</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="export.php?exportTransactions=all#">Sales Today</a></li>
                                    <li><a class="dropdown-item" href="export.php?exportTransactions=range&from=<?php echo $from_date?>&to=<?php echo $to_date?>#" id=exportRange onclick="changeRange()">Sales in Date Range</a></li>
                                    </ul>
                                </div>
                                </div>               
                            </div>
                            </div>              
                        </form> 
                    </div>
                


                    

                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class= "container1">
                        <table class="table table-borderd">
                            <?php
                                
                               $sql = "select distinct item_NAME from item order by item_NAME";
                                $result = mysqli_query($conn, $sql);
                                if (isset($_GET['to_date'])) {
                                    $from_date = $_GET['to_date'];
                                    $to_date = $_GET['to_date'];
                                    //unset($_GET['to_date']);
                                  } else {
                                    $from_date = date("Y-m-d");
                                    $to_date = date("Y-m-d");
                                  }
                                
                                    
                            ?>
                            <thead>
                                <h5>
                                    Daily Sales<?php echo " (".$to_date.")"; ?>
                                    <div style="float:right; width:50%; padding-right:15px;">
                                        <form action="sales.php" method="GET">
                                            <div class="row pb-2 mb-2">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                <input type="date" name="to_date" id="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } else {echo date("Y-m-d");} ?>" class="form-control" onchange="transactionDate()" min="2022-01-01">
                                                </div>
                                            </div>
                                            <div class="col-md-4">        
                                                <div class="form-group">              
                                                <button type="submit" class="form-control" style="color:black; background-color:#7393B3" style="background-color:#7393B3">Filter</button>
                                                </div>               
                                            </div>
                                            </div>                             
                                        </form> 
                                    </div>
                                </h5>
                                <tr>
                                    <th>Order Date</th>
                                    <th>Order ID</th>
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Item Unit</th>
                                    <th>Item Brand</th>
                                    <th>Quantity</th>
                                    <th>Order Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 


                                    $sql = "SELECT item.item_ID, item.item_Name, item.item_unit, item.item_Brand, order_items.order_ID, order_items.orderItems_Quantity, order_items.orderItems_TotalPrice, orders.order_Date, orders.order_Total 
                                            FROM item 
                                            INNER JOIN order_items on order_items.item_ID = item.item_ID 
                                            INNER JOIN orders on orders.order_ID = order_items.order_ID 
                                            WHERE orders.order_Date BETWEEN '$from_date' AND '$to_date'";
                                    $result = mysqli_query($conn, $sql);

                                    if(mysqli_num_rows($result) > 0)
                                    {
                                        foreach($result as $row)
                                        {
                                            ?>
                                            <tr>
                                                <td><?= $row['order_Date']; ?></td>
                                                <td><?= $row['order_ID']; ?></td>
                                                <td><?= $row['item_ID']; ?></td>
                                                <td><?= $row['item_Name']; ?></td>
                                                <td><?= $row['item_unit']; ?></td>
                                                <td><?= $row['item_Brand']; ?></td>
                                                <td><?= $row['orderItems_Quantity']; ?></td>
                                                <td><?= $row['order_Total']; ?></td>
                                                
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "No Record Found"; 
                                        ?>
                                        </br>
                                        <?php
                                        //echo $to_date;
                                    }
                                
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
