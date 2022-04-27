<?php
require('fpdf.php');

class PDF extends FPDF{
    function Header(){
        $this->Image('vsjm.png',40,6,30);
        $this->SetFont('Arial','B',20);
        $this->Cell(80);
        $this->Cell(30,10,'VSJM Merchandising',50,0,'C');
        $this->SetFont('Arial','B',15);
        $this->Ln(10);
        $this->Cell(190,10,'Sales Report',50,0,'C');
        $this->Line(10,40,199,40);
        $this->Ln(30);
    }
}

include "conn.php";
$from_date = date("Y-m-d", strtotime("monday this week"));
$to_date = date("Y-m-d", strtotime("sunday this week"));
    
    $sql = "SELECT item.item_ID, item.item_Name, item.item_unit, item.item_Brand, order_items.order_ID, order_items.orderItems_Quantity, order_items.orderItems_TotalPrice, orders.order_Date, orders.order_Total 
            FROM item 
            INNER JOIN order_items on order_items.item_ID = item.item_ID 
            INNER JOIN orders on orders.order_ID = order_items.order_ID 
            WHERE orders.order_Date BETWEEN '$from_date' AND '$to_date'";                                   
    $result = mysqli_query($conn, $sql);
    
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',8);

        if(mysqli_num_rows($result) > 0)
        {
            $sql1 = "SELECT DISTINCT order_ID FROM order_items";
            $result1 = mysqli_query($conn, $sql1);
            $current = '';
            $previous = '';

            foreach($result1 as $row)
            {
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(0,8,"Order ID:".$row['order_ID'],1,0,'C');
                $pdf->Ln(8);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(30,10,'Order Date',1,0);
                $pdf->Cell(20,10,'Item ID',1,0);
                $pdf->Cell(45,10,'Item Name',1,0);
                $pdf->Cell(25,10,'Item Unit',1,0);
                $pdf->Cell(30,10,'Item Brand',1,0);
                $pdf->Cell(20,10,'Quantity',1,0);
                $pdf->Cell(20,10,'Order Total',1,1);
                $y = $pdf->GetY();

                $result2 = mysqli_query($conn, $sql);
                $current = $row['order_ID'];

                foreach($result2 as $row)
                {
                    $previous = $row['order_ID'];
                    if($current == $previous)
                    {
                        $pdf->SetFont('Arial','',8);
                        $y= $pdf ->GetY();
                        $pdf->MultiCell(30,8,$row['order_Date'],1,'L');
                        $y1=$pdf ->GetY();
                        $pdf ->SetY($y);
                        $pdf ->Cell(30,5,'');$pdf->Cell(20,8,$row['item_ID'],1,0);
                        $pdf->Cell(45,8,$row['item_Name'],1,0);
                        $pdf->Cell(25,8,$row['item_unit'],1,0);
                        $pdf->Cell(30,8,$row['item_Brand'],1,0);
                        $pdf->Cell(20,8,$row['orderItems_Quantity'],1,0);
                        $pdf->Cell(20,8,$row['order_Total'],1,1);   
                    }
                }
                $pdf ->SetY($y1+6);
            }
             
        }
        else{
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,8,"No Record Found",0,'C');
        }

$pdf->Output();
?>