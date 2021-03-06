<?php
include_once '../../env/conn.php';
$n=0;

  $sql = "SELECT * FROM supplier_transactions WHERE transaction_Status !=0 ;";   
  $result = mysqli_query($conn,$sql);
  $resultCheck = mysqli_num_rows($result);
?>
<!DOCTYPE html><html class=''>
<head>
<link rel="stylesheet" href="./style.css?ts=<?=time()?>">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<style>

.panel-heading .colpsible-panel:after {
    
    font-family: 'Glyphicons Halflings'; 
    content: "\e114";    
    float: right;        
    color: #408080;         
}
.panel-heading .colpsible-panel.collapsed:after {
    content: "\e080"; 
}


</style>
</head>
<body>
  
  <div id="content">
    <h1> Transactions </h1>
    <p> Completed and undelivered transactions are shown here</p>

    <div class = "container">

      
      <div class="panel-group" id="accordion">

      <?php  
          if ($resultCheck>0){
              while ($row = mysqli_fetch_assoc($result)) {
                $n++;
                $ID = $row['transaction_ID'];  
                $supplier = $row['supplier_ID'];
                $transacDate = $row['transaction_Date'];   
                $total = $row['transaction_TotalPrice'];
                $status = $row['transaction_Status'];
                if ($status == 1) {
                  $status = 'undelivered';
                  echo "<div class='panel panel-info'>";
              } else{
                  $status = 'completed';
                  echo "<div class='panel panel-success'>";
              }
        
        ?>

      <!--<div class="panel panel-info">-->
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="colpsible-panel" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $n?>">
              <?php echo "<h4> Transaction ID: ".$ID. "</h4>"; ?> 
            </a>
          </h4>
        </div>
        <div id="collapse<?php echo $n?>" class="panel-collapse collapse">
          <div class="panel-body">
          <?php echo "<table class='table'> 
                            <tr> 
                                <th> ID </th>
                                <th> Item </th>
                                <th> Brand </th>
                                <th> unit </th>
                                <th> Quantity </th>
                                <th> Unit Price </th>
                                <th> Total Price </th>
                            </tr>";

                    $sql1 = "SELECT * FROM transaction_Items INNER JOIN item ON (transaction_Items.item_ID = item.item_ID) WHERE transaction_ID = '$ID' ;";   
                    $result1 = mysqli_query($conn,$sql1);
                    $resultCheck1 = mysqli_num_rows($result1);
                    
                    if ($resultCheck1>0){
                      while ($row1 = mysqli_fetch_assoc($result1)) {
                        echo "<tr>"; 
                        echo "<td>" .$row1['item_ID']. "</td>";  
                        echo "<td>". $row1['item_Name']. "</td>";  
                        echo "<td>" .$row1['item_Brand']. "</td>";  
                        echo "<td>" . $row1['item_unit'] . "</td>";  
                        echo "<td>" . $row1['transactionItems_Quantity']. "</td>"; 
                        echo "<td>" .$row1['transactionItems_CostPrice']. "</td>";
                        echo "<td>" .$row1['transactionItems_TotalPrice']. "</td>";       
                        echo "</tr>"; 

                      }
                    } 

                    echo "</table>"; ?>
          </div>
        </div>

        
      </div>
      <?php } 
      }?>
    </div><!-- end accordion -->
  </div> <!-- end container -->

</div> <!-- END CONTENT -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</body>
</html>