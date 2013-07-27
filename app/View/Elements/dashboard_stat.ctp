<!-- BEGIN DASHBOARD STATS -->
					<div class="row-fluid">
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-comments"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php echo $achievements['total_allocation'];?>
									</div>
									
								</div>
								<a class="more" href="#">
								Total Allocation
								</a>						
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat gold">
								<div class="visual">
									<i class="icon-shopping-cart"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo $achievements['target_till_date'];?></div>
									
								</div>
								<a class="more" href="#">
								Target Till Date
								</a>						
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6  fix-offset" data-desktop="span3">
							<div class="dashboard-stat purple">
								<div class="visual">
									<i class="icon-globe"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo $achievements['achieved_total'];?></div>
									
								</div>
								<a class="more" href="#">
								Achieved Till Date
								</a>						
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat green">
								<div class="visual">
									<i class="icon-shopping-cart"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo $achievements['achievement_parcentage'].'%';?></div>
									
								</div>
								<a class="more" href="#">
								Achievement Percentage
								</a>						
							</div>
						</div>
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat yellow">
								<div class="visual">
									<i class="icon-bar-chart"></i>
								</div>
								<div class="details">
									<div class="number"><?php echo $achievements['required_rate'];?></div>
									
								</div>
								<a class="more" href="#">
								Required Rate
								</a>						
							</div>
						</div>
					</div>
					<!-- END DASHBOARD STATS -->