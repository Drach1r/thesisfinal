<?php
include 'header.php';
include 'sidebar.php';
?>

   
  
    <article class="content dashboard-page">
      <section class="section">
    <div class="row sameheight-container">
        <div class="col col-xs-12 col-sm-12 col-md-6 col-xl-5 stats-col">
            <div class="card sameheight-item stats" data-exclude="xs">
            	<div class="card-block">
            
            		<div class="title-block">
            			<h4 class="title">
            				Stats
            			</h4>
    
            		</div>
            
            		<div class="row row-sm stats-container">
            			<div class="col-xs-12 col-sm-6 stat-col">
            				<div class="stat-icon">
            					<i class="fa fa-rocket"></i>
            				</div>
            				<div class="stat">
            					<div class="value">
            						1590
            					</div>
            					<div class="name">
            						Daily Milk
            					</div>
            				</div>
            				<progress class="progress stat-progress" value="75" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 75%;"></span>
            					</div>
            				</progress>
            			</div>
            
            			<div class="col-xs-12 col-sm-6 stat-col">
            				<div class="stat-icon">
            					<i class="fa fa-shopping-cart"></i>
            				</div>
            				<div class="stat">
            					<div class="value">
            						2000
            					</div>
            					<div class="name">
            						Products sold
            					</div>
            				</div>
            				<progress class="progress stat-progress" value="25" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 25%;"></span>
            					</div>
            				</progress>
            			</div>
            
            			<div class="col-xs-12 col-sm-6  stat-col">
            				<div class="stat-icon">
            					<i class="fa fa-line-chart"></i>
            				</div>
            				<div class="stat">
            					<div class="value">
            						₱60,000
            					</div>
            					<div class="name">
            						Monthly income
            					</div>
            				</div>
            				<progress class="progress stat-progress" value="60" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 60%;"></span>
            					</div>
            				</progress>
            			</div>
            
            			
            
            			<div class="col-xs-12 col-sm-6  stat-col">
            				<div class="stat-icon">
            					<i class="fa fa-users"></i>
            				</div>
            				<div class="stat">
            					<div class="value">
            						4
            					</div>
            					<div class="name">
            						Total users
            					</div>
            				</div>=
            				<progress class="progress stat-progress" value="34" max="100">
            					<div class="progress">
            						<span class="progress-bar" style"width: 34%;"></span>
            					</div>
            				</progress>
            			</div>
            
            
            			<div class="col-xs-12 col-sm-6  stat-col">
            				<div class="stat-icon">
            					<i class="fa fa-list-alt"></i>
            				</div>
            				<div class="stat">
            					<div class="value">
            						59
            					</div>
            					<div class="name">
            						Orders closed
            					</div>
            				</div>
            				<progress class="progress stat-progress" value="49" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 49%;"></span>
            					</div>
            				</progress>
            			</div>
            
            			<div class="col-xs-12 col-sm-6 stat-col">
            				<div class="stat-icon">
            					<i class="fa fa-money"></i>
            				</div>
            				<div class="stat">
            					<div class="value">
            						₱120,000
            					</div>
            					<div class="name">
            						Total income
            					</div>
            				</div>
            				<progress class="progress stat-progress" value="15" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 15%;"></span>
            					</div>
            				</progress>
            			</div>
            
            
            		</div>
            
            	</div>
            </div>        </div>
        <div class="col col-xs-12 col-sm-12 col-md-6 col-xl-7 history-col">
            <div class="card sameheight-item" data-exclude="xs">
            	<div class="card-header card-header-sm bordered">
            		<div class="header-block">
            			<h3 class="title">Sales Forecast</h3>
            		</div>
                    <ul class="nav nav-tabs pull-right" role="tablist">
            			<li class="nav-item">
            				<a class="nav-link active" href="#visits" role="tab" data-toggle="tab">Visits</a>
            			</li>
            			<li class="nav-item">
            				<a class="nav-link" href="#downloads" role="tab" data-toggle="tab">Downloads</a>
            			</li>
            		</ul>
                </div>
            	<div class="card-block">
            		
            		<div class="tab-content">
            			<div role="tabpanel" class="tab-pane active fade in" id="visits">
            				<p class="title-description">
            					Sales Results last 30 days
            				</p>
            
            				<div id="dashboard-visits-chart"></div>
            			</div>
            			<div role="tabpanel" class="tab-pane fade" id="downloads">
            				<p class="title-description">
            					Number of downloads in the last 30 days
            				</p>
            
            				<div id="dashboard-downloads-chart"></div>
            			</div>
            		</div>
            	</div>
            </div>        </div>
    </div>
</section>

<section class="section">
    <div class="row sameheight-container">

        <div class="col-xl-8">
            <div class="card sameheight-item items" data-exclude="xs,sm,lg">
            	<div class="card-header bordered">
            		<div class="header-block">
            			<h3 class="title">
            				Items
            			</h3>
            			<a href="item-editor.html" class="btn btn-primary btn-sm rounded">
            				Add new
            			</a>
            		</div>
            		<div class="header-block pull-right">
            			<label class="search">
            				<input class="search-input" placeholder="search...">
            				<i class="fa fa-search search-icon"></i>
            			</label>
            			<div class="pagination">
            				<a href="" class="btn btn-primary btn-sm rounded">
            					<i class="fa fa-angle-up"></i>
            				</a>
            				<a href="" class="btn btn-primary btn-sm rounded">
            					<i class="fa fa-angle-down"></i>
            				</a>
            			</div>
            		</div>
            	</div>
            	<ul class="item-list striped">
					<li class="item item-list-header hidden-sm-down">
						<div class="item-row">
							<div class="item-col fixed item-col-check">
								<label class="item-check" id="select-all-items">
									<input type="checkbox" class="checkbox">
									<span></span>
								</label>
							</div>
							<div class="item-col item-col-header fixed item-col-img md">
								<div>
									<span>Image</span>
								</div>
							</div>
							<div class="item-col item-col-header item-col-title">
								<div>
									<span>Name</span>
								</div>
							</div>
							<div class="item-col item-col-header item-col-sales">
								<div>
									<span>Price</span>
								</div>
							</div>
							<div class="item-col item-col-header item-col-stats">
								<div class="no-overflow">
									<span>Quantity</span>
								</div>
							</div>
							<div class="item-col item-col-header item-col-category">
								<div class="no-overflow">
									<span>Description</span>
								</div>
							</div>
							<div class="item-col item-col-header item-col-author">
								<div class="no-overflow">
									<span>Status</span>
								</div>
							</div>
							<div class="item-col item-col-header item-col-date">
								<div>
									<span>Produced On</span>
								</div>
							</div>
							<div class="item-col item-col-header fixed item-col-actions-dropdown">
			
							</div>
						</div>
					</li>
						<li class="item">	
			
							<div class="item-row">
			
								<div class="item-col fixed item-col-check">
			
									<label class="item-check" id="select-all-items">
										<input type="checkbox" class="checkbox">
										<span></span>
									</label>
								</div>
			
								<div class="item-col fixed item-col-img md">
									<a href="item-editor.html">
										<div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
									</a>
								</div>
								<div class="item-col fixed pull-left item-col-title">
									<div class="item-heading">Name</div>
									<div>
										<a href="item-editor.html" class="">
											<h4 class="item-title">
												Paneer
											</h4>
										</a>
									</div>
								</div>
								<div class="item-col item-col-sales">
									<div class="item-heading">Sales</div>
									<div>
										100
									</div>
								</div>
								<div class="item-col item-col-stats no-overflow">
									<div class="item-heading">Sales</div>
									<div>
										200
									</div>
								</div>
								<div class="item-col item-col-category no-overflow">
									<div class="item-heading">Category</div>
									<div class="no-overflow">
										<a href="">An indian Cheese</a>
									</div>
								</div>
								<div class="item-col item-col-author">
									<div class="item-heading">Status</div>
									<div class="no-overflow">
										<a href="">Active</a>
									</div>
								</div>
								<div class="item-col item-col-date">
									<div class="item-heading">Published</div>
									<div>
										21 SEP 10:45
									</div>
								</div>
								<div class="item-col fixed item-col-actions-dropdown">
									<div class="item-actions-dropdown">
										<a class="item-actions-toggle-btn">
											<span class="inactive">
												<i class="fa fa-cog"></i>
											</span>
											<span class="active">
											<i class="fa fa-chevron-circle-right"></i>
											</span>
										</a>
										<div class="item-actions-block">
											<ul class="item-actions-list">
												<li>
													<a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
														<i class="fa fa-trash-o "></i>
													</a>
												</li>
												<li>
													<a class="edit" href="item-editor.html">
														<i class="fa fa-pencil"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
			
							</div>
						</li>
						<li class="item">	
			
							<div class="item-row">
			
								<div class="item-col fixed item-col-check">
			
									<label class="item-check" id="select-all-items">
										<input type="checkbox" class="checkbox">
										<span></span>
									</label>
								</div>
			
								<div class="item-col fixed item-col-img md">
									<a href="item-editor.html">
										<div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
									</a>
								</div>
								<div class="item-col fixed pull-left item-col-title">
									<div class="item-heading">Name</div>
									<div>
										<a href="item-editor.html" class="">
											<h4 class="item-title">
												Karabun
											</h4>
										</a>
									</div>
								</div>
								<div class="item-col item-col-sales">
									<div class="item-heading">Sales</div>
									<div>
										10
									</div>
								</div>
								<div class="item-col item-col-stats no-overflow">
									<div class="item-heading">Sales</div>
									<div>
										100
									</div>
								</div>
								<div class="item-col item-col-category no-overflow">
									<div class="item-heading">Category</div>
									<div class="no-overflow">
										<a href="">Bread Made From Carabao Milk</a>
									</div>
								</div>
								<div class="item-col item-col-author">
									<div class="item-heading">Status</div>
									<div class="no-overflow">
										<a href="">Active</a>
									</div>
								</div>
								<div class="item-col item-col-date">
									<div class="item-heading">Published</div>
									<div>
										5 April 01:00
									</div>
								</div>
								<div class="item-col fixed item-col-actions-dropdown">
									<div class="item-actions-dropdown">
										<a class="item-actions-toggle-btn">
											<span class="inactive">
												<i class="fa fa-cog"></i>
											</span>
											<span class="active">
											<i class="fa fa-chevron-circle-right"></i>
											</span>
										</a>
										<div class="item-actions-block">
											<ul class="item-actions-list">
												<li>
													<a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
														<i class="fa fa-trash-o "></i>
													</a>
												</li>
												<li>
													<a class="edit" href="item-editor.html">
														<i class="fa fa-pencil"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
			
							</div>
						</li>
						<li class="item">	
			
							<div class="item-row">
			
								<div class="item-col fixed item-col-check">
			
									<label class="item-check" id="select-all-items">
										<input type="checkbox" class="checkbox">
										<span></span>
									</label>
								</div>
			
								<div class="item-col fixed item-col-img md">
									<a href="item-editor.html">
										<div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
									</a>
								</div>
								<div class="item-col fixed pull-left item-col-title">
									<div class="item-heading">Name</div>
									<div>
										<a href="item-editor.html" class="">
											<h4 class="item-title">
												Pastillas
											</h4>
										</a>
									</div>
								</div>
								<div class="item-col item-col-sales">
									<div class="item-heading">Sales</div>
									<div>
									50-150
									</div>
								</div>
								<div class="item-col item-col-stats no-overflow">
									<div class="item-heading">Sales</div>
									<div>
										60
									</div>
								</div>
								<div class="item-col item-col-category no-overflow">
									<div class="item-heading">Category</div>
									<div class="no-overflow">
										<a href="">A pastillas made frome carabao milk</a>
									</div>
								</div>
								<div class="item-col item-col-author">
									<div class="item-heading">Status</div>
									<div class="no-overflow">
										<a href="">Active</a>
									</div>
								</div>
								<div class="item-col item-col-date">
									<div class="item-heading">Published</div>
									<div>
										20 MAY 12:30
									</div>
								</div>
								<div class="item-col fixed item-col-actions-dropdown">
									<div class="item-actions-dropdown">
										<a class="item-actions-toggle-btn">
											<span class="inactive">
												<i class="fa fa-cog"></i>
											</span>
											<span class="active">
											<i class="fa fa-chevron-circle-right"></i>
											</span>
										</a>
										<div class="item-actions-block">
											<ul class="item-actions-list">
												<li>
													<a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
														<i class="fa fa-trash-o "></i>
													</a>
												</li>
												<li>
													<a class="edit" href="item-editor.html">
														<i class="fa fa-pencil"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
			
							</div>
						</li>
						<li class="item">	
			
							<div class="item-row">
			
								<div class="item-col fixed item-col-check">
			
									<label class="item-check" id="select-all-items">
										<input type="checkbox" class="checkbox">
										<span></span>
									</label>
								</div>
			
								<div class="item-col fixed item-col-img md">
									<a href="item-editor.html">
										<div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
									</a>
								</div>
								<div class="item-col fixed pull-left item-col-title">
									<div class="item-heading">Name</div>
									<div>
										<a href="item-editor.html" class="">
											<h4 class="item-title">
												White Cheese
											</h4>
										</a>
									</div>
								</div>
								<div class="item-col item-col-sales">
									<div class="item-heading">Sales</div>
									<div>
										150
									</div>
								</div>
								<div class="item-col item-col-stats no-overflow">
									<div class="item-heading">Sales</div>
									<div>
										30
									</div>
								</div>
								<div class="item-col item-col-category no-overflow">
									<div class="item-heading">Category</div>
									<div class="no-overflow">
										<a href="">White cheese </a>
									</div>
								</div>
								<div class="item-col item-col-author">
									<div class="item-heading">Status</div>
									<div class="no-overflow">
										<a href="">Active</a>
									</div>
								</div>
								<div class="item-col item-col-date">
									<div class="item-heading">Published</div>
									<div>
										30 MAY 02:00
									</div>
								</div>
								<div class="item-col fixed item-col-actions-dropdown">
									<div class="item-actions-dropdown">
										<a class="item-actions-toggle-btn">
											<span class="inactive">
												<i class="fa fa-cog"></i>
											</span>
											<span class="active">
											<i class="fa fa-chevron-circle-right"></i>
											</span>
										</a>
										<div class="item-actions-block">
											<ul class="item-actions-list">
												<li>
													<a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
														<i class="fa fa-trash-o "></i>
													</a>
												</li>
												<li>
													<a class="edit" href="item-editor.html">
														<i class="fa fa-pencil"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
			
							</div>
						</li>
						<li class="item">	
			
							<div class="item-row">
			
								<div class="item-col fixed item-col-check">
			
									<label class="item-check" id="select-all-items">
										<input type="checkbox" class="checkbox">
										<span></span>
									</label>
								</div>
			
								<div class="item-col fixed item-col-img md">
									<a href="item-editor.html">
										<div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
									</a>
								</div>
								<div class="item-col fixed pull-left item-col-title">
									<div class="item-heading">Name</div>
									<div>
										<a href="item-editor.html" class="">
											<h4 class="item-title">
												Flavoured Milk
											</h4>
										</a>
									</div>
								</div>
								<div class="item-col item-col-sales">
									<div class="item-heading">Sales</div>
									<div>
										22
									</div>
								</div>
								<div class="item-col item-col-stats no-overflow">
									<div class="item-heading">Sales</div>
									<div>
										1000
									</div>
								</div>
								<div class="item-col item-col-category no-overflow">
									<div class="item-heading">Category</div>
									<div class="no-overflow">
										<a href="">Flavoured Milk made from Carabao milk with different flavours</a>
									</div>
								</div>
								<div class="item-col item-col-author">
									<div class="item-heading">Status</div>
									<div class="no-overflow">
										<a href="">Active</a>
									</div>
								</div>
								<div class="item-col item-col-date">
									<div class="item-heading">Published</div>
									<div>
										24 FEB 3:00
									</div>
								</div>
								<div class="item-col fixed item-col-actions-dropdown">
									<div class="item-actions-dropdown">
										<a class="item-actions-toggle-btn">
											<span class="inactive">
												<i class="fa fa-cog"></i>
											</span>
											<span class="active">
											<i class="fa fa-chevron-circle-right"></i>
											</span>
										</a>
										<div class="item-actions-block">
											<ul class="item-actions-list">
												<li>
													<a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
														<i class="fa fa-trash-o "></i>
													</a>
												</li>
												<li>
													<a class="edit" href="item-editor.html">
														<i class="fa fa-pencil"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
			
							</div>
						</li>
						<li class="item">	
			
							<div class="item-row">
			
								<div class="item-col fixed item-col-check">
			
									<label class="item-check" id="select-all-items">
										<input type="checkbox" class="checkbox">
										<span></span>
									</label>
								</div>
			
								<div class="item-col fixed item-col-img md">
									<a href="item-editor.html">
										<div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
									</a>
								</div>
								<div class="item-col fixed pull-left item-col-title">
									<div class="item-heading">Name</div>
									<div>
										<a href="item-editor.html" class="">
											<h4 class="item-title">
												Pasteurized Milk
											</h4>
										</a>
									</div>
								</div>
								<div class="item-col item-col-sales">
									<div class="item-heading">Sales</div>
									<div>
										120
									</div>
								</div>
								<div class="item-col item-col-stats no-overflow">
									<div class="item-heading">Sales</div>
									<div>
										200
									</div>
								</div>
								<div class="item-col item-col-category no-overflow">
									<div class="item-heading">Category</div>
									<div class="no-overflow">
										<a href="">Processed Milk</a>
									</div>
								</div>
								<div class="item-col item-col-author">
									<div class="item-heading">Status</div>
									<div class="no-overflow">
										<a href="">Inactive</a>
									</div>
								</div>
								<div class="item-col item-col-date">
									<div class="item-heading">Published</div>
									<div>
										21 March 8:30
									</div>
								</div>
								<div class="item-col fixed item-col-actions-dropdown">
									<div class="item-actions-dropdown">
										<a class="item-actions-toggle-btn">
											<span class="inactive">
												<i class="fa fa-cog"></i>
											</span>
											<span class="active">
											<i class="fa fa-chevron-circle-right"></i>
											</span>
										</a>
										<div class="item-actions-block">
											<ul class="item-actions-list">
												<li>
													<a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
														<i class="fa fa-trash-o "></i>
													</a>
												</li>
												<li>
													<a class="edit" href="item-editor.html">
														<i class="fa fa-pencil"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
			
							</div>
						</li>
				</ul>
			</div>        </div>

        <div class="col-xl-4">
             <div class="card sameheight-item sales-breakdown" data-exclude="xs,sm,lg">
             	<div class="card-header">
             		<div class="header-block">
             			<h3 class="title">
             				Sales breakdown
             			</h3>
             		</div>
             	</div>
                 <div class="card-block">
                     <div class="dashboard-sales-breakdown-chart" id="dashboard-sales-breakdown-chart"></div>
                 </div>
             </div>        </div>
    </div>
</section>

	</body>
</html>