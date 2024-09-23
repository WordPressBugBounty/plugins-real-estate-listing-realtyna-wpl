<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$benchmarker_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&home=benckmarker" ;

?>

<header>
	<nav class="hbp-navbar">
		<div class="hbp-container">
			<div class="hbp-row">
				<div class="hbp-navbar-header">
					<a class="hbp-navbar-brand" href="realtyna.com/" target="_blank">
						<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEsAAAAzBAMAAAAtEeh1AAAAMFBMVEVHcEz///////////////////////////////////////////////////////////9EPuwCAAAAD3RSTlMAQMB/IVkOqNDwML/gcJCy7YJGAAAB7ElEQVQ4y+3Vv0/bUBAH8K+VkIgKhJ+gDEgokfsHvEhu19Z065RIYemUMLK5XRnozmAGpi5JVKl/QKtOWdgZTIdK3aB/QeqAwebX9c4uTgRuyUKHtifZunf+6On8bD8DN6NORDHujAoz+lfYt6WJmInJ2Ea//8G9m0l7wX2zklpWC79gK0qpdprOSN3JZUXJd9N8TnIzlxmSfx9nszbHGJPh/m2WnMdYAm6xxmTM/MOszGmEXAaPzy7WVLbK+SwNf7TKv2OUDmTOgxx2KRcyljRKOYx+Psb/7AbbsDjcB9Z14LllOa+lhqzm4G8I237ioGnbj4FmAyUbMrA5fVdrdlHg8vx++iK9hZZlKtI2H20ZUOUYfk3vYI6/ff9M2OevQ+hHPFuBwoT1KpeqfD4bt3UkzKBTYaYRQ9c4m46ihMm1Ir0MedpDTsuR1HjruID+aDfQ2iHzmuEZvYH2Qk7rZz5PQlvekbRThVfbdDO2zg9SP43rAQbV1m5yC4fQr1TXoC3/OGOFCNyK5wWlzp4XMvvScZPe5JM+GrGhsBcUTHF5KL21uNm9/qd6qObjIllWd8SmKJi5Ug+5ATLLfOs80aAKdFaTvSRjGAStE2DThWqXlNNTaqHHb1XvvewUxiJvufw/4oqxuNLlXdr8ARos6kPg3lkcAAAAAElFTkSuQmCC' alt="Realtyna logo" title="Realtyna logo">
						<span>
                            <span class="hbp-fw-300">Realtyna</span>
                            <br/>
                            BENCHMARKER
                        </span>
					</a>
				</div>
				<div class="hbp-navbar-list">
					<ul class="hbp-navbar-item">
						<li><a href="https://realtyna.com/hosting/" target="_blank">Realtyna Ultra fast Hosting</a></li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
</header>
<div class="hbp-content hbp-text-center">
	<div class="hbp-container">
		<div class="hbp-row">
			<div class="hbp-col-lg-12">
				<div class="hbp-title">
					<h1>Realtyna Benchmarker</h1>
					<p>A unique tool to measure your server's performance</p>
				</div>
			</div>
		</div>
		<div class="hbp-result"></div>
		<div class="hbp-row">
			<div class="hbp-col-lg-12">
				<div class="hbp-start-button">
					<a id="hbp-start-test" href="<?php wpl_esc::url($benchmarker_link); ?>">
						<span id="hbp-start-ring" class="hbp-start-ring"></span>
						<span id="hbp-start-border" class="hbp-start-border"></span>
						<span id="hbp-start-text" class="hbp-start-text">
                                <span id='hbp-speed-test' class='hbp-speed-test'>Try Now!</span>
                                <span id="hbp-mbps-text" class="hbp-mbps-text"></span>
                            </span>
						<span class="hbp-progress-bar"></span>
					</a>
				</div>
				<div id="hbp-running" class="hbp-running"></div>
			</div>
		</div>
	</div>
</div>
<div class="hbp-top-shave"></div>
<div class="hbp-bottom-shave"></div>
