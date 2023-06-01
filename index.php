<?php


	if ( isset( $_REQUEST["template_id"] ) ) {
		require_once( "classes/constants.php" );	
		
		$template_id = iS::rn( "template_id" );
		$template = iDB::row_assoc( "SELECT * FROM templates WHERE id={$template_id}" );
		$text = $template["text"];
		
		$file_url = "temp/" . time() . ".html";
		
		foreach ( $_REQUEST["fields"] as $name => $value ) {
			$text = str_ireplace('<<' . $name . '>>', $value, $text);
		};
		// file_put_contents( $file_url, $text );


		require_once("classes/HtmlToDoc.class.php");
		$htd = new HTML_TO_DOC();
		//$htmlContent = file_get_contents("1.html");
		$htd->createDoc($text, time(), 1);
		
		
		/*
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
		readfile($file_url);		
		
		//var_dump( $_REQUEST );
		exit();
		*/
	};

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Global stylesheets -->
	<link href="/assets/fonts/inter/inter.css" rel="stylesheet" type="text/css">
	<link href="/assets/icons/phosphor/styles.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/ltr/all.min.css" id="stylesheet" rel="stylesheet" type="text/css">
	<link href="/assets/css/ltr/styles.css" id="stylesheet" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="/assets/js/jquery/jquery.min.js"></script>
	<!-- <script src="../../../assets/js/vendor/forms/selects/select2.min.js"></script> -->

	<script src="/assets/js/app.js"></script>
	<!-- <script src="../../../assets/demo/pages/form_layouts.js"></script> -->
	<!-- /theme JS files -->
	
	<!-- Vue JS files -->
	<script src="/assets/js/vue/axios.min.0.19.2.js"></script>
	<script src="/assets/js/vue/vue-dist.2.6.11.js"></script>	
	<script src="/assets/js/vue/vuex.3.6.2.js"></script>	
	<script src="/assets/js/vue/vue-router.3.3.1.min.js"></script>
	<!-- /vue JS files -->

	<script src="/assets/js/plugins/awesome-notifications/index.js"></script>
	<link href="/assets/js/plugins/awesome-notifications/style.css" rel="stylesheet" type="text/css">
	
	<script src="/assets/js/plugins/moment/moment.2.27.0.min.js"></script>
	<script>
		moment.lang("uk")
	</script>
	

	<script src="/vue/vue.utils.js?v<?=time()?>"></script>

	<script src="/assets/js/plugins/datatables/datatables.min.js"></script>


	<script src="/vue/store/vue-store-alert.js?v<?=time()?>"></script>
	<script src="/vue/store/vue-store-account.js?v<?=time()?>"></script>
	<script src="/vue/store/vue-store-demo.js?v<?=time()?>"></script>
	<script src="/vue/store/vue-store.js?v<?=time()?>"></script>	


</head>

<body>

	<div id="vue-app-container" style="overflow-y: auto" v-cloak>
	
		<!-- Main navbar -->
		<div v-if="auth" class="navbar navbar-dark navbar-expand-lg navbar-static border-bottom border-bottom-white border-opacity-10">
			<div class="container-fluid">
				<div class="d-flex d-lg-none me-2">
					<button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
						<i class="ph-list"></i>
					</button>
				</div>

				<div class="navbar-brand flex-1 flex-lg-0">
					<router-link to="/" class="d-inline-flex align-items-center">
						<img src="/assets/images/logo_icon.svg" alt="">
						<img src="/assets/images/logo_text_light.svg" class="d-none d-sm-inline-block h-16px ms-3" alt="">
					</router-link>
				</div>

				<ul class="nav flex-row">
					<li class="nav-item d-lg-none">
						<a href="#navbar_search" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="collapse">
							<i class="ph-magnifying-glass"></i>
						</a>
					</li>

					<color-scheme></color-scheme>



				</ul>

				<div class="navbar-collapse justify-content-center flex-lg-1 order-2 order-lg-1 collapse" id="navbar_search" style="visibility: hidden">
					<div class="navbar-search flex-fill position-relative mt-2 mt-lg-0 mx-lg-3">
						<div class="form-control-feedback form-control-feedback-start flex-grow-1" data-color-theme="dark">
							<input type="text" class="form-control bg-transparent rounded-pill" placeholder="Search" data-bs-toggle="dropdown">
							<div class="form-control-feedback-icon">
								<i class="ph-magnifying-glass"></i>
							</div>
							<div class="dropdown-menu w-100" data-color-theme="light">
								<button type="button" class="dropdown-item">
									<div class="text-center w-32px me-3">
										<i class="ph-magnifying-glass"></i>
									</div>
									<span>Search <span class="fw-bold">"in"</span> everywhere</span>
								</button>

								<div class="dropdown-divider"></div>

								<div class="dropdown-menu-scrollable-lg">
									<div class="dropdown-header">
										Contacts
										<a href="#" class="float-end">
											See all
											<i class="ph-arrow-circle-right ms-1"></i>
										</a>
									</div>

									<div class="dropdown-item cursor-pointer">
										<div class="me-3">
											<img src="/assets/images/demo/users/face3.jpg" class="w-32px h-32px rounded-pill" alt="">
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<div class="fw-semibold">Christ<mark>in</mark>e Johnson</div>
											<span class="fs-sm text-muted">c.johnson@awesomecorp.com</span>
										</div>

										<div class="d-inline-flex">
											<a href="#" class="text-body ms-2">
												<i class="ph-user-circle"></i>
											</a>
										</div>
									</div>

									<div class="dropdown-item cursor-pointer">
										<div class="me-3">
											<img src="/assets/images/demo/users/face24.jpg" class="w-32px h-32px rounded-pill" alt="">
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<div class="fw-semibold">Cl<mark>in</mark>ton Sparks</div>
											<span class="fs-sm text-muted">c.sparks@awesomecorp.com</span>
										</div>

										<div class="d-inline-flex">
											<a href="#" class="text-body ms-2">
												<i class="ph-user-circle"></i>
											</a>
										</div>
									</div>

									<div class="dropdown-divider"></div>

									<div class="dropdown-header">
										Clients
										<a href="#" class="float-end">
											See all
											<i class="ph-arrow-circle-right ms-1"></i>
										</a>
									</div>

									<div class="dropdown-item cursor-pointer">
										<div class="me-3">
											<img src="/assets/images/brands/adobe.svg" class="w-32px h-32px rounded-pill" alt="">
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<div class="fw-semibold">Adobe <mark>In</mark>c.</div>
											<span class="fs-sm text-muted">Enterprise license</span>
										</div>

										<div class="d-inline-flex">
											<a href="#" class="text-body ms-2">
												<i class="ph-briefcase"></i>
											</a>
										</div>
									</div>

									<div class="dropdown-item cursor-pointer">
										<div class="me-3">
											<img src="/assets/images/brands/holiday-inn.svg" class="w-32px h-32px rounded-pill" alt="">
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<div class="fw-semibold">Holiday-<mark>In</mark>n</div>
											<span class="fs-sm text-muted">On-premise license</span>
										</div>

										<div class="d-inline-flex">
											<a href="#" class="text-body ms-2">
												<i class="ph-briefcase"></i>
											</a>
										</div>
									</div>

									<div class="dropdown-item cursor-pointer">
										<div class="me-3">
											<img src="/assets/images/brands/ing.svg" class="w-32px h-32px rounded-pill" alt="">
										</div>

										<div class="d-flex flex-column flex-grow-1">
											<div class="fw-semibold"><mark>IN</mark>G Group</div>
											<span class="fs-sm text-muted">Perpetual license</span>
										</div>

										<div class="d-inline-flex">
											<a href="#" class="text-body ms-2">
												<i class="ph-briefcase"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>

						<a href="#" class="navbar-nav-link align-items-center justify-content-center w-40px h-32px rounded-pill position-absolute end-0 top-50 translate-middle-y p-0 me-1" data-bs-toggle="dropdown" data-bs-auto-close="outside">
							<i class="ph-faders-horizontal"></i>
						</a>

						<div class="dropdown-menu w-100 p-3">
							<div class="d-flex align-items-center mb-3">
								<h6 class="mb-0">Search options</h6>
								<a href="#" class="text-body rounded-pill ms-auto">
									<i class="ph-clock-counter-clockwise"></i>
								</a>
							</div>

							<div class="mb-3">
								<label class="d-block form-label">Category</label>
								<label class="form-check form-check-inline">
									<input type="checkbox" class="form-check-input" checked="">
									<span class="form-check-label">Invoices</span>
								</label>
								<label class="form-check form-check-inline">
									<input type="checkbox" class="form-check-input">
									<span class="form-check-label">Files</span>
								</label>
								<label class="form-check form-check-inline">
									<input type="checkbox" class="form-check-input">
									<span class="form-check-label">Users</span>
								</label>
							</div>

							<div class="mb-3">
								<label class="form-label">Addition</label>
								<div class="input-group">
									<select class="form-select w-auto flex-grow-0">
										<option value="1" selected="">has</option>
										<option value="2">has not</option>
									</select>
									<input type="text" class="form-control" placeholder="Enter the word(s)">
								</div>
							</div>

							<div class="mb-3">
								<label class="form-label">Status</label>
								<div class="input-group">
									<select class="form-select w-auto flex-grow-0">
										<option value="1" selected="">is</option>
										<option value="2">is not</option>
									</select>
									<select class="form-select">
										<option value="1" selected="">Active</option>
										<option value="2">Inactive</option>
										<option value="3">New</option>
										<option value="4">Expired</option>
										<option value="5">Pending</option>
									</select>
								</div>
							</div>

							<div class="d-flex">
								<button type="button" class="btn btn-light">Reset</button>

								<div class="ms-auto">
									<button type="button" class="btn btn-light">Cancel</button>
									<button type="button" class="btn btn-primary ms-2">Apply</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<ul class="nav flex-row justify-content-end order-1 order-lg-2">
					

					<li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
						<a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
							<div class="status-indicator-container">
							
								<div class="">
									<div class="bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-2">
										<i class="ph-user"></i>
									</div>
								</div>
										
								<span class="status-indicator bg-success"></span>
							</div>
							<span class="d-none d-lg-inline-block mx-lg-2">{{name}}</span>
						</a>

						<div class="dropdown-menu dropdown-menu-end">

							<a @click.prevent=" $store.dispatch('account/deleteAuth') " href="#" class="dropdown-item">
								<i class="ph-sign-out me-2"></i>
								Вийти
							</a>
						</div>
					</li>
				
				
				</ul>
			</div>
		</div>
		<!-- /main navbar -->
		<!-- Main navbar -->
		<div v-else class="navbar navbar-dark navbar-static py-2">
			<div class="container-fluid">
				<div class="navbar-brand">
					<router-link to="/" class="d-inline-flex align-items-center">
						<img src="/assets/images/logo_icon.svg" alt="">
						<img src="/assets/images/logo_text_light.svg" class="d-none d-sm-inline-block h-16px ms-3" alt="">
					</a>
				</div>

				<div class="d-flex justify-content-end align-items-center ms-auto">
				
					<ul class="nav flex-row">
						<color-scheme drop_pos='left'></color-scheme>
						<!--
						<li class="nav-item">
							<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
								<div class="d-flex align-items-center mx-md-1">
								<i class="ph-lifebuoy"></i>
								<span class="d-none d-md-inline-block ms-2">Support</span>
							</div>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
								<div class="d-flex align-items-center mx-md-1">
								<i class="ph-user-circle-plus"></i>
								<span class="d-none d-md-inline-block ms-2">Register</span>
							</div>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
								<div class="d-flex align-items-center mx-md-1">
								<i class="ph-user-circle"></i>
								<span class="d-none d-md-inline-block ms-2">Login</span>
							</div>
							</a>
						</li>
						-->
					</ul>
				
				</div>
			</div>
		</div>
		<!-- /main navbar -->


		<!-- Page content -->
		<div class="page-content">

		<!-- Main sidebar -->
		
		<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Inner content -->
				<div class="content-inner">

					<!-- Page header -->
					<div v-if="auth" class="page-header page-header-light shadow">
						<!--
						<div class="page-header-content d-lg-flex">
							<div class="d-flex">
								<h4 class="page-title mb-0">
									Dashboard
								</h4>

								<a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
									<i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
								</a>
							</div>

							<div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
								<div class="d-sm-flex align-items-center mb-3 mb-lg-0 ms-lg-3">

									<div class="vr d-none d-sm-block flex-shrink-0 my-2 mx-3"></div>

									<div class="d-inline-flex mt-3 mt-sm-0">

										<template v-for="device in $store.getters.devices">
											<a v-if="device.status_id == 5" href="#" class="d-inline-flex align-items-center justify-content-center bg-danger text-white lh-1 rounded-pill w-40px h-40px me-2"><span class="letter-icon">{{device.code}}</span></a>
										</template>

									</div>
								</div>
							</div>
						</div>
						-->

						<div class="page-header-content d-lg-flex border-top">
							<div class="d-flex">
								<div class="breadcrumb py-2">
									<router-link to="/" class="breadcrumb-item"><i class="ph-house"></i></router-link>
									<span class="breadcrumb-item active">Головна</span>
								</div>

								<a href="#breadcrumb_elements" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
									<i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
								</a>
							</div>

			
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content">
						<!-- Content area -->
						<router-view v-if="auth === true"></router-view>
						<page-login v-else-if=" auth === false "></page-login>
						<!-- /content area -->
					</div>
					<!-- /content area -->


					<!-- Footer -->
				<div class="navbar navbar-sm navbar-footer border-top" style="padding-bottom: 15px;padding-top: 15px;">
					<div class="container-fluid">
						<span>&copy; 2023</span>

						<ul class="nav">
						</ul>
					</div>
				</div>
					<!-- /footer -->

				</div>
				<!-- /inner content -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->



	</div>
	
	<script src="/vue/func.js?v<?=time()?>"></script>
	<script src="/assets/js/delphi.js?v<?=time()?>"></script>

	<script src="/vue/components/global-mixin.js?v<?=time()?>"></script>	
	<script src="/vue/components/v-datatable.js?v<?=time()?>"></script>	
	<script src="/vue/components/color-scheme.js?v<?=time()?>"></script>

	
	
	
	<script src="/vue/pages/NestedPage.js?v<?=time()?>"></script>
	<script src="/vue/pages/HomePage.js?v<?=time()?>"></script>
	<script src="/vue/pages/LoginPage.js?v<?=time()?>"></script>
	<script src="/vue/pages/TemplatesPostPage.js?v<?=time()?>"></script>
	<script src="/vue/pages/TemplatesPatchPage.js?v<?=time()?>"></script>
	<script src="/vue/pages/TemplatesDetailPage.js?v<?=time()?>"></script>

	
	<script src="/vue/vue-router.js?v<?=time()?>"></script>
	<script src="/vue/vue-app.js?v<?=time()?>"></script>
	
	
	
</body>
</html>
