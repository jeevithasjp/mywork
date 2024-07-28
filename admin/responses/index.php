
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Questions Responses</h3>
		<div class="card-tools">
			<a href="?page=pagckages/manage" class="btn btn-flat btn-info"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-bordered table-stripped" id="myTable">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="60%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Question</th>
						<th>Response</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT q.id, r.response_message, q.question FROM `questions` q inner join `responses` r on q.response_id = r.id order by q.question asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['question'] ?></td>
							<td><span class="truncate"><?php echo $row['response_message'] ?></span></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="?page=responses/manage&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
			<button onclick="printPage()" style="background-color:rgb(0,123,255); border:none; color:white; padding:10px">Print</button>

			<button onclick="printTable()" style="background-color:rgb(0,123,255); border:none;color:white; padding:10px">Print Only Data</button>
		</div>
	</div>
</div>
<style>
	@media print {
  /* Add custom print styles */
  table {
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid black;
    padding: 8px;
  }
  /* Add any additional print styles as needed */
}
	</style>

<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this data?","delete_question",[$(this).attr('data-id')])
		})
		$('.table').dataTable();
	})
	function delete_question($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_response",
			method:"POST",
			data:{id: $id},
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(resp == 1){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
	function printPage() {
  window.print();
}
	function printTable() {
  var table = document.getElementById("myTable");
  var printWindow = window.open('', '_blank');
  printWindow.document.write('<html><head><title>Print Table</title>');
  printWindow.document.write('<style>');
  printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
  printWindow.document.write('th, td { border: 1px solid #000; padding: 8px; }');
  printWindow.document.write('th { background-color: #f2f2f2; }');
  printWindow.document.write('td { text-align: center; }');
  printWindow.document.write('@media print {');
  printWindow.document.write('  table { page-break-inside: avoid; }');
  printWindow.document.write('}');
  printWindow.document.write('</style>');
  printWindow.document.write('</head><body>');
  printWindow.document.write('<table>' + table.innerHTML + '</table>');
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}

</script>