<?php
class ApacheLogAnalyzer {
    private $accessLog;
    private $errorLog;
    private $pageStats = [];
    private $ipStats = [];
    private $browserStats = [];
    private $timeStats = [];

    public function __construct($accessLogPath, $errorLogPath) {
        $this->accessLog = $accessLogPath;
        $this->errorLog = $errorLogPath;
    }

    public function analyze() {
        if (!file_exists($this->accessLog)) {
            throw new Exception("Access log file not found");
        }

        $handle = fopen($this->accessLog, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $this->processLogLine($line);
            }
            fclose($handle);
        }

        return $this->generateReport();
    }

    private function processLogLine($line) {
        // Regular expression to parse Apache combined log format
        $pattern = '/^(\S+) \S+ \S+ \[([^\]]+)\] "(\S+) (.?) (\S+)" (\d{3}) (\d+) "([^"])" "([^"]*)"$/';
        
        if (preg_match($pattern, $line, $matches)) {
            $ip = $matches[1];
            $datetime = $matches[2];
            $method = $matches[3];
            $path = $matches[4];
            $protocol = $matches[5];
            $status = $matches[6];
            $bytes = $matches[7];
            $referrer = $matches[8];
            $userAgent = $matches[9];

            // Clean the path (remove query strings)
            $path = strtok($path, '?');

            // Update page statistics
            if (!isset($this->pageStats[$path])) {
                $this->pageStats[$path] = 0;
            }
            $this->pageStats[$path]++;

            // Update IP statistics
            if (!isset($this->ipStats[$ip])) {
                $this->ipStats[$ip] = [];
            }
            if (!isset($this->ipStats[$ip][$path])) {
                $this->ipStats[$ip][$path] = 0;
            }
            $this->ipStats[$ip][$path]++;

            // Update browser statistics
            $browser = $this->getBrowserInfo($userAgent);
            if (!isset($this->browserStats[$browser])) {
                $this->browserStats[$browser] = 0;
            }
            $this->browserStats[$browser]++;

            // Update time statistics
            $timestamp = strtotime($datetime);
            $date = date('Y-m-d', $timestamp);
            if (!isset($this->timeStats[$date])) {
                $this->timeStats[$date] = 0;
            }
            $this->timeStats[$date]++;
        }
    }

    private function getBrowserInfo($userAgent) {
        if (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Edge') === false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident/') !== false) {
            return 'Internet Explorer';
        } else {
            return 'Other';
        }
    }

    private function generateReport() {
        // Sort arrays by value in descending order
        arsort($this->pageStats);
        arsort($this->browserStats);
        ksort($this->timeStats);

        return [
            'pages' => $this->pageStats,
            'ips' => $this->ipStats,
            'browsers' => $this->browserStats,
            'dates' => $this->timeStats
        ];
    }

    public function displayReport($report) {
        echo "<h2>Page Access Statistics</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Page</th><th>Hits</th></tr>";
        foreach ($report['pages'] as $page => $hits) {
            echo "<tr><td>" . htmlspecialchars($page) . "</td><td>$hits</td></tr>";
        }
        echo "</table>";

        echo "<h2>IP Address Statistics</h2>";
        echo "<table border='1'>";
        echo "<tr><th>IP Address</th><th>Pages Accessed</th></tr>";
        foreach ($report['ips'] as $ip => $pages) {
            echo "<tr><td>$ip</td><td><ul>";
            foreach ($pages as $page => $hits) {
                echo "<li>" . htmlspecialchars($page) . ": $hits hits</li>";
            }
            echo "</ul></td></tr>";
        }
        echo "</table>";

        echo "<h2>Browser Statistics</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Browser</th><th>Hits</th></tr>";
        foreach ($report['browsers'] as $browser => $hits) {
            echo "<tr><td>$browser</td><td>$hits</td></tr>";
        }
        echo "</table>";

        echo "<h2>Daily Access Statistics</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Date</th><th>Hits</th></tr>";
        foreach ($report['dates'] as $date => $hits) {
            echo "<tr><td>$date</td><td>$hits</td></tr>";
        }
        echo "</table>";
    }
}

// Usage example
try {
    $analyzer = new ApacheLogAnalyzer('/var/log/apache2/access.log', '/var/log/apache2/error.log');
    $report = $analyzer->analyze();
    $analyzer->displayReport($report);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>