<div class="row margin-top-20">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-10">
						<h2><?php echo $forum['title']; ?></h2>
					</div>
					<div class="col-md-2">
					<?php if($editable){ ?>
						<button class="btn btn-info btn-100">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp; Edit
						</button>
						<button class="btn btn-danger btn-100">
							<i class="fa fa-trash" aria-hidden="true"></i>&nbsp; Delete
						</button>
					<?php } ?>
					</div>
				</div>
				<div class="row margin-top">
					<div class="col-md-12">
						<table class="table margin-top-20">
							<tr>
								<td class="bold">Subject</td>
								<td><?php echo $forum['subject']; ?></td>
							</tr>
							<tr>
								<td class="bold">Description</td>
								<td><?php echo $forum['description']; ?></td>
							</tr>
							<tr>
								<td class="bold">Date Posted</td>
								<td><?php echo $forum['createdAt']; ?></td>
							</tr>
							<tr>
								<td class="bold">Last Update</td>
								<td><?php echo $forum['updatedAt']; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row margin-top">
					<div class="col-md-8">
						<h3>Conclusion</h3>
						<p id="Conclusion">
							
						</p>
					</div>
				</div>
				<div class="row margin-top">
					<div class="col-md-8">
						<!-- <h3>Polarity</h3> -->
						<p style="font-size: 25px" id="Polarity">
							
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row margin-top-20">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<h2>Dashboard</h2>
					</div>
				</div>
				<div class="row">
					<div id="dashboard" class="col-md-12">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<h2>Discussions</h2>

<script type="text/javascript">var corpus = "";</script>

<?php if (!empty($answers)){ foreach($answers as $answer) {?>
<script type="text/javascript">corpus +=" <?php echo $answer['answer']; ?>";</script>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-warning">
			<div class="panel-heading">
					<label>
						<?php echo $answer['firstName']." ".$answer['lastName']; ?>
					</label>
					<?php if($answer['editable']){ ?>
					<button class="btn btn-danger pull-right btn-sm">
						<i class="fa fa-trash" aria-hidden="true"></i>
					</button>
					<button style="margin-right: 5px" class="btn btn-info pull-right btn-sm">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</button>
					
					<?php } ?>
			</div>
			<div class="panel-body">
				<?php echo $answer['answer']; ?>
			</div>
			<div class="panel-heading">
				Date Posted: <?php echo $answer['createdAt']; ?>
			</div>
		</div>
	</div>
</div>

<?php  }} ?>

<?php if($loggedIn){ ?>

<div class="row margin-top-20">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Post your answer</h4>
			</div>
			<div class="panel-body">
				<form id="answerForm" method="POST">
					<div class="form-group">
						<textarea name="answer" class="form-control" placeholder="Post your answer..." rows="7"></textarea>
					</div>
					<div class="form-group">
						<input name="post" type="submit" class="btn btn-primary btn-100" value="Post"></input>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php } ?>

<script type="text/javascript">
	var Samuel = [];
	$.ajax("http://192.168.1.14:63342/samuel_init?KEY=YOUR_API_KEY", {
      	success: function(data) {
			Samuel = data;
			data = {
				'text':corpus,
				'summary_length':8,
				'visualize': true,
				'query': '<?php echo $forum['subject']; ?>',
				'KEY':Samuel.KEY,
				'verbose': true
			};
			$.ajax({
				url: "http://192.168.1.14:63342/samuel_api",
				type: 'POST',
				data: JSON.stringify(data),
				contentType:"application/json",
				success:function(samuel) {
				console.log(samuel);
				$("#Conclusion").text(samuel.summarized_text);
				$("#dashboard").html(samuel.dashboard);
				if (samuel.polarity=="positive") {
					$("#Polarity").html("<i class='fa fa-smile-o' aria-hidden='true'></i> &nbsp;"+samuel.percentage.final+" Positive")
					$("#Polarity").addClass("text text-success")
				}
				else if(samuel.polarity=="negative"){
					$("#Polarity").html("<i class='fa fa-frown-o' aria-hidden='true'></i> &nbsp;"+samuel.percentage.final+" Negative")
					$("#Polarity").addClass("text text-danger")
				}
				else{
					$("#Polarity").html("<i class='fa fa-meh-o' aria-hidden='true'></i> &nbsp;"+samuel.percentage.final+" Neutral")
					$("#Polarity").addClass("text text-primary")
				}
				}
			});
		}
   	});
	
</script>