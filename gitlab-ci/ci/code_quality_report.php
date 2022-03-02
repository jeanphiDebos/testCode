<?php
include 'Sniffs.php';

$rawFileReport = file_get_contents(dirname(__FILE__) . '/../phpcs-quality-report.json');
if ($rawFileReport === false) {
    die('file not found or error ocurred reading report');
}

$report = json_decode($rawFileReport, true);
if ($report === null) {
    die('report can not be decoded');
}

print '[';
$firstLine = true;
if (is_array($report['files'])) {
    foreach ($report['files'] as $phpcsFile => $phpcsIssues) {
        foreach ($phpcsIssues['messages'] as $phpcsIssueData) {
            if (Sniffs::isValidIssue($phpcsIssueData)) {
                $path               = preg_replace('/^\/code\//', '', $phpcsFile);
                $checkName          = str_replace('.', ' ', $phpcsIssueData['source']);
                $cleanedSingleIssue = [
                    'type'               => 'issue',
                    'check_name'         => $checkName,
                    'description'        => $phpcsIssueData['message'],
                    'categories'         => ['Style'],
                    'location'           => [
                        'path'  => $path,
                        'lines' => [
                            'begin' => $phpcsIssueData['line'],
                            'end'   => $phpcsIssueData['line'],
                        ],
                    ],
                    'remediation_points' => Sniffs::pointsFor($phpcsIssueData),
                    'engine_name'        => 'phpcodesniffer',
                    'fingerprint'        => md5($path . $checkName . $phpcsIssueData['line']),
                ];
                if (!$firstLine) {
                    print ',';
                } else {
                    $firstLine = false;
                }

                print json_encode($cleanedSingleIssue, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
    }
}

print ']';
