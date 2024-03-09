
<header class="shadow" id="header">
			<div class="container">
				<nav class="navbar navbar-expand navbar-light">
					<a class="navbar-brand" href="<?= $domain ?>index.php"><i class="bi bi-house-fill"></i>首页</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav">

							<li class="nav-item">
								<div class="btn-group"><a class="nav-link active dropdown-toggle cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-robot"></i>1.Cltx-AI后台</a>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="<?= $domain ?>manage/sugesstion/index.php"><i class="bi bi-robot"></i>PMO-建模</a></li>
										
									</ul>
								</div>

							</li>
				
							
						</ul>
					</div>
					<div class="d-flex">
						<?php
						if ( Auth::check( false ) ) {
							?>
						<a class="float-end nav-link" aria-current="page" href="<?= $domain ?>com_edit.php?per=claiht"><i class="bi bi-person-fill"></i><?= Auth::attr('cmp_name') ?></a>
						<?php
						} else {
							?>
						<a class="float-end nav-link" aria-current="page" href="/saas/com_login.php?per=claiht"><i class="bi bi-box-arrow-in-right"></i>登录</a>
						<a class="float-end nav-link" aria-current="page" href="/saas/com_register.php?per=claiht"><i class="bi bi-capslock-fill"></i>注册</a>
						<?php
						}
						?>
					</div>
				</nav>
			</div>
		</header>