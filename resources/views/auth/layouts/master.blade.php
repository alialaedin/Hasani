<!DOCTYPE html>
<html lang="fa" dir="rtl">
	<head>
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="DayOne - It is one of the Major Dashboard Template which includes - HR, Employee and Job Dashboard. This template has multipurpose HTML template and also deals with Task, Project, Client and Support System Dashboard." name="description">
		<meta content="Spruko Technologies Private Limited" name="author">
		<meta name="keywords" content="admin dashboard, admin panel template, html admin template, dashboard html template, bootstrap 4 dashboard, template admin bootstrap 4, simple admin panel template, simple dashboard html template,  bootstrap admin panel, task dashboard, job dashboard, bootstrap admin panel, dashboards html, panel in html, bootstrap 4 dashboard"/>
		<title>@yield('title', config('app.name'))</title>
		@include("auth.layouts.includes.styles")
		@yield("styles")
	</head>

	<body class="app sidebar-mini">

		<div id="global-loader" >
			<img src="{{asset('assets/images/svgs/loader.svg')}}" alt="loader">
		</div>

		<div class="page login-bg">
			<div class="page-single">
				<div class="container">
					<div class="row">
						<div class="col mx-auto">
							<div class="row justify-content-center">
								<div class="col-md-7 col-lg-5">
									@yield('content')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include("admin.layouts.includes.scripts")
		@yield("scripts")

	</body>
</html>
