<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN STYLE CUSTOMIZER-->
						<div class="styler-panel hidden-phone">
							<i class="icon-cog"></i>
							<i class="icon-remove"></i>
							<span class="settings">
							<span class="text">Style:</span>
							<span class="colors">
							<span class="color-default" data-style="default"></span>
							<span class="color-blue" data-style="blue"></span>
							<span class="color-light" data-style="light"></span>		
							</span>
							<span class="layout">
							<label class="hidden-phone">
							<input type="checkbox" class="header" checked="" value="" />Fixed Header
							</label>							
							</span>
							</span>
						</div>
						<!-- END STYLE CUSTOMIZER-->    	
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->		
						<h3 class="page-title">
							Dashboard
							<small>statistics and more</small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.html">Home</a> 
								<span class="icon-angle-right"></span>
							</li>
							<li><a href="#">Dashboard</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
					<div class="row-fluid">
						<div class="span3 responsive" data-tablet="span6" data-desktop="span3">
							<div class="dashboard-stat blue">
								<div class="visual">
									<i class="icon-comments"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php echo $current_campaign_detail['Campaign']['total_target'];?>
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
									<div class="number">549</div>
									
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
									<div class="number"><?php echo $achieved_total;?></div>
									
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
									<div class="number"><?php echo $achieved_percentage.'%';?></div>
									
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
									<div class="number">123</div>
									
								</div>
								<a class="more" href="#">
								Required Rate
								</a>						
							</div>
						</div>
					</div>
					<!-- END DASHBOARD STATS -->
					<div class="clearfix"></div>
					
					<div class="row-fluid">
						<div class="span12">
						<!-- BEGAIN MAIN CONTENT-->
						
						<div class="portlet box red">
							<div class="portlet-title">
								<h4><i class="icon-info-sign"></i>For Further Information</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="reload"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<div style="height:75px;">
								
								<form class="form-horizontal" name="search" method="post" action="report.html" id="">
									<div style="width:20%; margin-left:50px;">
										<label>Dispatch Details</label>
									</div>
							
									<div style="width:100%; margin-top:10px; margin-left:50px;">
										<select name="region" id="region" style="width:23%;float:left;">									
										 <option value="">All Region</option>
										 <option value="">Dhaka</option>
										 <option value="">Chitagong</option>
										 <option value="">Shylet</option>
										 <option value="">Khulna</option>
										 <option value="">Rajshahi</option>
										 <option value="">Barishal</option>
										 <option value="">Rangpur</option>
											
										</select>
										<select name="area" id="area" style="width:23%;float:left; margin-left:15px;">									
										<option value="">All Area</option>
										</select>
										<select name="house" id="house" style="width:23%;float:left; margin-left:15px; margin-right:20px;">									
										<option value="">All House</option>
										</select>
										
										<input type="submit" value="Submit" class="btn green" style="margin-top:-2px;"/>
										<input type="reset" value="Reset" class="btn red" style="margin-top:-2px;"/>
									</div>
								</form>
								
								
								</div>
								</div>
							</div>
						</div>
						
							<!-- BEGIN STACK CHART CONTROLS PORTLET-->
						<div class="portlet box yellow">
							<div class="portlet-title">
								<h4><i class="icon-reorder"></i>Till Date (%) By Region</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="reload"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
							
								<div id="chart_div" style="width: 950px; height: 300px;"></div>

							</div>
						</div>
						<!-- END STACK CHART CONTROLS PORTLET-->
							<!-- END MAIN CONTENT-->
						</div>
					</div>
					
					
				</div>