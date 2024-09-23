<?php

/**
 * Realtyna Benchmarker
 * Copyright (C) 2022 Realtyna Inc.
 * All rights reserved.
 */

/**
 * Realtyna Benchmarker - client
 * @author Ashton <ashton@realtyna.com>
 */
class RealtynaBenchmarker
{
    /**
     * Client Version
     * @var string
     */
    public static $version = '1.0.1';

    /**
     * Backend API Endpoint
     * @var string
     */
    public static $api_endpoint = 'https://benchmarker.host/api';

    /**
     * Disk space required to run tests
     * @var int
     */
    public static $diskspace_required = 300 * 1024 * 1024;

    /**
     * Current Full URL
     * @var string
     */
    public $url;

    /**
     * Constructor
     * @author Ashton <ashton@realtyna.com>
     */
    public function __construct()
    {
        $this->url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")  . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        ini_set('memory_limit', '128M');
    }

    /**
     * Check for update
     * @author Ashton <ashton@realtyna.com>
     */
    public function check_for_update()
    {
        $data = [
            'op' => 'check-update',
            'version' => self::$version
        ];

        $result = self::api_request($data);

        if(stripos($result ?? '', 'update-available') !== false) {
            $this->view('update-prompt', $result);
        }
    }

    /**
     * Check available disk space
     * @author Ashton <ashton@realtyna.com>
     */
    public function check_disk_space()
    {
        $free_space_bytes = diskfreespace(__FILE__);
        if(!$free_space_bytes) return;
        if(self::$diskspace_required > $free_space_bytes) {
            $this->output(
                sprintf(
                    "Error: You need %dMB of free space at least. (%dMB free now)",
                    self::$diskspace_required / pow(1024, 2),
                    $free_space_bytes / pow(1024, 2)
				)
            );
        }
    }

    /**
     * Handle request and load proper view
     * @author Ashton <ashton@realtyna.com>
     */
    public function load()
    {
        $page = 'home';

        if(isset($_GET['run_tests']) or php_sapi_name() == "cli") $page = 'run-tests';

        if(isset($_GET['update'])) $page = 'update';

        $skip_update = isset($_GET['skip_update']);

        switch ($page) {
            case 'run-tests':
                $data = array();

                // Test Network
                $data['network'] = $this->test_network();

                // Test Disk
                $data['disk'] = $this->test_disk();

                // Test CPU
                $cpu_test = new CPUTest(8);
                $data['cpu'] = $cpu_test->run();

	            // Test Database
	            $data['database'] = $this->test_database();

                $this->view('run-tests', $data);
                break;

            case 'update':
                $updated = $this->update();
                $this->view('update', $updated);
                break;

            case 'home':
            default:
                // Load home
                $this->check_disk_space();
                if(!$skip_update) $this->check_for_update();
                $this->view('home');
                break;
        }
    }

    /**
     * Update to latest version
     * @author Ashton <ashton@realtyna.com>
     * @return array
     */
    public function update()
    {
        $request = [
            'op' => 'get-update-package'
        ];

        $update_package = self::api_request($request);
        if(!strlen($update_package)) return ['error' => 'Failed to download update package'];

        // Remove last byte to match MD5
        $update_package = substr($update_package, 0, -1);

        $request = [
            'op' => 'get-update-package-md5'
        ];

        $source_md5 = trim(self::api_request($request) ?? '');

        if(md5($update_package) !== $source_md5) return ['error' => 'Failed to verify update package integrity'];

        file_put_contents(__FILE__, $update_package);

        return ['success' => 1];
    }


    /**
     * Get a view's content
     * @author Ashton <ashton@realtyna.com>
     * @param string $page
     * @param string $params
     */
    public function view($page = 'home', $params = '')
    {
        switch($page) {
            case 'home':
                $this->output($this->view_home());
                break;

            case 'update-prompt':
                $this->output($this->view_update_prompt($params));
                break;

            case 'update':
                $this->output($this->view_update_status($params));
                break;

            case 'run-tests':
                $this->output($this->view_test_results($params));
                break;
        }
    }


    /**
     * Run Network test
     * @author Ashton <ashton@realtyna.com>
     * @return array
     */
    public function test_network()
    {
        $request = [
            'op' => 'get-file-url'
        ];

        $url = trim(self::api_request($request) ?? '');

        $file_path = __DIR__ . '/test.tar.xz';

        $start = microtime(true);

        self::download_file($url, $file_path);

        $time = microtime(true) - $start;

        if(!file_exists($file_path)) return ["error" => "Failed to download the test data."];

        $file_size = filesize($file_path);

        unlink($file_path);

        $speed = ($file_size * 8) / $time;

        return [
            'size' => $file_size,
            'time' => $time,
            'speed' => number_format($speed / pow(1024, 2)) . ' Mbps'
        ];
    }

    /**
     * Run Disk test
     * @return array
     */
    public function test_disk()
    {
        $file_path = __DIR__ . '/test.txt';
        $write_size = self::$diskspace_required * 1.00;
        $block_size = 2 * pow(1024, 2);
        $phrase = mt_rand(10000, 99999);
        $data = str_repeat($phrase, round($block_size / strlen($phrase)));

        $start = microtime(true);
        $written = 0;
        $written_count = 0;
        while($written < $write_size) {
            if(!file_put_contents($file_path, $data)) {
                return ['error' => 'Failed to write to temp file'];
            }
            $written += $block_size;
            $written_count++;
        }

        $time = microtime(true) - $start;
        unlink($file_path);

        $written = $written / pow(1024, 2);
        $rate = $written / $time;

        return [
            'time' => $time,
            'written' => [
                'count' => $written_count,
                'size' => $written
            ],
            'rate' => $rate
        ];
    }

	/**
	 * Run database test
	 *
	 * @author Noah <noah.s@realtyna.com>
	 * @static
	 * @since WPL 4.13
	 * @date 09/01/2023
	 * @return array
	 */
	public function test_database()
	{
		// check wpl_properties table engine in DB is innodb
		$tableDetails = wpl_db::select( "SHOW TABLE STATUS WHERE Name = '#__wpl_properties'" , 'loadAssoc' );
		$tableEngine = !empty( $tableDetails['Engine'] ) ? strtolower( $tableDetails['Engine'] ) : 'unknown';

		return [
			'is_innodb' => $tableEngine == 'innodb'
		];
	}

    /**
     * View home page
     * @author Ashton <ashton@realtyna.com>
     * Updated on
     * @return string
     */
    protected function view_home()
    {
    	$view = sprintf('<div class="hbp-result"></div>
								<div class="hbp-row">
									<div class="hbp-col-lg-12">
										<div class="hbp-start-button">
											<a id="hbp-start-test" href="benchmarker.php">
												<span id="hbp-start-ring" class="hbp-start-ring-high"></span>
												<span id="hbp-start-border" class="hbp-start-border"></span>
												<span id="hbp-start-text" class="hbp-start-text">
						                                <span id="hbp-speed-test" class="hbp-speed-test">Testing...</span>
						                                <span id="hbp-mbps-text" class="hbp-mbps-text"></span>
						                            </span>
												<span class="hbp-progress-bar"></span>
											</a>
										</div>
										<div id="hbp-running" class="hbp-running"></div>
									</div>
								</div>
								<script>window.location = "%s";</script>'
		                        ,$this->url . (strpos($this->url ?? '', '?') === false ? '?' : '&') . 'run_tests');

	    return $this->view_template($view);
    }

    /**
     * View update prompt page
     * @author Ashton <ashton@realtyna.com>
     * @param $response
     * @return string
     */
    protected function view_update_prompt($response)
    {
        $result = '';
        $message = str_replace("update-available", "", $response ?? '');

        $result .= '<h3>An update is available!</h3><hr/>';
        if(trim($message ?? '')) $result .= $message;
        $result .= sprintf('<a href="%s">Update Now</a>', $this->url . '?update');

        return $result;
    }

    /**
     * View update status page
     * @param $result
     * @return string
     */
    protected function view_update_status($result)
    {
        if(isset($result['error'])) {
            return sprintf(
                '<h3>Update failed</h3>
					<p>
						<b>Error:</b> %s
					</p>
					<a href="#" onclick="window.location.reload()">Retry</a>
					&nbsp;&nbsp;
					<a href="?skip_update">Skip</a>
				',
                $result['error']
            );
        }

        return sprintf(
            '<h3>Update successful</h3>
			<hr/>
			<p>Please wait, Redirecting...</p>
			<script>
				setTimeout(function() {
					window.location = "%s";
				}, 2000);
			</script>
			',
            str_replace('?update', '', $this->url ?? '')
        );
    }

    /**
     * View test results page
     * Updated by Zein on 26 February 2022
     * @param $data
     * @return string
     */
    protected function view_test_results($data)
    {
	    $networkResult = $diskResult = $cpuResult = $databaseResult = '';

	    if(isset($data['network']['error'])) {
		    $networkResult = "<span class='hbp-speed-text'>Error!</span>";
		    $networkResult .= "<h4>".$data['network']['error']."</h4>";
        } else {
		    $networkResult = "<h3>";
            $networkResult .= "<span class='hbp-speed-text'>Speed</span>";
		    $networkResult .= "<span class='hbp-measure-text'>" . $data['network']['speed'] . "</span>";
		    $networkResult .= "<span class='hbp-unit-text'>Mbps</span>";
		    $networkResult .= "</h3>";
		    $networkResult .= "<h4>";
		    $networkResult .= "(Downloaded ". round( $data['network']['size'] / pow(1024, 2),2) ."MB in ".round( $data['network']['time'],2)." seconds)";
            $networkResult .= "</h4>";
	    }


	    if(isset($data['disk']['error'])) {
		    $diskResult = "<span class='hbp-speed-text'>Error!</span>";
		    $diskResult .= "<h4>".$data['disk']['error']."</h4>";
	    } else {
		    $diskResult = "<h3>";
		    $diskResult .= "<span class='hbp-speed-text'>Speed</span>";
		    $diskResult .= "<span class='hbp-measure-text'>" . round($data['disk']['rate'] ,2) . "</span>";
		    $diskResult .= "<span class='hbp-unit-text'>MB/s</span>";
		    $diskResult .= "</h3>";
		    $diskResult .= "<h4>";
		    $diskResult .= "(Created ". $data['disk']['written']['count'] ." files, ". $data['disk']['written']['size'] ."MB in total size)";
		    $diskResult .= "</h4>";
	    }


	    if(!is_array($data['cpu'])) {
		    $cpuResult = "<h4>".$data['cpu']."</h4>";
	    } else {
		    $cpuResult = "<h3>";
		    $cpuResult .= "<span class='hbp-speed-text'>Score</span>";
		    $cpuResult .= "<span class='hbp-measure-text'>" . $data['cpu']['score'] . "</span>";
		    $cpuResult .= "<span class='hbp-unit-text'>of 10</span>";
		    $cpuResult .= "</h3>";
		    $cpuResult .= "<h4>";
		    $cpuResult .= "System Load averages";
		    $cpuResult .= "<span class='hbp-sub-text'>(Lower is better)</span>";
		    $cpuResult .= "</h4>";
		    $cpuResult .= "<table class='hbp-load-avg'><tbody>";
		    foreach ($data['cpu']['load_avg'] as $minutes => $load_avg)
		    {
			    $minutes_labeled = $minutes . ($minutes > 1 ? ' minutes' : ' minute');
			    $cpuResult .= "<tr>";
			    $cpuResult .= "<td>".$minutes_labeled."</td>";
			    $cpuResult .= "<td>".$load_avg['value']."</td>";
			    $cpuResult .= "<td>".$load_avg['label']."</td>";
			    $cpuResult .= "</tr>";
		    }
		    $cpuResult .= "</tbody></table>";
	    }

	    $databaseResult = "<h4>";
	    $databaseResult .= "<ul>";
	    if ($data['database']['is_innodb']) {
		    $databaseResult .= "<li class='hbp-db-text'>wpl_properties table engine is <strong>InnoDB</strong></li>";
	    } else {
		    $databaseResult .= "<li class='hbp-db-text'>wpl_properties table engine is NOT <strong>InnoDB</strong></li>";
	    }
	    $databaseResult .= "</ul>";
	    $databaseResult .= "</h4>";

	    $result = '<div class="hbp-result">
                        <div class="hbp-row">
                            <div class="hbp-col-lg-6">
                                <div class="hbp-network-test">
                                        <span class="hbp-network-icon">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAdBAMAAAAqZzNAAAAAMFBMVEVHcExg9bRg/69g97Ng97Rh9rRg9rRg9rRg9rRg97dg97Rg9rRg9bRg9bNg+rVh9rQJL3inAAAAD3RSTlMA8BBAZOCawLAgf9nQUDAuwXo2AAABEUlEQVQoz2NgAAPmZYX//4tnGTAgAJPHfyhoUYCJcSb+hwOxCVB1+WDuR3kw9Q2i1hHI/L4YqILTqh7IFAGJqQMZJVCjmNyBnCIGBj6grmKEneZAcx4wGP7/H4vkEIaL//8LM8T//6GALMjU//8rg/z/BSD2k/b//ys8QSwuoKANSDPTUogro0Carh6G6FkIc7sUwhwdEP9rPIg8BBNjBroq7AIDA28q0D2wMJn4/38IhOX6/78kVNAe5mFQMHyGGfnRAWYSizzcUEsg9pD/2AKkJiN75CrIahT/MjCwQZyZgCJoD3HnZzRBEQUmRzRBNglQyDeiascFVJywCJ7/gynG/P//AwxBJvmPWLS/6IMzAbCSnrqWbAhAAAAAAElFTkSuQmCC" alt="Network Test" title="Network Test">
                                        </span>
                                    <h2>Network Test</h2>'
                                    .$networkResult.
                                '</div>
                            </div>
                            <div class="hbp-col-lg-6">
                                <div class="hbp-disk-test">
                                        <span class="hbp-disk-icon">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAArBAMAAADvZkc5AAAALVBMVEWOyv9HcEyPz/+Py/+Oyv+Oyv+Oyv+Ny/+Nyv+Myf+Oyf+Pyv+Oyf+Pz/+Oyv8YDNmyAAAADnRSTlPAABBAmtDwa4BQ4DCwIA06JtAAAAHYSURBVCjPVdIxb9NAFAfwY0JdrDzbqRtMEA40kRAMJQI2S0EMTCc1K5KHsJRWWAoMMFXqQoeiSB6pqgoxIDHUapbu8AEsZWFEDvalqWveZ+Dd2UndG6Lzz3e+9/4XZnhYHSJgbsyqQ0uY14Xq6AqG7rAC1iWy2HIqogckatb8+GJ/omZ+IZ2QjsneyammpOHn77vP+CxYCp/Lb+mjdCGmGHe0HMD2eqXwvuWLFXq/nhZiZA7fUgfp4YaSWwltK8pxN5Uc946/lQWaiZJoHI6hw2Q1dq5k20ihGWImd36XYqTmBdTnLb4pK9uQMq/9BbfXun1JMiplCqMhs86vpBHQmi9mRWjW3oGzweI7upDdI0OZpefI0zXZwSH+kG0IVc/u3jLUtXMl7bdLaQ+UWLl8aGzRT7RX5CODAn5BdyPKxB5QUHeRmNN+j2UHYIevDJ9en86oWSpDOPAQIxzoR/gLpAjeB9hF/BqijJokNsWk6dENxgdQCvA3T9OotyhJioX4QThVgei1e1W2WiMe+fHLITy+Z08KWUvquYdTY85Xb5RrMmrjCYl2R8ns/oTnDtQKsZ8j8/8EQAfVpnbyqf6z8W/GoiIvuo8is5T9xuvjhOmfr/3Dbzr/ATLM8i5IXnTOAAAAAElFTkSuQmCC" alt="Disk Test" title="Disk Test">
                                        </span>
                                    <h2>Disk Test</h2>'
                                    .$diskResult.
                                '</div>
                            </div>
                            <div class="hbp-col-lg-6">
                                <div class="hbp-cpu-test">
                                        <span class="hbp-cpu-icon">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoBAMAAAB+0KVeAAAALVBMVEXfl/9HcEzflv/gl//jlv/gl//fl//hl//gl//gl//flf/gl//hl//fn//gl//cc40nAAAADnRSTlOAAMC/P9xBf2bwMJCgEAmBig8AAAE6SURBVCjPYxAUFOwUtJaaJh0oypi5cLPgDKAAAxDXLXwk8UDooZwiX6Ke2AOo4L7ERwIYgnxwQUa44A2740+LH5c+rwu3M4+redwLFux7hwJeAAXby/UUGJAA0+PyMoZ5794xCiIBgXfvXjJIpZ1DFXyTthBoJh+qINB6BjuQ9jiYLU9B2h8zbFKKYxR8qQQF8wQFnippQ7Q/gmnWg2hf0m6HKvi43AviJGRBkJMSGPRQBR8xsGE3s9g4DlXwqbE5QxymmU+xa8cqiFU7kxK6RUoK2LWnl2N4swy7N5dhejMLu5mbQd58aQwFoEA2tmbQw4yOR9gjTsoFPYpdFmJLDE8ZGjbFHXFBAj5PtTmwJzBgUtQ78JTpEesDvgA9hTieR724Ey2SYCOSILaM0CmoLTVNAJxlNoGzDAA1TufXrtakQwAAAABJRU5ErkJggg==" alt="CPU Test" title="CPU Test">
                                        </span>
                                    <h2>CPU Test</h2>'
                                    .$cpuResult.
                                '</div>
                            </div>
                            <div class="hbp-col-lg-6">
                                <div class="hbp-db-test">
                                        <span class="hbp-db-icon">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDcuMi1jMDAwIDc5LjU2NmViYzViNCwgMjAyMi8wNS8wOS0wODoyNTo1NSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIzLjQgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkZEMjI1NDYxOTAzNzExRUQ5MjJEQTMxQTQ0MjNDRTA1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkZEMjI1NDYyOTAzNzExRUQ5MjJEQTMxQTQ0MjNDRTA1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RkQyMjU0NUY5MDM3MTFFRDkyMkRBMzFBNDQyM0NFMDUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RkQyMjU0NjA5MDM3MTFFRDkyMkRBMzFBNDQyM0NFMDUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4MzHi3AAAIr0lEQVR42sRZ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" alt="Database Test">
                                        </span>
                                    <h2>Database Test</h2>'
	                                .$databaseResult.
	                            '</div>
                            </div>
                        </div>
                    </div>
                    <div class="hbp-row">
                        <div class="hbp-col-lg-12">
                            <div class="hbp-start-button">
                                <a id="hbp-start-test" href="benchmarker.php">
                                    <span id="hbp-start-ring" class="hbp-start-ring"></span>
                                    <span id="hbp-start-border" class="hbp-start-border"></span>
                                    <span id="hbp-start-text" class="hbp-start-text">
                                            <span id="hbp-speed-test" class="hbp-speed-test">Again!</span>
                                            <span id="hbp-mbps-text" class="hbp-mbps-text"></span>
                                        </span>
                                    <span class="hbp-progress-bar"></span>
                                </a>
                            </div>
                            <div id="hbp-running" class="hbp-running">
                            Get Ultra Fast Hosting at <a href="https://hosting.realtyna.com/" target="_blank">hosting.realtyna.com</a>
							</div>
                   
                        </div>
                    </div>';
	    $title = '<h1>Test Result</h1>';
        return $this->view_template($result,$title);
    }

	/**
	 * Create full view of content's template
	 * @author Zein <zein.z@realtyna.net>
	 * @param string $hunk
	 * @return  string
	 */
	public function view_template($hunk , $title = '<h1>Realtyna Benchmarker</h1><p>A unique tool to measure your server\'s performance</p>') {

		$page_view = '<header>
						<nav class="hbp-navbar">
							<div class="hbp-container">
								<div class="hbp-row">
									<div class="hbp-navbar-header">
										<a class="hbp-navbar-brand" href="realtyna.com" target="_blank">
											<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEsAAAAzBAMAAAAtEeh1AAAAMFBMVEVHcEz///////////////////////////////////////////////////////////9EPuwCAAAAD3RSTlMAQMB/IVkOqNDwML/gcJCy7YJGAAAB7ElEQVQ4y+3Vv0/bUBAH8K+VkIgKhJ+gDEgokfsHvEhu19Z065RIYemUMLK5XRnozmAGpi5JVKl/QKtOWdgZTIdK3aB/QeqAwebX9c4uTgRuyUKHtifZunf+6On8bD8DN6NORDHujAoz+lfYt6WJmInJ2Ea//8G9m0l7wX2zklpWC79gK0qpdprOSN3JZUXJd9N8TnIzlxmSfx9nszbHGJPh/m2WnMdYAm6xxmTM/MOszGmEXAaPzy7WVLbK+SwNf7TKv2OUDmTOgxx2KRcyljRKOYx+Psb/7AbbsDjcB9Z14LllOa+lhqzm4G8I237ioGnbj4FmAyUbMrA5fVdrdlHg8vx++iK9hZZlKtI2H20ZUOUYfk3vYI6/ff9M2OevQ+hHPFuBwoT1KpeqfD4bt3UkzKBTYaYRQ9c4m46ihMm1Ir0MedpDTsuR1HjruID+aDfQ2iHzmuEZvYH2Qk7rZz5PQlvekbRThVfbdDO2zg9SP43rAQbV1m5yC4fQr1TXoC3/OGOFCNyK5wWlzp4XMvvScZPe5JM+GrGhsBcUTHF5KL21uNm9/qd6qObjIllWd8SmKJi5Ug+5ATLLfOs80aAKdFaTvSRjGAStE2DThWqXlNNTaqHHb1XvvewUxiJvufw/4oqxuNLlXdr8ARos6kPg3lkcAAAAAElFTkSuQmCC" alt="Realtyna logo" title="Realtyna logo">
											<span>
					                                <span class="hbp-fw-300">Realtyna</span>
					                                <br />
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
									<div class="hbp-title">'
										.$title.
									'</div>
								</div>
							</div>'
		             .$hunk.
		             '</div>
						</div>
						<div class="hbp-top-shave"></div>
						<div class="hbp-bottom-shave"></div>
						</body>
						</html>';
		return $page_view;
	}

    /**
     * Download a file
     * @author Ashton <ashton@realtyna.com>
     * @param $url
     * @param $path
     * @return bool
     */
    public static function download_file($url, $path = '')
    {
        if($path) {
            $fp = fopen($path, 'w');
            if ($fp === false) return false;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if($path) curl_setopt($ch, CURLOPT_FILE, $fp);
        else curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        if(curl_errno($ch)) return false;
        $status_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($path) {
            fclose($fp);
            return $status_code == 200;
        }
        else {
            return $output;
        }
    }

    public static function get_test_data()
    {
        $request = [
            'op' => 'get-testdata-url'
        ];

        $url = trim(self::api_request($request) ?? '');

        $content = false;
        $file_path = __DIR__ . '/test.jpg';
        if(self::download_file($url, $file_path)) {
            $content = file_get_contents($file_path);
            unlink($file_path);
        }
        return $content;
    }

    /**
     * Send an API request and return the response
     * @author Ashton <ashton@realtyna.com>
     * @param array $data
     * @return bool|string
     */
    public static function api_request(array $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$api_endpoint);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    /**
     * Write to output
     * @author Ashton <ashton@realtyna.com>
     * @param $content
     * @param bool $exit
     */
    protected function output($content, $exit = true)
    {
        wpl_esc::e($content . PHP_EOL);
        if($exit) exit;
    }
}

class CPUTest
{
    public $iterations = 8;
    public static $test_data = '';
    public static $stats = [
        'Intel(R) Core(TM) i7-3720QM CPU @ 2.60GHz' => 5.97,
        'Intel(R) Xeon(R) Platinum 8275CL CPU @ 3.00GHz' => 5.73,
        'Intel(R) Xeon(R) CPU E5-2676 v3 @ 2.40GHz' => 8.25,
    ];

    public function __construct($iterations = 8)
    {
        @ini_set('memory_limit', -1);
        $this->iterations = $iterations;
        if(!self::$test_data) self::$test_data = RealtynaBenchmarker::get_test_data();
    }

    public function run()
    {
        if(!self::$test_data) return 'Error: failed to acquire test data.';
        $start = microtime(true);
        $methods = get_class_methods($this);
        for($i = 0; $i < $this->iterations; $i++) {
            foreach ($methods as $method) {
                if (!preg_match("/^test_.+/", $method)) continue;
                $result = $this->$method();
                if (stripos($result ?? '', 'error') !== false) return $result;
            }
        }

        $runtime = microtime(true) - $start;
        return [
            'runtime' => $runtime,
            'score' => self::calculate_score($runtime),
            'load_avg' => self::get_load_averages()
        ];
    }

    protected function test_compression()
    {
        if(!self::$test_data) return false;
        if(!function_exists('gzcompress')) return "Error: PHP Zlib extension is not installed.";

        $compressed = gzcompress(self::$test_data, 9);
        gzuncompress($compressed);
        unset($compressed);

        $deflated = gzdeflate(self::$test_data, 9);
        gzinflate($deflated);
        unset($deflated);
    }

    protected function test_cryptography()
    {
        if(!function_exists('openssl_encrypt')) return "Error: PHP OpenSSL extension is not installed.";

        $ciphering = "aes256";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        $iv = '';
        for($i = 0; $i < $iv_length; $i++) $iv .= mt_rand(0,9);
        $encryption_key = "RealtynaBenchmarkerCPUTest";

        $encrypted = openssl_encrypt(self::$test_data, $ciphering, $encryption_key, $options, $iv);
        openssl_decrypt($encrypted, $ciphering, $encryption_key, $options, $iv);

        password_hash(self::$test_data, PASSWORD_DEFAULT);
    }

    public static function calculate_score($time, $round_precision = 1)
    {
        // Score Range
        $a = 1;
        $b = 10;

        // Data Range
        $cpu_low = self::get_strongest_cpu();
        $cpu_high = self::get_weakest_cpu();

        $x = $time;
        $min = $cpu_low['time'];
        $max = $cpu_high['time'];

        if($time < $min) return $b;
        if($time > $max) return 0;

        // Map data to score range
        $score = ((($b - $a) * ($x - $min)) / ($max - $min)) + $a;
        $score = ($b + $a) - $score;

        return round($score, $round_precision, PHP_ROUND_HALF_DOWN);
    }

    public static function get_weakest_cpu()
    {
        $max = max(self::$stats);
        $max_model = array_search($max, self::$stats);
        return [
            'model' => $max_model,
            'time' => $max
        ];
    }

    public static function get_strongest_cpu()
    {
        $min = min(self::$stats);
        $min_model = array_search($min, self::$stats);
        return [
            'model' => $min_model,
            'time' => $min
        ];
    }

    public static function get_load_averages()
    {
        
		$load_avg = function_exists('sys_getloadavg') ? sys_getloadavg() : 0;
        $load_minutes = [1, 5, 15];
        $result = [];
        foreach ($load_avg as $i => $load)
        {
            $label = 'OK';
            if($load <= 2.5) $label = 'Low';
            if($load > 5) $label = 'High';
            if($load > 8) $label = 'Very High';

            $result[$load_minutes[$i]] = [
                'value' => $load,
                'label' => $label
            ];
        }
        return $result;
    }
}

$checker = new RealtynaBenchmarker();
$checker->load();