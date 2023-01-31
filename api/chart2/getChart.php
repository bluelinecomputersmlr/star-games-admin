<?php
include "../../game/closed/connection/config.php";
extract($_REQUEST);

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $market; ?> Chart</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td {
  text-align: center;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
</head>
<body>

<div style="overflow-x:auto;">
  <table>
    <tr>
      <th>Date</th>
      <th>Open</th>
      <th>Jodi</th>
      <th>Close</th>
    
    </tr>
    
    <?php
$results = query("select * from manual_market_results where market='$market' ORDER BY STR_TO_DATE(date, '%d/%m/%Y')");


while($dd = fetch($results)) {
?>

    <tr>
      <td><?php echo $dd['date']; ?></td>
      <td><?php echo $dd['open_panna']; ?></td>
      <td><?php echo $dd['open']; ?><?php echo $dd['close']; ?></td>
      <td><?php echo $dd['close_panna']; ?></td>
    </tr>
    
    <?php } ?>
   
  </table>
</div>

</body>
</html>

