<div class="page-sidebar-wrapper">
	<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
	<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
	<div class="page-sidebar navbar-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
		<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
		<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
		<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
			<li>
				<a href="<?php echo base_url().'index.php/page/beranda'; ?>">
				<i class="icon-home"></i>
				<span class="title">Beranda</span>
				</a>
			</li>
			<li>
				<a href="javascript:;">
				<i class="icon-layers"></i>
				<span class="title">Master</span>
				<span class="arrow "></span>
				</a>
				<ul class="sub-menu">
					<li>
						<a href="#"><i class="icon-bar-chart"></i> Item 3 </a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#">
				<i class="icon-user"></i>
				<span class="title">Aplikan</span>
				</a>
			</li>
			<li>
				<a href="#">
				<i class="icon-check"></i>
				<span class="title">Jadwal</span>
				</a>
			</li>
			<li>
				<a href="#">
				<i class="icon-paper-plane"></i>
				<span class="title">Info</span>
				</a>
			</li>						
		</ul>
		<!-- END SIDEBAR MENU -->
	</div>
</div>